<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Tour;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // enable_cash_pickup
        $cart = Session::get('cart', []);
        if (isset($cart['total_price']) && $cart['total_price'] === 0) {
            Session::forget('cart');

            return redirect()->route('cart.index')->with('notify_error', 'Your cart is empty.');
        }
        $hasCouponApplied = !empty($cart['applied_coupons']);

        if (! empty($cart)) {
            $tours = Tour::where('status', 'publish')->get();

            // Initialize required data arrays for checkout view
            $cartTours = [];
            $promoToursData = [];
            $toursNormalPrices = [];
            $privateTourData = [];
            $waterTourTimeSlots = [];
            $hideCashOnPickup = false;

            // Build cartTours array from cart data
            if (isset($cart['tours'])) {
                foreach ($cart['tours'] as $tourId => $tourData) {
                    $tour = $tours->where('id', $tourId)->first();
                    if ($tour) {
                        $cartTours[] = $tour;
                    }
                    if ($tour->enable_cash_pickup == 0) {
                        $hideCashOnPickup = true;
                    }
                }
            }

            $data = compact('hideCashOnPickup', 'tours', 'cart', 'cartTours', 'promoToursData', 'toursNormalPrices', 'privateTourData', 'waterTourTimeSlots', 'hasCouponApplied');

            return view('frontend.tour.checkout.index')
                ->with('title', 'Checkout')
                ->with($data);
        }

        return redirect()->route('frontend.index')->with('notify_error', 'Your cart is empty.');
    }

    public function store(Request $request, PaymentService $paymentService)
    {
        $settings = Setting::pluck('value', 'key');
        $advancePaymentPercentage = (float) ($settings->get('advance_payment_percentage', 10));
        $cashDiscountApplicable = $settings->get('cash_discount_applicable', 0);
        $canAvailDiscount = true;

        if ($request->payment_type === 'cod' && $cashDiscountApplicable == 0) {
            $canAvailDiscount = false;
        }

        $cart = Session::get('cart', []);
        if (!$canAvailDiscount) {
            $cart = $this->revertCouponDiscounts($cart);
        }

        $totalAmount = $cart['total_price'] ?? 0;

        if ($totalAmount <= 0) {
            return redirect()
                ->route('frontend.index')
                ->with('notify_error', 'Your cart is empty.');
        }

        $advanceAmount = 0;
        $remainingAmount = 0;
        if ($request->payment_type === 'advance_payment') {
            $advanceAmount = round($totalAmount * ($advancePaymentPercentage / 100), 2);
            $remainingAmount = $totalAmount - $advanceAmount;
            $request->merge([
                'payment_type' => 'stripe',
                'advance_amount' => $advanceAmount,
                'remaining_amount' => $remainingAmount,
            ]);
        }

        $order = $this->createOrder($request, $cart, $totalAmount);

        $paymentService->sendAdminOrderEmail(
            'emails.admin-pending-order',
            $order,
            'New Pending Order',
            route('admin.bookings.edit', $order->id),
            'admin'
        );

        return $paymentService->processPayment($request, $order);
    }



    /**
     * Revert all coupon discounts from the cart
     */
    private function revertCouponDiscounts(array $cart): array
    {
        // Remove applied coupons
        unset($cart['applied_coupons']);

        // Process each tour in the cart
        foreach ($cart['tours'] as $tourId => &$tourCart) {
            $originalSubtotal = 0;

            // Revert discounts in tourData items
            if (isset($tourCart['tourData']) && is_array($tourCart['tourData'])) {
                foreach ($tourCart['tourData'] as &$item) {
                    // Remove first order coupon flag
                    $item['is_first_order_coupon_applied'] = false;

                    // Revert to discounted_price (before first-order coupon)
                    if (isset($item['promo_discounted_price'])) {
                        unset($item['promo_discounted_price']);
                    }

                    // Calculate original subtotal based on item type
                    if (isset($item['source'])) {
                        $qty = $item['quantity'] ?? 0;

                        if ($item['source'] === 'promo' || $item['source'] === 'addon') {
                            // For promo items, use discounted_price (original promo price)
                            $itemPrice = floatval($item['discounted_price'] ?? 0);

                            // ✅ FIXED: Check if 'type' key exists before accessing
                            if (isset($item['type']) && $item['type'] === 'timeslot' && isset($item['selected_slots']) && is_array($item['selected_slots'])) {
                                // For timeslot items, revert slot prices
                                if (isset($item['slots']) && is_array($item['slots'])) {
                                    foreach ($item['slots'] as &$slot) {
                                        if (isset($slot['promo_discounted_price'])) {
                                            unset($slot['promo_discounted_price']);
                                        }
                                        $slot['is_first_order_coupon_applied'] = false;
                                    }
                                    unset($slot);
                                }

                                // Calculate timeslot total
                                foreach (array_slice($item['selected_slots'], 0, $qty) as $slotTime) {
                                    $slot = collect($item['slots'] ?? [])->firstWhere('time', $slotTime);
                                    if ($slot) {
                                        $originalSubtotal += floatval($slot['discounted_price'] ?? 0);
                                    }
                                }
                            } else {
                                // For simple items (or items without type)
                                $originalSubtotal += $itemPrice * $qty;
                            }
                        }
                    } else {
                        // For normal tour items
                        if (isset($item['promo_discounted_price'])) {
                            unset($item['promo_discounted_price']);
                        }
                        $qty = $item['quantity'] ?? 0;
                        $price = floatval($item['original_price'] ?? $item['price'] ?? 0);
                        $originalSubtotal += $price * $qty;
                    }
                }
                unset($item);
            }

            // Use price_without_discount if available, otherwise calculate from items
            if (isset($tourCart['price_without_discount'])) {
                $tourCart['subtotal'] = round($tourCart['price_without_discount'], 2);
            } else {
                $tourCart['subtotal'] = round($originalSubtotal, 2);
            }

            // Recalculate total_price (subtotal + service_fee + extras)
            $serviceFee = floatval($tourCart['service_fee'] ?? 0);
            $extraTotal = array_sum(array_column($tourCart['extra_prices'] ?? [], 'price'));
            $tourCart['total_price'] = round($tourCart['subtotal'] + $serviceFee + $extraTotal, 2);
        }
        unset($tourCart);

        // Recalculate cart-level totals
        $cart['subtotal'] = round(array_sum(array_column($cart['tours'], 'subtotal')), 2);
        $cart['total_price'] = round(array_sum(array_column($cart['tours'], 'total_price')), 2);

        // Use price_without_discount for cart total if available
        if (isset($cart['price_without_discount'])) {
            $cart['total_price'] = round($cart['price_without_discount'], 2);
        }

        return $cart;
    }

    protected function createOrder(Request $request, array $cart, float $totalAmount): Order
    {
        return Order::create([
            'user_id' => auth()->id() ?? null,
            'guest_email' => $request->order['email'] ?? null,
            'request_data' => json_encode($request->order),
            'request_data' => json_encode($request->order),
            'cart_data' => json_encode($cart),
            'payment_type' => $request->payment_type,
            'payment_status' => 'pending',
            'total_amount' => $totalAmount,
            'advance_amount' => $request->advance_amount ?? null,
            'remaining_amount' => $request->remaining_amount ?? null,
        ]);
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
                    'Your total cart amount should be at least AED ' . number_format($coupon->minimum_order_amount) . ' to apply this coupon.'
                )
                ->withErrors([
                    'code' => 'Your total cart amount should be at least AED ' . number_format($coupon->minimum_order_amount) . ' to apply this coupon.',
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

    public function stripeSuccess(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);
        $paymentStatus = $order->remaining_amount > 0 ? 'partial' : 'paid';

        $order->update([
            'payment_type' => $request->payment_type,
            'status' => 'confirmed',
            'payment_status' => $paymentStatus,
            'paid_amount' => $order->advance_amount ?? $order->total_amount,
            'payment_date' => now(),
        ]);

        $paymentService->sendAdminOrderEmail('emails.admin-order-success', $order, 'New Order Paid', route('admin.bookings.edit', $order->id), 'admin');
        $paymentService->sendAdminOrderEmail('emails.customer-order-success', $order, 'Your Order is Confirmed', route('user.bookings.edit', $order->id), 'user');
        $cart = json_decode($order->cart_data, true);

        $paymentService->saveAppliedUserCoupons($cart, $order);

        Session::forget('cart');

        return view('frontend.tour.checkout.success')
            ->with('title', 'Payment successful!');
    }

    public function pointCheckoutResponse(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        // PointCheckout sends back transactionId and status
        $transactionId = $request->get('transactionId');
        $status = strtolower($request->get('status', ''));

        if ($status === 'paid' || $status === 'captured') {
            $order->update([
                'payment_type' => 'pointCheckout',
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'payment_date' => now(),
            ]);

            $paymentService->sendAdminOrderEmail(
                'emails.admin-order-success',
                $order,
                'New Order Paid',
                route('admin.bookings.edit', $order->id),
                'admin'
            );

            $paymentService->sendAdminOrderEmail(
                'emails.customer-order-success',
                $order,
                'Your Order is Confirmed',
                route('user.bookings.edit', $order->id),
                'user'
            );

            $cart = json_decode($order->cart_data, true);
            $paymentService->saveAppliedUserCoupons($cart, $order);

            Session::forget('cart');

            return view('frontend.tour.checkout.success')->with('title', 'Payment successful!');
        }

        // if failed or canceled
        $order->update([
            'payment_type' => 'pointCheckout',
            'payment_status' => 'failed',
        ]);

        return redirect()
            ->route('checkout.error', ['order_id' => $order->id])
            ->with('notify_error', 'Payment failed or was cancelled');
    }

    public function tabbySuccess(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        $paymentId = $request->get('payment_id');  // Tabby sends this back
        if (! $paymentId) {
            return redirect()->route('checkout.tabby.failure', ['order_id' => $order->id]);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.tabby.ai/api/v2/payments/$paymentId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . env('TABBY_SECRET_KEY'),
            'Content-Type: application/json',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (! empty($result['status']) && $result['status'] === 'CLOSED') {
            $order->update([
                'payment_type' => 'tabby',
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'payment_date' => now(),
            ]);

            $paymentService->sendAdminOrderEmail('emails.admin-order-success', $order, 'New Order Paid', route('admin.bookings.edit', $order->id), 'admin');
            $paymentService->sendAdminOrderEmail('emails.customer-order-success', $order, 'Your Order is Confirmed', route('user.bookings.edit', $order->id), 'user');

            Session::forget('cart');

            return view('frontend.tour.checkout.success')->with('title', 'Payment successful!');
        }

        return redirect()->route('checkout.tabby.failure', ['order_id' => $order->id]);
    }

    public function tabbyCancel(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_type' => 'tabby',
            'payment_status' => 'cancelled',
        ]);

        $paymentService->sendAdminOrderEmail('emails.admin-order-payment-cancelled', $order, 'Payment Cancelled', route('admin.bookings.edit', $order->id), 'admin');

        return view('frontend.tour.checkout.cancel')
            ->with('title', 'Payment cancelled!');
    }

    public function tabbyFailure(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_type' => 'tabby',
            'payment_status' => 'failed',
        ]);

        $paymentService->sendAdminOrderEmail(
            'emails.admin-order-payment-failed',
            $order,
            'Payment Failed',
            route('admin.bookings.edit', $order->id),
            'admin'
        );

        return view('frontend.tour.checkout.error')
            ->with('title', 'Payment failed!');
    }

    public function postpaySuccess(Request $request, $order_id, PaymentService $paymentService)
    {
        $status = $request->get('status');
        $postpayOrderId = $request->get('order_id');

        if ($status !== 'APPROVED') {
            return redirect()
                ->route('checkout.error', ['order_id' => $order_id])
                ->with('notify_error', 'Postpay payment not approved');
        }

        $order = Order::findOrFail($order_id);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.postpay.io/orders/' . $postpayOrderId . '/capture',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . env('POSTPAY_SECRET_KEY'),
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (! empty($result['error'])) {
            return redirect()
                ->route('checkout.error', ['order_id' => $order_id])
                ->with('notify_error', 'Postpay capture failed');
        }

        if (($result['status'] ?? null) === 'captured') {
            Order::findOrFail($order_id)->update([
                'payment_type' => 'postpay',
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'payment_date' => now(),
            ]);

            $paymentService->sendAdminOrderEmail('emails.admin-order-success', $order, 'New Order Paid', route('admin.bookings.edit', $order->id), 'admin');
            $paymentService->sendAdminOrderEmail('emails.customer-order-success', $order, 'Your Order is Confirmed', route('user.bookings.edit', $order->id), 'user');

            Session::forget('cart');

            return view('frontend.tour.checkout.success', ['order_id' => $order_id, 'payment_type' => 'postpay']);
        }

        return redirect()
            ->route('checkout.error', ['order_id' => $order_id])
            ->with('notify_error', 'Unexpected Postpay response');
    }

    public function postpayCancel($order_id)
    {
        return redirect()
            ->route('checkout.cancel', ['order_id' => $order_id, 'payment_type' => 'postpay']);
    }

    public function postpayFailure($order_id)
    {
        return redirect()
            ->route('checkout.error', ['order_id' => $order_id])
            ->with('notify_error', 'Postpay payment failed');
    }

    public function cancel(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_type' => $request->payment_type,
            'payment_status' => 'cancelled',
            'payment_date' => now(),
        ]);

        $paymentService->sendAdminOrderEmail('emails.admin-order-payment-cancelled', $order, 'Payment Cancelled', route('admin.bookings.edit', $order->id), 'admin');

        return view('frontend.tour.checkout.cancel')
            ->with('title', 'Payment cancelled');
    }

    public function paypalSuccess(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        try {
            // Since buttons capture payment client-side, we just mark it as paid
            $order->update([
                'payment_type' => $request->payment_type ?? 'paypal',
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'payment_date' => now(),
            ]);

            $cart = json_decode($order->cart_data, true);

            // Send emails and apply coupons
            $paymentService->sendAdminOrderEmail(
                'emails.admin-order-success',
                $order,
                'New Order Paid',
                route('admin.bookings.edit', $order->id),
                'admin'
            );

            $paymentService->sendAdminOrderEmail(
                'emails.customer-order-success',
                $order,
                'Your Order is Confirmed',
                route('user.bookings.edit', $order->id),
                'user'
            );

            $paymentService->saveAppliedUserCoupons($cart, $order);

            Session::forget('cart');

            return view('frontend.tour.checkout.success')
                ->with('title', 'Payment successful!');
        } catch (\Exception $e) {
            return redirect()
                ->route('checkout.error', ['order_id' => $order->id])
                ->with('notify_error', 'Payment processing exception: ' . $e->getMessage());
        }
    }

    public function error(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_type' => $request->payment_type,
            'payment_status' => 'failed',
            'payment_date' => now(),
        ]);

        $paymentService->sendAdminOrderEmail(
            'emails.admin-order-payment-failed',
            $order,
            'Payment Failed',
            route('admin.bookings.edit', $order->id),
            'admin'
        );

        return view('frontend.tour.checkout.error')
            ->with('title', 'Payment failed due to an error');
    }

    protected function calculateAmountToCharge(array $cart, Order $order): float
    {
        $totalPrice = $cart['total_price'] ?? 0;

        // If advance payment exists, charge only the remaining
        if ($order->advance_amount > 0) {
            return round($totalPrice - $order->advance_amount, 2);
        }

        // Otherwise, charge the full total
        return round($totalPrice, 2);
    }

    public function showPayPalPage(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        if ($order->payment_status === 'paid') {
            abort(404);
        }

        // Decode cart
        $cart = json_decode($order->cart_data, true) ?? [];

        // Calculate remaining amount considering advance payment
        $amountAED = $this->calculateAmountToCharge($cart, $order);

        try {
            $response = Http::get('https://api.exchangerate-api.com/v4/latest/AED');

            if ($response->successful()) {
                $exchangeRates = $response->json();
                $usdRate = $exchangeRates['rates']['USD'];
                $usdAmount = $amountAED * $usdRate;
            } else {
                // Fallback to a fixed rate if API fails
                $usdAmount = $amountAED * 0.27;  // Approximate rate as fallback
            }
        } catch (\Exception $e) {
            // Handle API errors gracefully
            \Log::error('Currency conversion failed: ' . $e->getMessage());
            $usdAmount = $amountAED * 0.27;  // Fallback rate
        }

        return view('frontend.tour.checkout.paypal', compact('order', 'usdAmount'));
    }
}
