<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\Models\TourCategory;
use App\Models\TourView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TourController extends Controller
{
    public function index()
    {

        $tours = Tour::where('status', 'publish')->get();
        $data = compact('tours');

        return view('frontend.tour.index')
            ->with('title', 'Top Tours')->with($data);
    }

    public function details(Request $request, $slug)
    {
        $settings = Setting::pluck('value', 'key');
        $cart = Session::get('cart', []);
        $attributes = TourAttribute::where('status', 'active')
            ->latest()->get();
        $tour = Tour::where('slug', $slug)->with('tourAttributes.items')->first();
        $this->trackTourView($request, $tour->id);
        $todayViews = $tour->views()->whereDate('view_date', today())->count();
        if ($tour) {
            $isTourInCart = isset($cart['tours'][$tour->id]);
            $data = compact('tour', 'attributes', 'cart', 'isTourInCart', 'settings', 'todayViews');

            return view('frontend.tour.details')->with('title', $tour->title)->with($data);
        }

        return redirect()->route('index')->with('notify_error', 'Page Not Found');
    }

    public function search(Request $request)
    {
        $resourceId = $request->input('resource_id');
        $resourceType = $request->input('resource_type');
        $tours = collect();
        $resourceName = '';

        switch ($resourceType) {
            case 'city':
                $city = City::find($resourceId);
                if ($city) {
                    $tours = $city->tours()->where('status', 'publish')->get();
                    $resourceName = $city->name;
                }
                break;
            case 'country':
                $country = Country::find($resourceId);
                if ($country) {
                    $tours = Tour::whereHas('cities', function ($query) use ($country) {
                        $query->where('country_id', $country->id);
                    })->where('status', 'publish')->get();
                    $resourceName = $country->name;
                }
                break;

            case 'category':
                $category = TourCategory::find($resourceId);
                if ($category) {
                    $tours = $category->tours()->where('status', 'publish')->get();
                    $resourceName = $category->name;
                }
                break;

            default:
                $tours = collect();
                break;
        }

        return view('frontend.tour.search-results', compact('tours', 'resourceType', 'resourceName'))
            ->with('title', 'Tour Search Results');
    }

    public function trackTourView(Request $request, $tourId)
    {
        $ip = $request->ip();
        $today = now()->toDateString();

        TourView::firstOrCreate([
            'tour_id' => $tourId,
            'ip_address' => $ip,
            'view_date' => $today,
        ]);
    }

    public function getTourPromoPricesByDay(Request $request)
    {
        $tourId = $request->input('tour_id');
        $isWeekend = filter_var($request->input('isWeekend'), FILTER_VALIDATE_BOOLEAN);

        $tour = Tour::with('promoPrices')->findOrFail($tourId);

        $now = now();
        $hourOfDay = $now->hour;

        $promoDiscountConfig = $tour->promo_discount_config
            ? json_decode($tour->promo_discount_config, true)
            : [];

        $promoTourData = $tour->promoPrices->map(function ($promoPrice) use ($promoDiscountConfig, $hourOfDay, $isWeekend) {
            $originalPrice = (float) $promoPrice->original_price;

            $discountPercent = $isWeekend
                ? $promoDiscountConfig['weekend_discount_percent'] ?? 0
                : $promoDiscountConfig['weekday_discount_percent'] ?? 0;

            $timerHours = (int) ($isWeekend
                ? $promoDiscountConfig['weekend_timer_hours'] ?? 0
                : $promoDiscountConfig['weekday_timer_hours'] ?? 0);

            $hoursLeft = $timerHours > 0 ? $timerHours - ($hourOfDay % $timerHours) : 0;

            $discountedPrice = $originalPrice - $originalPrice * ($discountPercent / 100);

            return [
                'promo_title' => $promoPrice->promo_title,
                'original_price' => number_format($originalPrice, 2),
                'discount_percent' => $discountPercent,
                'discounted_price' => number_format($discountedPrice, 2),
                'quantity' => 0,
                'hours_left' => $hoursLeft,
            ];
        });

        return response()->json($promoTourData);
    }
}
