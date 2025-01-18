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
        $tours = Tour::where('status', 'publish')->get();
        $data = compact('tours', 'cart');

        return view('frontend.tour.checkout')
            ->with('title', 'Checkout')->with($data);
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
