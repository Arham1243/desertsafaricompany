<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (isset($cart['total_price']) && $cart['total_price'] === 0) {
            Session::forget('cart');

            return redirect()->route('cart.index')->with('notify_error', 'Your cart is empty.');
        }

        if (! empty($cart)) {
            $tours = Tour::where('status', 'publish')->get();

            // Initialize required data arrays for checkout view
            $cartTours = [];
            $promoToursData = [];
            $toursNormalPrices = [];
            $privateTourData = [];
            $waterTourTimeSlots = [];

            // Build cartTours array from cart data
            if (isset($cart['tours'])) {
                foreach ($cart['tours'] as $tourId => $tourData) {
                    $tour = $tours->where('id', $tourId)->first();
                    if ($tour) {
                        $cartTours[] = $tour;
                    }
                }
            }

            $data = compact('tours', 'cart', 'cartTours', 'promoToursData', 'toursNormalPrices', 'privateTourData', 'waterTourTimeSlots');

            return view('frontend.tour.checkout.index')
                ->with('title', 'Checkout')
                ->with($data);
        }

        return redirect()->route('frontend.index')->with('notify_error', 'Your cart is empty.');
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        $totalAmount = $cart['total_price'] ?? 0;

        if ($totalAmount <= 0) {
            return redirect()
                ->route('frontend.index')
                ->with('notify_error', 'Your cart is empty.');
        }
        $order = $this->createOrder($request, $cart, $totalAmount);

        $this->sendAdminOrderEmail('emails.admin-pending-order', $order, 'New Pending Order', route('locations.country', 'ae'));

        return $this->processPayment($request, $order);
    }

    protected function createOrder(Request $request, array $cart, float $totalAmount): Order
    {
        return Order::create([
            'user_id' => auth()->id(),
            'request_data' => json_encode($request->order),
            'cart_data' => json_encode($cart),
            'payment_type' => $request->payment_type,
            'payment_status' => 'pending',
            'total_amount' => $totalAmount,
        ]);
    }

    protected function processPayment(Request $request, Order $order)
    {
        return match ($request->payment_type) {
            'stripe' => $this->handleStripe($request, $order),
            'paypal' => $this->handlePaypal($request, $order),
            'tabby' => $this->handleTabby($request, $order),
            'postpay' => $this->handlePostpay($request, $order),
            'tamara' => $this->handleTamara($request, $order),
            'cod' => $this->handleCOD($order),
            default => redirect()->back()->with('notify_error', 'Invalid payment type'),
        };
    }

    /**
     * ---------------- STRIPE ----------------
     */
    private function handleStripe(Request $request, Order $order)
    {
        $session = $this->createStripeSession($request, $order);

        if (! isset($session->id)) {
            return redirect()
                ->route('checkout.error', ['order_id' => $order->id])
                ->with('notify_error', 'Failed to create Stripe session');
        }

        $order->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    private function createStripeSession(Request $request, Order $order)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $cart = Session::get('cart', []);

            if (empty($cart['total_price'])) {
                return response()->json(['error' => 'Cart total missing']);
            }

            $totalAmount = round($cart['total_price'], 2) * 100;

            return \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => env('APP_CURRENCY'),
                        'product_data' => ['name' => 'Order #'.$order->id],
                        'unit_amount' => $totalAmount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success', ['order_id' => $order->id]),
                'cancel_url' => route('checkout.cancel', ['order_id' => $order->id]),
                'client_reference_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * ---------------- TABBY ----------------
     */
    private function handleTabby(Request $request, Order $order)
    {
        $cart = Session::get('cart', []);
        $totalAmount = $cart['total_price'] ?? 0;

        return $this->createTabbySession($request, $order, $totalAmount);
    }

    private function createTabbySession(Request $request, Order $order, float $totalAmount)
    {
        $tabbyData = [];
        $cart = Session::get('cart', []);

        foreach ($cart['tours'] as $tourId => $tour) {
            $tabbyData[] = [
                'title' => $tour['data']['title'] ?? 'Tour #'.$tourId,
                'unit_price' => $tour['data']['total_price'] ?? 0,
                'quantity' => 1,
            ];
        }

        $postData = [
            'payment' => [
                'amount' => $totalAmount,
                'currency' => env('APP_CURRENCY'),
                'description' => env('APP_NAME'),
                'buyer' => [
                    'name' => Auth::user()->full_name,
                    'email' => Auth::user()->email,
                ],
                'order' => [
                    'reference_id' => $order->id,
                    'items' => $tabbyData,
                ],
            ],
            'lang' => 'en',
            'merchant_code' => 'HDS',
            'merchant_urls' => [
                'success' => route('checkout.success'),
                'cancel' => route('checkout.cancel'),
                'failure' => route('checkout.error', ['order_id' => $order->id]),
            ],
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.tabby.ai/api/v2/checkout',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.env('TABBY_KEY'),
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return response()->json(['error' => $error]);
        }

        $result = json_decode($response, true);

        return $result['configuration']['available_products']['installments'][0]['web_url']
            ?? response()->json(['error' => $result['error']['message'] ?? 'Unknown error']);
    }

    protected function sendAdminOrderEmail(string $template, Order $order, ?string $subject = null, ?string $orderLink = null, string $recipientType = 'admin'): void
    {
        try {
            $settings = Setting::pluck('value', 'key');
            $adminEmail = $settings->get('admin_email') ?? 'admin@desertsafaricompany.com';
            $user = auth()->user();
            $email = $recipientType === 'admin'
                ? ($settings->get('admin_email') ?? 'admin@desertsafaricompany.com')
                : $user->email;
            $cart = Session::get('cart', []);

            $order = Order::findOrFail($order->id);
            $orderRequestData = json_decode($order->request_data);
            $headerLogo = $settings->get('header_logo') ?? asset('admin/assets/images/placeholder-logo.png');

            $data = [
                'order_id' => $order->id,
                'customer_name' => $user->full_name ?? '',
                'customer_email' => $user->email ?? '',
                'customer_phone' => $orderRequestData->phone_dial_code.$orderRequestData->phone_number,
                'payment_type' => $order->payment_type,
                'cart' => $cart ?? [],
                'total' => $cart['total_price'] ?? 0,
                'tours' => $cart['tours'] ?? [],
                'logo' => asset($headerLogo),
                'order_link' => $orderLink ?? '',
            ];

            $finalSubject = $subject.' - '.env('MAIL_FROM_NAME');
            Mail::send($template, ['data' => $data], function ($message) use ($email, $finalSubject) {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message
                    ->to($email)
                    ->subject($finalSubject);
            });
        } catch (\Throwable $e) {
            \Log::error('Failed to send admin order email: '.$e->getMessage());
        }
    }

    /**
     * ---------------- PAYPAL ----------------
     */
    private function handlePaypal(Request $request, Order $order)
    {
        // TODO: implement Paypal checkout
    }

    /**
     * ---------------- POSTPAY ----------------
     */
    private function handlePostpay(Request $request, Order $order)
    {
        // TODO: implement Postpay checkout
    }

    /**
     * ---------------- TAMARA ----------------
     */
    private function handleTamara(Request $request, Order $order)
    {
        // TODO: implement Tamara checkout
    }

    /**
     * ---------------- COD ----------------
     */
    private function handleCOD(Order $order)
    {
        $order->update(['payment_status' => 'pending']);
        $cart = Session::get('cart');
        $this->saveAppliedUserCoupons($cart, $order);
        $this->sendAdminOrderEmail('emails.customer-order-success', $order, 'Your Order is Confirmed', route('locations.country', 'ae'), 'user');
        Session::forget('cart');

        return view('frontend.tour.checkout.confirmed')
            ->with('title', 'Order Confirmed!');
    }

    protected function saveAppliedUserCoupons(array $cart, Order $order): void
    {
        if (empty($cart['applied_coupons'])) {
            return;
        }

        foreach ($cart['applied_coupons'] as $coupon) {
            CouponUser::updateOrCreate(
                [
                    'coupon_id' => $coupon['coupon'],
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                ],
                ['discount_applied_amount' => $coupon['amount']]
            );
        }

        if (! empty($cart['applied_coupons'])) {
            foreach ($cart['applied_coupons'] as $coupon) {
                if (! empty($coupon['is_first_order_coupon'])) {
                    auth()->user()->update(['has_used_first_order_coupon' => true]);
                }
            }
        }
    }

    public function applyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('status', 'active')
            ->first();

        if (! $coupon) {
            return redirect()
                ->back()
                ->with('notify_error', 'Invalid coupon code.')
                ->withErrors(['code' => 'Invalid coupon code.'])
                ->withInput();
        }

        if ($coupon->expiry_date && \Carbon\Carbon::parse($coupon->expiry_date)->isPast()) {
            return redirect()
                ->back()
                ->with('notify_error', 'This coupon has expired.')
                ->withErrors(['code' => 'This coupon has expired.'])
                ->withInput();
        }

        $cart = Session::get('cart', []);
        if (empty($cart) || ! isset($cart['total_price'])) {
            return redirect()
                ->back()
                ->with('notify_error', 'Cart is empty.');
        }

        if (isset($cart['applied_coupons']) && in_array($coupon->id, array_column($cart['applied_coupons'], 'coupon'))) {
            return redirect()
                ->back()
                ->with('notify_error', 'You have already used this coupon.')
                ->withErrors(['code' => 'You have already used this coupon.'])
                ->withInput();
        }

        $totalAmount = $cart['total_price'];

        if ($coupon->minimum_order_amount && $totalAmount < $coupon->minimum_order_amount) {
            return redirect()
                ->back()
                ->with(
                    'notify_error',
                    'Your total cart amount should be at least '.formatPrice($coupon->minimum_order_amount).' to apply this coupon.'
                )
                ->withErrors([
                    'code' => 'Your total cart amount should be at least '.formatPrice($coupon->minimum_order_amount).' to apply this coupon.',
                ])
                ->withInput();
        }

        $discountAmount = 0;

        if ($coupon->discount_type === 'fixed') {
            $discountAmount = $coupon->amount;
        } elseif ($coupon->discount_type === 'percentage') {
            $discountAmount = ($totalAmount * $coupon->amount) / 100;
        }

        $discountAmount = min($discountAmount, $totalAmount);

        $cart['total_price'] = $totalAmount - $discountAmount;

        $cart['applied_coupons'][] = [
            'coupon' => $coupon->id,
            'code' => $coupon->code,
            'amount' => $discountAmount,
            'type' => $coupon->discount_type,
        ];

        Session::put('cart', $cart);

        return redirect()
            ->back()
            ->with('notify_success', 'Coupon applied successfully!');
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        $this->sendAdminOrderEmail('emails.admin-order-success', $order, 'New Order Paid', route('locations.country', 'ae'), 'admin');
        $this->sendAdminOrderEmail('emails.customer-order-success', $order, 'Your Order is Confirmed', route('locations.country', 'ae'), 'user');
        $cart = Session::get('cart');

        $this->saveAppliedUserCoupons($cart, $order);

        Session::forget('cart');

        return view('frontend.tour.checkout.success')
            ->with('title', 'Payment successful!');
    }

    public function cancel(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'cancelled',
            'payment_date' => now(),
        ]);

        $this->sendAdminOrderEmail('emails.admin-order-payment-cancelled', $order, 'Payment Cancelled', route('locations.country', 'ae'), 'admin');

        return view('frontend.tour.checkout.cancel')
            ->with('title', 'Payment cancelled');
    }

    public function error(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'failed',
            'payment_date' => now(),
        ]);

        $this->sendAdminOrderEmail(
            'emails.admin-order-payment-failed',
            $order,
            'Payment Failed',
            route('locations.country', 'ae'),
            'admin'
        );

        return view('frontend.tour.checkout.error')
            ->with('title', 'Payment failed due to an error');
    }
}
