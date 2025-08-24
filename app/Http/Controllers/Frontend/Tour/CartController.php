<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $tours = Tour::where('status', 'publish')->get();

        // Initialize required data arrays for cart view
        $cartTours = [];
        $promoToursData = [];
        $toursNormalPrices = [];
        $privateTourData = [];
        $waterTourTimeSlots = [];

        $data = compact('tours', 'cart', 'cartTours', 'promoToursData', 'toursNormalPrices', 'privateTourData', 'waterTourTimeSlots');

        return view('frontend.tour.cart.index')
            ->with('title', 'Cart')
            ->with($data);
    }

    public function add(Request $request, $tourId)
    {
        // Decode tourData if sent as JSON
        $tourItems = $request->has('tourData')
            ? json_decode($request->input('tourData'), true)
            : [];

        // Cart-level fields
        $subtotal = round((float) ($request->input('subtotal', 0)), 2);
        $serviceFee = round((float) ($request->input('service_fee', 0)), 2);
        $startDate = $request->input('start_date');

        // Extra prices
        $extraPrices = $request->input('extra_prices', []);
        $formattedExtras = [];
        $totalExtra = 0;
        foreach ($extraPrices as $extra) {
            $price = round((float) ($extra['price'] ?? 0), 2);
            $formattedExtras[] = [
                'name' => $extra['name'] ?? '',
                'price' => $price,
            ];
            $totalExtra += $price;
        }

        $totalPrice = round($subtotal + $serviceFee + $totalExtra, 2);

        // Prepare card data
        $cardData = [
            'tourData' => $tourItems,  // array of items
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'start_date' => $startDate,
            'extra_prices' => $formattedExtras,
            'total_price' => $totalPrice,
        ];

        // Retrieve current cart
        $cart = Session::get('cart', [
            'tours' => [],
            'subtotal' => 0,
            'total_price' => 0,
        ]);

        if ($request->has('applied_coupons')) {
            $cart['applied_coupons'] = $request->input('applied_coupons');
        }

        // Add or update tour in cart
        $cart['tours'][$tourId] = $cardData;

        Session::put('cart', $this->recalculateCartTotals($cart));

        return redirect()
            ->route('cart.index')
            ->with('notify_success', 'Tour added to cart successfully.');
    }

    public function update(Request $request)
    {
        $cartData = json_decode($request->cart, true);
        Session::put('cart', $this->recalculateCartTotals($cartData));

        return redirect()->route('checkout.index');
    }

    public function remove($tourId)
    {
        $cart = Session::get('cart', [
            'tours' => [],
            'subtotal' => 0,
            'total_price' => 0,
        ]);

        if (isset($cart['tours'][$tourId])) {
            unset($cart['tours'][$tourId]);

            if (empty($cart['tours'])) {
                Session::forget('cart');
            } else {
                $cart = $this->recalculateCartTotals($cart);
                Session::put('cart', $cart);
            }

            return redirect()->back()->with('notify_success', 'Item removed from cart successfully.');
        }

        return redirect()->back()->with('notify_error', 'Item not found in cart.');
    }

    public function sync(Request $request)
    {
        $cartData = $request->input('cart');

        if ($cartData) {
            $cart = $this->recalculateCartTotals($cartData);
            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart synced successfully',
                'cart' => $cart,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid cart data',
        ], 400);
    }

    private function recalculateCartTotals(array $cart): array
    {
        $subtotal = 0;
        $totalPrice = 0;

        foreach ($cart['tours'] as $tour) {
            $subtotal += $tour['subtotal'] ?? 0;
            $totalPrice += $tour['total_price'] ?? 0;
        }

        $cart['subtotal'] = round($subtotal, 2);
        $cart['total_price'] = round($totalPrice, 2);

        return $cart;
    }

    public function flush(Request $request)
    {
        // Clear the entire cart session
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart session flushed successfully',
        ]);
    }
}
