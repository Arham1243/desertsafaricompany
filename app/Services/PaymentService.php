<?php

namespace App\Services;

use App\Models\CouponUser;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class PaymentService
{
    public function processPayment(Request $request, Order $order)
    {
        return match ($request->payment_type) {
            'pointCheckout' => $this->handlePointCheckout($request, $order),
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

            $lineItems = [];

            foreach ($cart['tours'] as $tour) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => env('APP_CURRENCY'),
                        'product_data' => [
                            'name' => $tour['tour_title'],
                            'description' => 'Start Date: '.Carbon::parse($tour['start_date'])->format('d M Y').
                                " | Total: {$tour['total_no_of_people']}",
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

    private function createTabbySession(Request $request, Order $order)
    {
        $custom_order_id = 'ORDER'.$order->id;
        $total_with_taxes = number_format($order->total_amount, 2, '.', '');
        $tax = number_format($order->total_amount * 0.05, 2, '.', ''); // adjust tax logic
        $final_total = number_format($order->total_amount - $tax, 2, '.', '');

        $name = $order->first_name.' '.$order->last_name;
        $email = $order->email;
        $phone = '+'.$order->phone_dial_code.$order->phone_number;

        $items = [
            [
                'title' => 'Tour Booking',
                'description' => 'Booking ID '.$custom_order_id,
                'quantity' => 1,
                'unit_price' => (float) $final_total,
                'category' => 'tour',
            ],
        ];

        $payload = [
            'payment' => [
                'amount' => $total_with_taxes,
                'currency' => 'AED',
                'description' => env('APP_NAME'),
                'buyer' => [
                    'phone' => $phone,
                    'email' => $email,
                    'name' => $name,
                ],
                'shipping_address' => [
                    'city' => $order->city ?? 'Dubai',
                    'address' => $order->address ?? 'Dubai Marina',
                    'zip' => '00000',
                ],
                'order' => [
                    'tax_amount' => $tax,
                    'shipping_amount' => '0.00',
                    'discount_amount' => '0.00',
                    'updated_at' => now()->toIso8601String(),
                    'reference_id' => $custom_order_id,
                    'items' => $items,
                ],
                'buyer_history' => [
                    'registered_since' => now()->subYear()->toIso8601String(),
                    'loyalty_level' => 0,
                    'wishlist_count' => 0,
                    'is_social_networks_connected' => true,
                    'is_phone_number_verified' => true,
                    'is_email_verified' => true,
                ],
                'meta' => [
                    'order_id' => $custom_order_id,
                    'customer' => $order->user_id,
                ],
            ],
            'lang' => 'en',
            'merchant_code' => 'HDS',
            'merchant_urls' => [
                'success' => route('checkout.tabby.success', ['order_id' => $order->id]),
                'cancel' => route('checkout.tabby.cancel', ['order_id' => $order->id]),
                'failure' => route('checkout.tabby.failure', ['order_id' => $order->id]),
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.tabby.ai/api/v2/checkout');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.env('TABBY_SECRET_KEY'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (! empty($result['configuration']['available_products']['installments'][0]['web_url'])) {
            // store payment session id or key
            $order->update([
                'tabby_session_id' => $result['id'] ?? null,
                'payment_type' => 'tabby',
            ]);

            return redirect()->away($result['configuration']['available_products']['installments'][0]['web_url']);
        }

        throw new \Exception('Tabby checkout failed: '.$response);
    }

    /**
     * ---------------- PointCheckout ----------------
     */
    private function handlePointCheckout(Request $request, Order $order)
    {
        $checkoutKey = $this->createPointCheckoutSession($request, $order);

        if (! $checkoutKey) {
            return redirect()
                ->route('checkout.error', ['order_id' => $order->id])
                ->with('notify_error', 'Failed to create PointCheckout session');
        }

        $order->update(['pointcheckout_key' => $checkoutKey]);

        $checkoutUrl = "https://www.pointcheckout.com/checkout/{$checkoutKey}";

        return redirect($checkoutUrl);
    }

    private function createPointCheckoutSession(Request $request, Order $order)
    {
        $orderData = $request->input('order', []);
        $custom_order_id = $order->id;

        $firstName = $orderData['first_name'] ?? 'Guest';
        $lastName = $orderData['last_name'] ?? '';
        $name = trim($firstName.' '.$lastName);
        $email = $orderData['email'] ?? '';
        $phone = '+'.($orderData['phone_dial_code'] ?? '').($orderData['phone_number'] ?? '');
        $address = $orderData['address'] ?? '';
        $city = $orderData['city'] ?? '';
        $country = $orderData['country'] ?? '';

        $total_with_taxes = number_format($order->total_amount, 2, '.', '');
        $final_total = number_format($order->total_amount, 2, '.', ''); // adjust if you track discount vs subtotal
        $tax = '0.00'; // pull from order if available

        $cart = json_decode($order->cart_data, true);
        $lineItems = [];
        foreach ($cart['tours'] as $tour) {
            $lineItems[] = [
                'name' => $tour['tour_title'] ?? 'Tour',
                'quantity' => $tour['total_no_of_people'] ?? 1,
                'unitPrice' => number_format($tour['total_price'], 2, '.', ''),
                'totalAmount' => number_format($tour['total_price'], 2, '.', ''),
            ];
        }
        $itemsJson = json_encode(array_values($lineItems));

        $payload = [
            'transactionId' => (string) $custom_order_id,
            'currency' => env('APP_CURRENCY', 'AED'),
            'amount' => $total_with_taxes,
            'subtotal' => $final_total,
            'shipping' => '0.0',
            'tax' => $tax,
            'discount' => '0.0',
            'resultUrl' => route('checkout.pointcheckout.response', ['order_id' => $order->id, 'payment_type' => 'pointCheckout']),
            'defaultPaymentMethod' => 'CARD',
            'paymentMethods' => ['POINTCHECKOUT', 'CARD'],
            'deviceReference' => 'WEB-APP',
            'generateQR' => false,
            'expiryInMinutes' => 1440,
            'items' => json_decode($itemsJson),
            'customer' => [
                'id' => (string) $custom_order_id,
                'firstName' => $firstName,
                'email' => $email,
                'phone' => $phone,
                'billingAddress' => [[
                    'name' => $name,
                    'address1' => $address,
                    'city' => $city,
                    'country' => $country,
                ]],
                'shippingAddress' => [[
                    'name' => $name,
                    'address1' => $address,
                    'city' => $city,
                    'country' => $country,
                ]],
            ],
            'sendCustomerEmail' => false,
            'sendCustomerSms' => false,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pointcheckout.com/mer/v1.2/checkouts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-PointCheckout-Api-Key: '.env('POINTCHECKOUT_API_KEY'),
            'X-PointCheckout-Api-Secret: '.env('POINTCHECKOUT_API_SECRET'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        curl_close($ch);

        $result_p = json_decode($result, true);

        return $result_p['reference'] ?? null;
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
}
