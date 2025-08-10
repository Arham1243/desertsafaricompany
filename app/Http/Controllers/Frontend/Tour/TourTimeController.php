<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourTime;
use App\Models\TourTimeView;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TourTimeController extends Controller
{
    public function details(Request $request, $country, $city, $category, $slug)
    {
        $cityModel = City::where('slug', $city)->firstOrFail();
        $item = TourTime::where('slug', $slug)
            ->where('city_id', $cityModel->id)
            ->firstOrFail();
        $tourCategories = TourTime::where('status', 'publish')
            ->latest()
            ->get();
        $tours = Tour::where('status', 'publish')->latest()->get();
        $featuredReviews = Testimonial::whereIn('id', json_decode($item->tour_reviews_ids ?? '[]'))
            ->where('status', 'active')
            ->where('rating', '5')
            ->get();

        $this->trackTimeView($request, $item->id);
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

        return view('frontend.tour.time-category.details')
            ->with('title', ucfirst(strtolower($item->name)))
            ->with($data);
    }

    public function trackTimeView(Request $request, $timeId)
    {
        $ip = $request->ip();
        $today = now()->toDateString();

        TourTimeView::firstOrCreate([
            'tour_time_id' => $timeId,
            'ip_address' => $ip,
            'view_date' => $today,
        ]);
    }
}
