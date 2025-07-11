<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Tour;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $data = compact('tours', 'cart');

            return view('frontend.tour.checkout.index')
                ->with('title', 'Checkout')
                ->with($data);
        }

        return redirect()->route('index')->with('notify_error', 'Your cart is empty.');
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        $totalAmount = isset($cart['total_price']) ? $cart['total_price'] : 0;
        if (! $totalAmount || $totalAmount < 0) {
            return redirect()->route('index')->with('notify_error', 'Your cart is empty.');
        }
        $order = Order::create([
            'user_id' => Auth::id(),
            'request_data' => json_encode($request->order),
            'cart_data' => json_encode($cart),
            'payment_type' => $request->payment_type,
            'payment_status' => 'pending',
            'total_amount' => $totalAmount,
        ]);

        if ($request->payment_type === 'stripe') {
            $response = $this->createStripeSession($request, $order, $totalAmount);
            $payment_error = 'Failed to create Stripe session. Please try again.';
            if (! $response || ! isset($response->id)) {
                return redirect()
                    ->route('checkout.error', ['order_id' => $order->id])
                    ->with('notify_error', $payment_error)
                    ->with('error_message', $payment_error);
            }
            Order::where('id', $order->id)->update([
                'stripe_session_id' => $response->id,
            ]);

            return redirect($response->url);
        } elseif ($request->payment_type === 'tabby') {
            $response = $this->createTabbySession($request, $order, $totalAmount);

            if (isset($response['error'])) {
                return redirect()
                    ->route('checkout.error', ['order_id' => $order->id])
                    ->with('notify_error', $response['error'])
                    ->with('error_message', $response['error']);
            }

            return redirect($response);
        } elseif ($request->payment_type === 'cod') {
            Order::where('id', $order->id)->update([
                'payment_status' => 'pending',
            ]);

            Session::forget('cart');

            return view('frontend.tour.checkout.confirmed')
                ->with('title', 'Order Confirmed!');
        }
    }

    private function createStripeSession(Request $request, Order $order)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $cart = Session::get('cart', []);
            $tourTitles = [];
            $tourPrices = [];

            foreach ($cart['tours'] as $tourId => $tourData) {
                $tourTitles[$tourId] = Tour::where('id', $tourId)->first()->title;
                $tourPrices[$tourId] = $tourData['data']['total_price'] ?? 0;
            }

            if (empty($tourTitles) || empty($tourPrices)) {
                return response()->json(['error' => 'Tour details are missing. Please provide valid tour titles and prices.']);
            }

            $lineItems = [];
            foreach ($tourTitles as $index => $title) {
                if (! isset($tourPrices[$index]) || ! is_numeric($tourPrices[$index])) {
                    return response()->json(['error' => 'Invalid price provided for one or more tours.']);
                }

                $lineItems[] = [
                    'price_data' => [
                        'currency' => env('APP_CURRENCY'),
                        'product_data' => [
                            'name' => $title,
                        ],
                        'unit_amount' => $tourPrices[$index] * 100,
                    ],
                    'quantity' => 1,
                ];
            }

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success', [
                    'order_id' => $order->id,
                ]),
                'cancel_url' => route('checkout.cancel', ['order_id' => $order->id]),
                'client_reference_id' => $order->id,
            ]);

            return $session;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => 'Stripe API error: '.$e->getMessage()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: '.$e->getMessage()]);
        }
    }

    private function createTabbySession(Request $request, Order $order, $totalAmount)
    {
        $tourTitles = $request->input('tour.title');
        $tourPrices = $request->input('tour.total_price');
        if (empty($tourTitles) || empty($tourPrices)) {
            return response()->json(['error' => 'Tour titles and prices are required.']);
        }

        $tabbyData = [];
        foreach ($tourTitles as $i => $title) {
            if (! isset($tourPrices[$i])) {
                return response()->json(['error' => 'Each tour must have a corresponding price.']);
            }
            $tabbyData[] = [
                'title' => $title,
                'unit_price' => $tourPrices[$i],
                'quantity' => 1,
            ];
        }

        $dt = new DateTime;
        $dt->setTimeZone(new DateTimeZone('UTC'));

        $curl = curl_init();

        $postData = [
            'payment' => [
                'amount' => $totalAmount,
                'currency' => env('APP_CURRENCY'),
                'description' => env('APP_NAME'),
                'buyer' => [
                    'name' => Auth::user()->full_name,
                    'email' => Auth::user()->email,
                ],
                'shipping_address' => [
                    'country' => $request['order']['country'],
                    'address' => $request['order']['address'],
                ],
                'order' => [
                    'tax_amount' => $totalAmount,
                    'shipping_amount' => '0.00',
                    'discount_amount' => '0.00',
                    'updated_at' => $order->created_at->toIso8601String(),
                    'reference_id' => $order->id,
                    'items' => $tabbyData,
                ],
                'buyer_history' => [
                    'registered_since' => Auth::user()->created_at->toIso8601String(),
                ],
                'meta' => [
                    'order_id' => $order->id,
                    'customer' => $order->id,
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

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.tabby.ai/api/v2/checkout',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.env('TABBY_KEY'),
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            $error_message = curl_error($curl);

            return response()->json(['error' => 'Curl error: '.$error_message]);
        }

        $result = json_decode($response, true);

        if (isset($result['id'])) {
            $redirectUrl = $result['configuration']['available_products']['installments'][0]['web_url'] ?? null;

            if ($redirectUrl) {
                return $redirectUrl;
            } else {
                return response()->json(['error' => 'Installments not available.']);
            }
        } else {
            $error_message = $result['error']['message'] ?? 'Unknown error';

            return response()->json(['error' => $error_message]);
        }
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        $cart = Session::get('cart');

        if (! empty($cart['applied_coupons'])) {
            foreach ($cart['applied_coupons'] as $coupon) {
                CouponUser::updateOrCreate([
                    'coupon_id' => $coupon['coupon'],
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                ], [
                    'discount_applied_amount' => $coupon['amount'],
                ]);
            }
        }

        Session::forget('cart');

        return view('frontend.tour.checkout.success')
            ->with('title', 'Payment successful!');
    }

    public function cancel(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'failed',
            'payment_date' => now(),
        ]);

        return view('frontend.tour.checkout.cancel')
            ->with('title', 'Payment failed');
    }

    public function error(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'error',
            'payment_date' => now(),
        ]);

        return view('frontend.tour.checkout.error')
            ->with('title', 'Something went wrong during the process');
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
        $totalSubtotalAmount = $cart['subtotal'];

        if ($coupon->minimum_order_amount && $totalSubtotalAmount < $coupon->minimum_order_amount) {
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
        $cart['subtotal'] = $totalSubtotalAmount - $discountAmount;

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
}
