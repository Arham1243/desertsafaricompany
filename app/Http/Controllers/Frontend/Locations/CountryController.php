<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Tour;

class CountryController extends Controller
{
    public function show($slug)
    {
        $item = Country::where('slug', $slug)->firstOrFail();
        $tours = Tour::where('status', 'publish')->latest()->get();
        $relatedCities = $item->cities()
            ->where('status', 'publish')
            ->get();

        $data = compact('item', 'relatedCities', 'tours');

        return view('frontend.locations.country.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with($data);
    }
}
