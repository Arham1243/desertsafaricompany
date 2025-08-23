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
        $data = compact('tours', 'cart');

        return view('frontend.tour.cart.index')
            ->with('title', 'Cart')
            ->with($data);
    }

    public function add(Request $request, $tourId)
    {
        dd($request->all());
        $cardData = $request->except('_token', 'applied_coupons');

        $cart = Session::get('cart', ['tours' => [], 'subtotal' => 0, 'service_fee' => 0, 'total_price' => 0]);
        if ($request->has('applied_coupons')) {
            $cart['applied_coupons'] = $request->input('applied_coupons');
        }
        if (! isset($cart['tours'][$tourId])) {
            $cardData['subtotal'] = $cardData['subtotal'] ?? 0;
            $cardData['service_fee'] = $cardData['service_fee'] ?? 0;
            $cardData['total_price'] = $cardData['total_price'] ?? ($cardData['subtotal'] + $cardData['service_fee']);

            $cart['tours'][$tourId] = [
                'data' => $cardData,
            ];

            Session::put('cart', $this->updateCombinedTotals($cart, $request, 'add'));

            return redirect()->route('cart.index')->with('notify_success', 'Tour added to cart successfully.');
        }

        return redirect()->route('cart.index')->with('notify_error', 'Tour already in cart.');
    }

    public function update(Request $request)
    {
        $cartData = json_decode($request->cart, true);
        Session::put('cart', $cartData);

        return redirect()->route('checkout.index');
    }

    public function remove($tourId)
    {
        $cart = Session::get('cart', ['tours' => [], 'subtotal' => 0, 'service_fee' => 0, 'total_price' => 0]);

        if (isset($cart['tours'][$tourId])) {
            $cart = $this->updateCombinedTotals($cart, $tourId, 'remove');

            unset($cart['tours'][$tourId]);

            if ($cart['subtotal'] <= 0 || $cart['total_price'] <= 0) {
                Session::forget('cart');
            } else {
                Session::put('cart', $cart);
            }

            return redirect()->back()->with('notify_success', 'Item removed from cart successfully.');
        }

        return redirect()->back()->with('notify_error', 'Item not found in cart.');
    }

    private function updateCombinedTotals($cart, $request, $action)
    {
        if ($action === 'add') {
            $combined = [
                'subtotal' => $cart['subtotal'],
                'service_fee' => $cart['service_fee'],
                'total_price' => $cart['total_price'],
            ];

            $combined['subtotal'] += $request->subtotal;
            $combined['service_fee'] += $request->service_fee;
            $combined['total_price'] += $request->total_price;

            $cart['subtotal'] = $combined['subtotal'];
            $cart['service_fee'] = $combined['service_fee'];
            $cart['total_price'] = $combined['total_price'];
        } elseif ($action === 'remove') {
            $cart['subtotal'] -= $cart['tours'][$request]['data']['subtotal'];
            $cart['service_fee'] -= $cart['tours'][$request]['data']['service_fee'];
            $cart['total_price'] -= $cart['tours'][$request]['data']['total_price'];
        }

        return $cart;
    }
}
