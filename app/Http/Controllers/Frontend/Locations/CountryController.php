<?php

namespace App\Http\Controllers\Frontend\Locations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\PageController;
use App\Models\Country;
use App\Models\News;
use App\Models\Page;
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
        $news = News::where('status', 'publish')->latest()->get();

        return view('frontend.locations.country.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with(compact('item', 'relatedCities', 'tours', 'categories', 'news'));
    }

    public function resolveSlug($slug)
    {
        if (Country::where('iso_alpha2', $slug)->exists()) {
            return app(self::class)->show($slug);
        }

        if (Page::where('slug', $slug)->exists()) {
            return app(PageController::class)->show($slug);
        }

        abort(404);
    }
}
