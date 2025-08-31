<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\News;
use App\Models\Tour;
use App\Models\TourCategory;

class CityController extends Controller
{
    public function show($country, $city)
    {
        $countryModel = Country::where('iso_alpha2', $country)->firstOrFail();
        $categories = TourCategory::where('status', 'publish')->get();
        $item = City::where('slug', $city)
            ->where('country_id', $countryModel->id)
            ->firstOrFail();

        $tours = Tour::where('status', 'publish')->latest()->get();
        $news = News::where('status', 'publish')->latest()->get();
        $relatedCities = $countryModel
            ->cities()
            ->where('status', 'publish')
            ->where('id', '!=', $item->id)
            ->get();

        return view('frontend.locations.city.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with(compact('item', 'relatedCities', 'tours', 'countryModel', 'categories', 'news'));
    }
}
