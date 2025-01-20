<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (! empty($cart)) {
            $tours = Tour::where('status', 'publish')->get();
            $data = compact('tours', 'cart');

            return view('frontend.tour.checkout')
                ->with('title', 'Checkout')->with($data);
        }

        return redirect()->back()->with('notify_error', 'Your cart is empty.');
    }

    public function store(Request $request)
    {

        $order = Order::create([
            'user_id' => Auth::id(),
            'request_data' => json_encode($request->order),
            'cart_data' => json_encode(Session::get('cart', [])),
            'payment_type' => $request->payment_type,
            'payment_status' => 'pending',
            'total_amount' => $request->total_amount,
        ]);

        if ($request->payment_type === 'stripe') {
            $stripeSession = $this->createStripeSession($request, $order);
            Order::where('id', $order->id)->update([
                'stripe_session_id' => $stripeSession->id,
            ]);

            return redirect($stripeSession->url);
        } elseif ($request->payment_type === 'postpay') {
            $postPayRedirectUrl = $this->createPostPaySession($request, $order);

            return redirect($postPayRedirectUrl);
        }
    }

    private function createStripeSession(Request $request, Order $order)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $tourTitles = $request->input('tour.title');
        $tourPrices = $request->input('tour.total_price');

        $lineItems = [];
        foreach ($tourTitles as $index => $title) {
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
    }

    private function createPostPaySession(Request $request, Order $order)
    {
        $dt = new \DateTime;
        $dt->setTimeZone(new \DateTimeZone('UTC'));

        $customer = [
            'id' => (string) $order->id,
            'email' => Auth::user()->email,
            'first_name' => Auth::user()->first_name,
            'last_name' => Auth::user()->last_name,
            'date_joined' => Auth::user()->created_at->format('Y-m-d\TH:i:s.u'),
        ];
        $tourTitles = $request->input('tour.title', []);
        $tourPrices = $request->input('tour.total_price', []);

        $items = [];
        foreach ($tourTitles as $i => $title) {
            $items[] = [
                'name' => $title,
                'price' => $tourPrices[$i] ?? 0,
                'quantity' => 1,
            ];
        }

        $payload = [
            'order_id' => (string) $order->id,
            'total_amount' => $request->total_amount,
            'tax_amount' => $request->tax_amount ?? 0,
            'currency' => env('APP_CURRENCY'),
            'customer' => $customer,
            'items' => $items,
            'merchant' => [
                'confirmation_url' => route('checkout.success', ['order_id' => $order->id]),
                'cancel_url' => route('checkout.cancel', ['order_id' => $order->id]),
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.postpay.io/checkouts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic '.env('POSTPAY_AUTH_KEY'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('Failed to connect to PostPay API: '.$error);
        }

        $response = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from PostPay API: '.json_last_error_msg());
        }

        if (! empty($response['error'])) {
            throw new \Exception($response['error']['message']);
        }

        if (empty($response['redirect_url'])) {
            throw new \Exception('Redirect URL is missing in PostPay API response');
        }

        return $response['redirect_url'];
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        Session::forget('cart');

        return view('frontend.tour.success')
            ->with('title', 'Payment successful!');
    }

    public function cancel(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'failed',
            'payment_date' => now(),
        ]);

        Session::forget('cart');

        return view('frontend.tour.cancel')
            ->with('title', 'Payment failed');
    }
}
