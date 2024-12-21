<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Models\City;

class CityController extends Controller
{
    public function show($slug)
    {
        $item = City::where('slug', $slug)->firstOrFail();
        $relatedCities = City::where('country_id', $item->country->id)->where('status', 'publish')->whereNot('id', $item->id)->get();
        $data = compact('item', 'relatedCities');

        return view('frontend.locations.city.details')->with('title', ucfirst(strtolower($item->name)))->with($data);
    }
}
