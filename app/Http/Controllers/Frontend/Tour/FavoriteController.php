<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\UserFavoriteTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            $favoriteToursIds = UserFavoriteTour::where('user_id', $user->id)->pluck('tour_id');
        }
        $tours = Tour::whereIn('id', $favoriteToursIds ?? [])->where('status', 'publish')->get();

        return view('frontend.tour.cart.favorite')
            ->with('title', 'Favorite')->with(compact('tours'));
    }

    public function add(Request $request, $tour)
    {

        UserFavoriteTour::create([
            'user_id' => Auth::user()->id,
            'tour_id' => $tour,
        ]);

        return redirect()->back()->with('notify_success', 'Tour added to favorites successfully.');
    }

    public function remove(Request $request, $tour)
    {

        $deleted = UserFavoriteTour::where('user_id', Auth::user()->id)->where('tour_id', $tour)->delete();

        return redirect()->back()->with('notify_success', $deleted ? 'Tour removed from favorites successfully.' : 'Favorite not found.');
    }
}
