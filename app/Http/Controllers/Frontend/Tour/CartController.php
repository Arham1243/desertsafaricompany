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

        return view('frontend.tour.cart')
            ->with('title', 'Cart')->with($data);
    }

    public function add(Request $request, $tourId)
    {
        $cardData = $request->except('_token');
        $cart = Session::get('cart', []);

        $cart[$tourId] = [
            'data' => $cardData,
        ];
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('notify_success', 'Tour added to cart successfully.');
    }

    public function remove($tourId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$tourId])) {
            unset($cart[$tourId]);

            Session::put('cart', $cart);

            return redirect()->back()->with('notify_success', 'Item removed from cart successfully.');
        }

        return redirect()->back()->with('notify_error', 'Item not found in cart.');
    }
}
