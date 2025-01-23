<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
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
        if ($cart['total_price'] === 0) {
            Session::forget('cart');

            return redirect()->route('cart.index')->with('notify_error', 'Your cart is empty.');
        }

        if (! empty($cart)) {
            $tours = Tour::where('status', 'publish')->get();
            $data = compact('tours', 'cart');

            return view('frontend.tour.checkout.index')
                ->with('title', 'Checkout')->with($data);
        }
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
            $response = $this->createStripeSession($request, $order);
            $payment_error = 'Failed to create Stripe session. Please try again.';
            if (! $response || ! isset($response->id)) {
                return redirect()->route('checkout.error', ['order_id' => $order->id])
                    ->with('notify_error', $payment_error)
                    ->with('error_message', $payment_error); // Pass error to the error page
            }
            Order::where('id', $order->id)->update([
                'stripe_session_id' => $response->id,
            ]);

            return redirect($response->url);
        } elseif ($request->payment_type === 'postpay') {
            $response = $this->createPostPaySession($request, $order);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $response = $response->getData(true);
            }

            if (isset($response['error'])) {
                return redirect()->route('checkout.error', ['order_id' => $order->id])
                    ->with('notify_error', $response['error'])
                    ->with('error_message', $response['error']); // Pass error to the error page
            }

            return redirect($response['redirect_url']);
        } elseif ($request->payment_type === 'tabby') {
            $response = $this->createTabbySession($request, $order);

            if (isset($response['error'])) {
                return redirect()->route('checkout.error', ['order_id' => $order->id])
                    ->with('notify_error', $response['error'])
                    ->with('error_message', $response['error']); // Pass error to the error page
            }

            return redirect($response);
        }
    }

    private function createStripeSession(Request $request, Order $order)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $tourTitles = $request->input('tour.title');
            $tourPrices = $request->input('tour.total_price');

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

    private function createPostPaySession(Request $request, Order $order)
    {
        $dt = new \DateTime;
        $dt->setTimeZone(new \DateTimeZone('UTC'));

        $customer = [
            'id' => (string) $order->id,
            'email' => Auth::user()->email,
            'first_name' => Auth::user()->full_name,
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
            'tax_amount' => 0,
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

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return response()->json(['error' => 'Failed to connect to PostPay API: '.$error], 500);
        }

        $response = json_decode($result, true);
        curl_close($ch);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON response from PostPay API: '.json_last_error_msg()], 500);
        }

        if (! empty($response['error'])) {
            return response()->json(['error' => $response['error']['message']], 500);
        }

        if (empty($response['redirect_url'])) {
            return response()->json(['error' => 'Redirect URL is missing in PostPay API response'], 500);
        }

        return response()->json(['redirect_url' => $response['redirect_url']]);
    }

    private function createTabbySession(Request $request, Order $order)
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
                'amount' => $request->total_amount,
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
                    'tax_amount' => $request->total_amount,
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
}
