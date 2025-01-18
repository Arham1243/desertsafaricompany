<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.tour.cart')
            ->with('title', 'Cart');
    }

    public function add(Request $request, $tourId)
    {
        $cardData = $request->except('_token');
        $cart = Session::get('cart', []);

        $cart[$tourId] = [
            'data' => $cardData,
        ];
        Session::put('cart', $cart);

        return redirect()->route('tours.cart.index')->with('notify_success', 'Tour added to cart successfully.');
    }
}
