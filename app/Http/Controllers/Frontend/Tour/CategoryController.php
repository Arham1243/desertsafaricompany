<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourCategoryView;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function details(Request $request, $country, $param2, $param3 = null)
    {
        $countryModel = Country::where('iso_alpha2', $country)->firstOrFail();

        if ($param3) {
            $city = $param2;
            $category = $param3;
            $cityModel = City::where('slug', $city)->where('country_id', $countryModel->id)->firstOrFail();
            $item = TourCategory::where('slug', $category)
                ->where('city_id', $cityModel->id)
                ->firstOrFail();
        } else {
            $city = null;
            $category = $param2;
            $item = TourCategory::where('slug', $category)
                ->where('country_id', $countryModel->id)
                ->firstOrFail();
        }

        $query = TourCategory::where('slug', $category)
            ->where('country_id', $countryModel->id);

        if ($city) {
            $cityModel = City::where('slug', $city)
                ->where('country_id', $countryModel->id)
                ->firstOrFail();
            $query->where('city_id', $cityModel->id);
        }

        $item = $query->firstOrFail();

        $tourCategories = TourCategory::where('status', 'publish')->latest()->get();
        $tours = Tour::where('status', 'publish')->latest()->get();
        $featuredReviews = Testimonial::whereIn('id', json_decode($item->tour_reviews_ids ?? '[]'))
            ->where('status', 'active')
            ->where('rating', '5')
            ->get();

        $this->trackCategoryView($request, $item->id);
        $thisWeekViews = $item
            ->views()
            ->whereBetween('view_date', [Carbon::now()->startOfWeek(), Carbon::now()])
            ->count();

        return view('frontend.tour.category.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with(compact(
                'item',
                'featuredReviews',
                'tours',
                'tourCategories',
                'thisWeekViews'
            ));
    }

    public function trackCategoryView(Request $request, $categoryId)
    {
        $ip = $request->ip();
        $today = now()->toDateString();

        TourCategoryView::firstOrCreate([
            'category_id' => $categoryId,
            'ip_address' => $ip,
            'view_date' => $today,
        ]);
    }
}
