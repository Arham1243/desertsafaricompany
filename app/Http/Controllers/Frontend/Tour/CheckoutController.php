<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('frontend.tour.checkout')
            ->with('title', 'Checkout');
    }
}
