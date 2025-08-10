<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Tour\CategoryController;
use App\Models\City;
use App\Models\Country;
use App\Models\TourCategory;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function resolveSlug($country, $slug)
    {
        $countryModel = Country::where('iso_alpha2', $country)->firstOrFail();

        if (TourCategory::where('slug', $slug)->exists()) {
            $request = Request::create(request()->fullUrl(), 'GET');

            return app(CategoryController::class)->details($request, $country, $slug);
        }

        if (City::where('slug', $slug)->exists()) {
            return app(CityController::class)->show($country, $slug);
        }

        abort(404);
    }
}
