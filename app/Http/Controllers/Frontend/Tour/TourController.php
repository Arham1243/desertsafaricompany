<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\Models\TourCategory;
use App\Models\TourDetailPopup;
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
            ->with('title', 'Top Tours')
            ->with($data);
    }

    public function resolveSlug(Request $request, $country, $city, $category, $slug)
    {
        $countryModel = Country::where('iso_alpha2', $country)->firstOrFail();

        if (Tour::where('slug', $slug)->exists()) {
            return app(TourController::class)->details($request, $country, $city, $category, $slug);
        }
        abort(404);
    }

    public function details(Request $request, $country, $city, $category, $slug)
    {
        $settings = Setting::pluck('value', 'key');
        $detailPopups = TourDetailPopup::where('status', 'active')->get();
        $firstOrderCoupon = Coupon::where('is_first_order_coupon', 1)->where('status', 'active')->first();
        $cart = Session::get('cart', []);
        $attributes = TourAttribute::where('status', 'active')->latest()->get();

        $tour = Tour::with(['tourAttributes.items', 'categories.city', 'categories.country'])
            ->where('slug', $slug)
            ->whereHas('categories', fn ($q) => $q->where('slug', $category))
            ->firstOrFail();

        $currentCategory = $tour->categories->firstWhere('slug', $category);

        $this->trackTourView($request, $tour->id);
        $todayViews = $tour->views()->whereDate('view_date', today())->count();
        $isTourInCart = isset($cart['tours'][$tour->id]);

        $data = compact('tour', 'attributes', 'cart', 'isTourInCart', 'settings', 'todayViews', 'firstOrderCoupon', 'detailPopups', 'currentCategory');

        return view('frontend.tour.details')
            ->with('title', $tour->title)
            ->with($data);
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

        $tour = Tour::with(['promoPrices', 'promoAddons'])->findOrFail($tourId);

        $now = now();
        $hourOfDay = $now->hour;

        $config = $tour->promo_discount_config ? json_decode($tour->promo_discount_config, true) : [];

        $discountPercent = $isWeekend ? $config['weekend_discount_percent'] ?? 0 : $config['weekday_discount_percent'] ?? 0;

        $timerHours = (int) ($isWeekend ? $config['weekend_timer_hours'] ?? 0 : $config['weekday_timer_hours'] ?? 0);

        $hoursLeft = $timerHours > 0 ? ($hourOfDay % $timerHours === 0 ? $timerHours : $timerHours - ($hourOfDay % $timerHours)) : 0;
        $firstOrderCoupon = Coupon::where('is_first_order_coupon', 1)->where('status', 'active')->first();

        $promoData = collect();

        // Promo prices
        $promoData = $promoData->concat(
            $tour->promoPrices->map(function ($promoPrice) use ($firstOrderCoupon, $discountPercent, $hoursLeft) {
                $original = (float) $promoPrice->original_price;
                $discounted = $original - $original * ($discountPercent / 100);

                return [
                    'source' => 'promo',
                    'title' => $promoPrice->promo_title,
                    'slug' => $promoPrice->promo_slug,
                    'promo_is_free' => (int) $promoPrice->promo_is_free,
                    'original_price' => number_format($original, 2),
                    'discount_percent' => $discountPercent,
                    'original_discounted_price' => number_format($discounted, 2),
                    'discounted_price' => number_format($discounted, 2),
                    'promo_discounted_price' => $firstOrderCoupon
                        ? applyPromoDiscount(number_format($discounted, 2), $firstOrderCoupon->discount_type, $firstOrderCoupon->amount)
                        : null,
                    'min_person' => (int) $promoPrice->min_person ?? 0,
                    'max_person' => (int) $promoPrice->max_person ?? 200,
                    'quantity' => (int) $promoPrice->min_person ?? 0,
                    'hours_left' => $hoursLeft,
                ];
            }),
        );

        // Promo addons
        $promoData = $promoData->concat(
            $tour->promoAddons->flatMap(function ($pricing) use ($firstOrderCoupon, $hoursLeft) {
                $addons = json_decode($pricing->promo_addons ?? '[]', true);

                return collect($addons)
                    ->map(function ($addon) use ($firstOrderCoupon, $hoursLeft) {
                        if ($addon['type'] === 'simple') {
                            $original = floatval($addon['price']);
                            $discountPercent = floatval($addon['discounted_percent'] ?? 0);
                            $discounted = $original - ($original * $discountPercent) / 100;

                            return [
                                'source' => 'addon',
                                'type' => 'simple',
                                'title' => $addon['title'],
                                'slug' => $addon['promo_slug'],
                                'original_price' => number_format($original, 2),
                                'discount_percent' => $discountPercent,
                                'original_discounted_price' => number_format($discounted, 2),
                                'discounted_price' => number_format($discounted, 2),
                                'promo_discounted_price' => $firstOrderCoupon
                                ? applyPromoDiscount(number_format($discounted, 2), $firstOrderCoupon->discount_type, $firstOrderCoupon->amount)
                                : null,
                                'min_person' => (int) $addon['min_person'] ?? 0,
                                'max_person' => (int) $addon['max_person'] ?? 200,
                                'quantity' => (int) $addon['min_person'] ?? 0,
                                'hours_left' => $hoursLeft,
                            ];
                        }

                        if ($addon['type'] === 'timeslot') {
                            $slots = collect($addon['slots'] ?? []);
                            $firstSlotDiscount = floatval($slots[0]['discounted_percent'] ?? 0);

                            return [
                                'source' => 'addon',
                                'type' => 'timeslot',
                                'title' => $addon['title'],
                                'slug' => $addon['promo_slug'],
                                'original_discounted_price' => $firstSlotDiscount,
                                'discounted_price' => $firstSlotDiscount,
                                'promo_discounted_price' => $firstOrderCoupon
                                ? applyPromoDiscount(number_format($firstSlotDiscount, 2), $firstOrderCoupon->discount_type, $firstOrderCoupon->amount)
                                : null,
                                'hours_left' => $hoursLeft,
                                'quantity' => (int) $addon['min_person'] ?? 0,
                                'min_person' => (int) $addon['min_person'] ?? 0,
                                'max_person' => (int) $addon['max_person'] ?? 200,
                                'selected_slots' => [],
                                'slots' => $slots
                                    ->map(function ($slot) use ($firstOrderCoupon) {
                                        $price = floatval($slot['price']);
                                        $discountPercent = floatval($slot['discounted_percent'] ?? 0);
                                        $discounted = $price - ($price * $discountPercent) / 100;

                                        return [
                                            'time' => $slot['time'],
                                            'original_price' => number_format($price, 2),
                                            'discount_percent' => $discountPercent,
                                            'original_discounted_price' => number_format($discounted, 2),
                                            'discounted_price' => number_format($discounted, 2),
                                            'promo_discounted_price' => $firstOrderCoupon
                                            ? applyPromoDiscount(number_format($discounted, 2), $firstOrderCoupon->discount_type, $firstOrderCoupon->amount)
                                            : null,
                                        ];
                                    })
                                    ->values(),
                            ];
                        }

                        return null;
                    })
                    ->filter();
            }),
        );

        return response()->json($promoData->values());
    }
}
