<?php

namespace App\Services;

use App\Models\CouponUser;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class PaymentService
{
    public function processPayment(Request $request, Order $order)
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
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $cart = json_decode($order->cart_data, true);

            if (empty($cart['total_price'])) {
                return response()->json(['error' => 'Cart total missing']);
            }

            $totalAmount = round($cart['total_price'], 2) * 100;
            $lineItems = [];

            foreach ($cart['tours'] as $tourId => $tour) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => env('APP_CURRENCY'),
                        'product_data' => [
                            'name' => $tour['tour_title'],
                            'description' => "Start Date: {$tour['start_date']} | Total: {$tour['total_no_of_people']}",
                        ],
                        'unit_amount' => round($tour['total_price'], 2) * 100,
                    ],
                    'quantity' => 1,
                ];
            }

            return StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success', [
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type,
                ]),
                'cancel_url' => route('checkout.cancel', [
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type,
                ]),
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

    public function sendAdminOrderEmail(string $template, Order $order, ?string $subject = null, ?string $orderLink = null, string $recipientType = 'admin'): void
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
                'settings' => $settings,
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
        $cart = json_decode($order->cart_data, true);
        $this->saveAppliedUserCoupons($cart, $order);
        $this->sendAdminOrderEmail('emails.customer-order-success', $order, 'Your Order is Confirmed', route('user.bookings.edit', $order->id), 'user');
        Session::forget('cart');

        return view('frontend.tour.checkout.confirmed')
            ->with('title', 'Order Confirmed!');
    }

    public function saveAppliedUserCoupons(array $cart, Order $order): void
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
}
