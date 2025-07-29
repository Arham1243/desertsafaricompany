<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourCategoryView;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function details(Request $request, $slug)
    {
        $item = TourCategory::where('slug', $slug)->firstOrFail();
        $tourCategories = TourCategory::where('status', 'publish')
            ->where('id', '!=', $item->id)
            ->latest()
            ->get();
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

        $data = compact(
            'item',
            'featuredReviews',
            'tours',
            'tourCategories',
            'thisWeekViews',
        );

        return view('frontend.tour.category.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with($data);
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
