<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function index()
    {
        return view('frontend.tour.favorite')
            ->with('title', 'Favorite');
    }
}
