<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Tour;

class CityController extends Controller
{
    public function show($slug)
    {
        $item = City::where('slug', $slug)->firstOrFail();
        $tours = Tour::where('status', 'publish')->latest()->get();
        $country = $item->country;
        $relatedCities = $country->cities()->where('status', 'publish')->whereNot('id', $item->id)->get();
        $data = compact('item', 'relatedCities', 'tours', 'country');

        return view('frontend.locations.city.details')->with('title', ucfirst(strtolower($item->name)))->with($data);
    }
}
