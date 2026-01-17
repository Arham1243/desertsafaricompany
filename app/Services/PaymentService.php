<?php

namespace App\Services;

use App\Models\CouponUser;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
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

    private function calculateAmountToCharge(array $cart, Order $order): float
    {
        // If advance payment, charge only that
        if (!empty($order->advance_amount) && $order->payment_type === 'stripe') {
            return (float) $order->advance_amount;
        }

        return $cart['total_price'] ?? 0;
    }

    private function createStripeSession(Request $request, Order $order)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $cart = json_decode($order->cart_data, true);

            $lineItems = [];
            $amountToCharge = $this->calculateAmountToCharge($cart, $order);

            foreach ($cart['tours'] as $tour) {
                $tourProportion = $tour['total_price'] / $cart['total_price'];
                $tourAdvanceAmount = round($amountToCharge * $tourProportion, 2);

                $lineItems[] = [
                    'price_data' => [
                        'currency' => env('APP_CURRENCY'),
                        'product_data' => [
                            'name' => $tour['tour_title'],
                            'description' => 'Start Date: ' . Carbon::parse($tour['start_date'])->format('d M Y')
                                . " | Qty: {$tour['total_no_of_people']}",
                        ],
                        'unit_amount' => round($tourAdvanceAmount, 2) * 100,
                    ],
                    'quantity' => 1,
                ];
            }

            return StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.stripe.success', [
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
        $session = $this->createTabbySession($request, $order);

        if (! isset($session['id']) || empty($session['url'])) {
            return redirect()
                ->route('checkout.error', ['order_id' => $order->id])
                ->with('notify_error', 'Failed to create Tabby session');
        }

        $order->update([
            'tabby_session_id' => $session['id'],
            'payment_type' => 'tabby',
        ]);

        return redirect()->away($session['url']);
    }

    private function createTabbySession(Request $request, Order $order)
    {
        $customOrderId = 'ORDER' . $order->id;
        $cart = json_decode($order->cart_data, true) ?? [];
        $amountToCharge = $this->calculateAmountToCharge($cart, $order);
        $tax = round($amountToCharge * 0.05, 2);
        $finalTotal = round($amountToCharge - $tax, 2);

        $items = [[
            'title' => 'Tour Booking',
            'description' => 'Booking ID ' . $customOrderId,
            'quantity' => 1,
            'unit_price' => (float) $finalTotal,
            'category' => 'tour',
        ]];

        $payload = [
            'payment' => [
                'amount' => (float) $amountToCharge,
                'currency' => 'AED',
                'description' => env('APP_NAME'),
                'buyer' => [
                    'phone' => '+' . $order->phone_dial_code . $order->phone_number,
                    'email' => $order->email,
                    'name' => $order->first_name . ' ' . $order->last_name,
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
                    'reference_id' => $customOrderId,
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
                    'order_id' => $customOrderId,
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

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.tabby.ai/api/v2/checkout');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . env('TABBY_SECRET_KEY'),
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);
            if ($response === false) {
                throw new \Exception(curl_error($ch));
            }
            curl_close($ch);

            $result = json_decode($response, true);

            $url = $result['configuration']['available_products']['installments'][0]['web_url'] ?? null;

            return [
                'id' => $result['id'] ?? null,
                'url' => $url,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Tabby checkout failed: ' . $e->getMessage());
        }
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
        $name = trim($firstName . ' ' . $lastName);
        $email = $orderData['email'] ?? '';
        $phone = '+' . ($orderData['phone_dial_code'] ?? '') . ($orderData['phone_number'] ?? '');
        $address = $orderData['address'] ?? '';
        $city = $orderData['city'] ?? '';
        $country = $orderData['country'] ?? '';

        // Decode cart
        $cart = json_decode($order->cart_data, true) ?? [];

        // Calculate amount to charge based on advance payment
        $amountToCharge = $this->calculateAmountToCharge($cart, $order);

        // Optional: calculate tax if needed
        $tax = round($amountToCharge * 0.05, 2); // example 5% tax
        $finalTotal = round($amountToCharge - $tax, 2);

        // Build line items based on remaining amount
        $lineItems = [];
        foreach ($cart['tours'] as $tour) {
            $proportion = $tour['total_price'] / ($cart['total_price'] ?? 1);
            $unitPrice = round($finalTotal * $proportion, 2);

            $lineItems[] = [
                'name' => $tour['tour_title'] ?? 'Tour',
                'quantity' => $tour['total_no_of_people'] ?? 1,
                'unitPrice' => number_format($unitPrice, 2, '.', ''),
                'totalAmount' => number_format($unitPrice, 2, '.', ''),
            ];
        }
        $itemsJson = json_encode(array_values($lineItems));

        $payload = [
            'transactionId' => (string) $custom_order_id,
            'currency' => env('APP_CURRENCY', 'AED'),
            'amount' => number_format($amountToCharge, 2, '.', ''),
            'subtotal' => number_format($finalTotal, 2, '.', ''),
            'shipping' => '0.0',
            'tax' => number_format($tax, 2, '.', ''),
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
            'X-PointCheckout-Api-Key: ' . env('POINTCHECKOUT_API_KEY'),
            'X-PointCheckout-Api-Secret: ' . env('POINTCHECKOUT_API_SECRET'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if ($response === false) {
            dd(['curl_error' => curl_error($ch)]);
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (!($result['success'] ?? false)) {
            dd($result['error'] ?? 'Unknown error');
        }

        return $result['reference'] ?? null;
    }

    /**
     * ---------------- PAYPAL ----------------
     */
    private function handlePayPal(Request $request, Order $order)
    {
        // Store the order ID so your custom PayPal page can load it
        $order->update(['paypal_order_id' => $order->id, 'payment_type' => 'paypal']);

        // Redirect to your custom PayPal page where buttons will be rendered
        return redirect()->route('checkout.paypal.custom', [
            'order_id' => $order->id,
        ]);
    }

    private function createPayPalOrder(Request $request, Order $order)
    {
        try {
            $environment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET_KEY'));
            $client = new PayPalHttpClient($environment);

            $cart = json_decode($order->cart_data, true);
            $items = [];

            foreach ($cart['tours'] as $tour) {
                $items[] = [
                    'name' => $tour['tour_title'],
                    'description' => 'Start Date: ' . \Carbon\Carbon::parse($tour['start_date'])->format('d M Y')
                        . " | Total: {$tour['total_no_of_people']}",
                    'unit_amount' => [
                        'currency_code' => env('APP_CURRENCY'),
                        'value' => number_format($tour['total_price'], 2, '.', ''),
                    ],
                    'quantity' => '1',
                ];
            }

            $orderRequest = new OrdersCreateRequest;
            $orderRequest->prefer('return=representation');
            $orderRequest->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $order->id,
                        'amount' => [
                            'currency_code' => env('APP_CURRENCY'),
                            'value' => number_format($cart['total_price'], 2, '.', ''),
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => env('APP_CURRENCY'),
                                    'value' => number_format($cart['total_price'], 2, '.', ''),
                                ],
                            ],
                        ],
                        'items' => $items,
                    ],
                ],
                'application_context' => [
                    'cancel_url' => route('checkout.cancel', ['order_id' => $order->id, 'payment_type' => $request->payment_type]),
                    'return_url' => route('checkout.paypal.success', ['order_id' => $order->id, 'payment_type' => $request->payment_type]),
                ],
            ];

            $response = $client->execute($orderRequest);

            $approveLink = collect($response->result->links)
                ->firstWhere('rel', 'approve')
                ->href ?? null;

            return $approveLink ? ['approve_link' => $approveLink, 'order_id' => $response->result->id] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * ---------------- POSTPAY ----------------
     */
    private function handlePostpay(Request $request, Order $order)
    {
        $session = $this->createPostpaySession($request, $order);

        if (! isset($session['id']) || empty($session['checkout_url'])) {
            return redirect()
                ->route('checkout.error', ['order_id' => $order->id])
                ->with('notify_error', 'Failed to create Postpay session');
        }

        $order->update([
            'postpay_session_id' => $session['id'],
            'payment_type' => 'postpay',
        ]);

        return redirect()->away($session['checkout_url']);
    }

    private function createPostpaySession(Request $request, Order $order)
    {
        $custom_order_id = $order->id;
        $cart = json_decode($order->cart_data, true);

        $total_with_taxes = number_format($cart['total_price'], 2, '.', '');
        $tax = number_format($cart['total_price'] * 0.05, 2, '.', '');

        $name = $order->first_name;
        $email = $order->email;
        $dt = now()->timezone('UTC');

        $items = [];
        foreach ($cart['tours'] as $tour) {
            $items[] = [
                'name' => $tour['tour_title'],
                'reference' => $tour['tour_id'] ?? $custom_order_id,
                'unit_price' => (int) round($tour['total_price']),
                'qty' => (int) $tour['total_no_of_people'],
            ];
        }

        $payload = [
            'order_id' => $custom_order_id,
            'total_amount' => (float) $total_with_taxes,
            'tax_amount' => (float) $tax,
            'currency' => 'AED',
            'customer' => [
                'id' => $custom_order_id,
                'email' => $email,
                'first_name' => $name,
                'last_name' => '',
                'gender' => 'male',
                'account' => 'guest',
                'date_of_birth' => '1999-09-14',
                'date_joined' => $dt->format('Y-m-d\TH:i:s.u'),
            ],
            'items' => $items,
            'merchant' => [
                'confirmation_url' => route('checkout.postpay.success', [
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type,
                ]),
                'cancel_url' => route('checkout.cancel', [
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type,
                ]),
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.postpay.io/checkouts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . env('POSTPAY_SECRET_KEY'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        if ($response === false) {
            dd(curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($response, true);

        return [
            'id' => $result['id'] ?? null,
            'checkout_url' => $result['checkout_url'] ?? null,
        ];
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
        $order->update(['payment_status' => 'pending', 'status' => 'confirmed']);
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
                'customer_phone' => $orderRequestData->phone_dial_code . $orderRequestData->phone_number,
                'payment_type' => $order->payment_type,
                'advance_amount' => $order->advance_amount,
                'cart' => $cart ?? [],
                'total' => $cart['total_price'] ?? 0,
                'tours' => $cart['tours'] ?? [],
                'logo' => asset($headerLogo),
                'order_link' => $orderLink ?? '',
            ];

            $finalSubject = $subject . ' - ' . env('MAIL_FROM_NAME');
            Mail::send($template, ['data' => $data], function ($message) use ($email, $finalSubject) {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message
                    ->to($email)
                    ->subject($finalSubject);
            });
        } catch (\Throwable $e) {
            \Log::error('Failed to send admin order email: ' . $e->getMessage());
        }
    }
}
