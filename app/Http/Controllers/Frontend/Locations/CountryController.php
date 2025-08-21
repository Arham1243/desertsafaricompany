<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Tour;
use App\Models\TourCategory;

class CountryController extends Controller
{
    public function show($country)
    {
        $item = Country::where('iso_alpha2', $country)->firstOrFail();
        $categories = TourCategory::where('status', 'publish')->get();
        $tours = Tour::where('status', 'publish')->latest()->get();
        $relatedCities = $item->cities()->where('status', 'publish')->get();

        return view('frontend.locations.country.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with(compact('item', 'relatedCities', 'tours', 'categories'));
    }
}
