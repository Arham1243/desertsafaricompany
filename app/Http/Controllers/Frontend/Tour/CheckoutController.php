<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Tour;
use App\Services\PaymentService;
use Illuminate\Http\Request;
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

    public function store(Request $request, PaymentService $paymentService)
    {
        $cart = Session::get('cart', []);
        $totalAmount = $cart['total_price'] ?? 0;

        if ($totalAmount <= 0) {
            return redirect()
                ->route('frontend.index')
                ->with('notify_error', 'Your cart is empty.');
        }
        $order = $this->createOrder($request, $cart, $totalAmount);

        $paymentService->sendAdminOrderEmail('emails.admin-pending-order', $order, 'New Pending Order', route('admin.bookings.edit', $order->id), 'admin');

        return $paymentService->processPayment($request, $order);
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

    public function success(Request $request, PaymentService $paymentService)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_type' => $request->payment_type,
            'payment_status' => 'paid',
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
}
