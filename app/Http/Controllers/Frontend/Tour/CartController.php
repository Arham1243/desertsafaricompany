<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.tour.cart')
            ->with('title', 'Cart');
    }

    public function add(Request $request)
    {
        dd($request->all());

        return view('frontend.tour.cart')
            ->with('title', 'Cart');
    }
}
