<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\News;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourCategoryView;
use App\Models\TourReview;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function details(Request $request, $country, $param2 = null, $param3 = null)
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

        $tourCategories = TourCategory::where('status', 'publish')->get();
        $tours = Tour::where('status', 'publish')->latest()->get();
        $featuredReviews = TourReview::whereIn('id', json_decode($item->tour_reviews_ids ?? '[]'))
            ->where('status', 'active')
            ->get();

        $news = News::where('status', 'publish')->latest()->get();

        $this->trackCategoryView($request, $item->id);
        $baseline = $item->views()->where('start_count', '>', 0)->value('start_count');
        $thisWeekViews = $item->views()->count() + ($baseline ?? 0);

        return view('frontend.tour.category.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with(compact(
                'item',
                'featuredReviews',
                'tours',
                'tourCategories',
                'thisWeekViews',
                'news'
            ));
    }

    public function trackCategoryView(Request $request, $categoryId)
    {
        $ip = $request->ip();
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $hasAnyViews = TourCategoryView::where('category_id', $categoryId)->exists();
        $latestView = TourCategoryView::where('category_id', $categoryId)->latest()->first();

        $newWeek = $latestView && ! $latestView->created_at->between($weekStart, $weekEnd);

        if (! $hasAnyViews) {
            TourCategoryView::where('category_id', $categoryId)->delete();
            $startCount = rand(252, 500);
        } elseif ($newWeek) {
            TourCategoryView::where('category_id', $categoryId)->delete();
            $startCount = rand(252, 500);
        } else {
            $weeklyTotal = TourCategoryView::where('category_id', $categoryId)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('start_count');

            if ($weeklyTotal >= 300) {
                TourCategoryView::where('category_id', $categoryId)->delete();
                $startCount = rand(252, 500);
            } else {
                $startCount = 0;
            }
        }

        TourCategoryView::create([
            'category_id' => $categoryId,
            'ip_address' => $ip,
            'view_date' => $today,
            'start_count' => $startCount,
        ]);
    }
}
