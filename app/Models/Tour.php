<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'availability_open_hours' => 'array',
    ];

    protected $appends = ['average_rating', 'formated_price_type', 'availability_status', 'detail_url', 'tour_lowest_price', 'advance_booking_badge', 'has_five_star_five_review'];

    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(TourCategory::class, 'category_tour');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function availabilities()
    {
        return $this->hasMany(TourAvailability::class);
    }

    public function getHasFiveStarFiveReviewAttribute()
    {
        return $this->reviews()
            ->where('rating', 5)
            ->where('status', 'active')
            ->count() >= 5;
    }

    public function reviews()
    {
        return $this->hasMany(TourReview::class)->where('status', 'active');
    }

    public function faqs()
    {
        return $this->hasMany(TourFaq::class);
    }

    public function pricing()
    {
        return $this->hasMany(TourPricing::class);
    }

    public function getTotalServiceFeeAttribute()
    {
        return $this->enabled_custom_service_fee === 1 ? $this->service_fee_price : 0;
    }

    public function getInitialPriceAttribute()
    {
        $total_price = 0;

        if ($this->enabled_custom_service_fee === 1) {
            $total_price += $this->service_fee_price;
        }

        if ($this->is_extra_price_enabled === 1 && $this->extra_prices) {
            foreach (json_decode($this->extra_prices) as $extra_price) {
                $total_price += $extra_price->price;
            }
        }
        if (! $this->price_type) {
            $total_price += $this->sale_price;
        }

        return $total_price;
    }

    public function getTotalExtraPricesAttribute()
    {
        if ($this->is_extra_price_enabled === 1 && $this->extra_prices) {
            $extraPrices = json_decode($this->extra_prices, true);

            return collect($extraPrices)->sum('price');
        }

        return 0;
    }

    public function normalPrices()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'normal');
    }

    public function privatePrices()
    {
        return $this->hasOne(TourPricing::class)->where('price_type', 'private');
    }

    public function author()
    {
        return $this->belongsTo(TourAuthor::class, 'author_id');
    }

    public function waterPrices()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'water');
    }

    public function promoPrices()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'promo');
    }

    public function promoAddons()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'promoAddOn');
    }

    public function getLowestPromoPriceAttribute()
    {
        if (! $this->promoPrices || ! $this->promo_discount_config) {
            return null;
        }

        $now = now();
        $day = strtolower($now->englishDayOfWeek);
        $hour = $now->hour;

        $config = json_decode($this->promo_discount_config, true);

        $isWeekend = in_array($day, ['friday', 'saturday', 'sunday']);

        $discountPercent = $isWeekend
            ? ($config['weekend_discount_percent'] ?? 0)
            : ($config['weekday_discount_percent'] ?? 0);

        $prices = $this->promoPrices->map(function ($promo) use ($discountPercent) {
            $original = (float) $promo->original_price;
            $discounted = $original - ($original * ($discountPercent / 100));

            return [
                'original' => $original,
                'discounted' => $discounted,
            ];
        });

        $lowest = $prices
            ->filter(fn($p) => $p['discounted'] > 0)
            ->sortBy('discounted')
            ->first();

        return $lowest
            ? [
                'original' => $lowest['original'],
                'discounted' => $lowest['discounted'],
            ]
            : null;
    }

    public function getFormatedPriceTypeAttribute()
    {
        $types = [
            'normal' => 'Group Pricing',
            'private' => 'Private Tour',
            'water' => 'Water Adventure',
            'promo' => 'Promo Tour',
        ];

        return $types[$this->price_type] ?? null;
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function attributes()
    {
        return $this
            ->belongsToMany(TourAttribute::class, 'tour_attribute_tour_attribute_item')
            ->withPivot('tour_attribute_item_id')
            ->withTimestamps();
    }

    public function tourAttributes()
    {
        return $this->belongsToMany(TourAttribute::class, 'tour_attribute_tour_attribute_item');
    }

    public function getAverageRatingAttribute()
    {
        $totalReviews = $this->reviews()->count();
        $sumOfRatings = $this->reviews()->sum('rating');

        return $totalReviews > 0 ? round($sumOfRatings / $totalReviews, 1) : null;
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function normalItineraries()
    {
        return $this->hasMany(TourItinerary::class);
    }

    public function orders()
    {
        return Order::whereJsonContains('cart_data->tours', function ($query) {
            $query->where('tour_id', $this->id);
        })
            ->where('payment_status', 'paid')
            ->get();
    }

    public function addOns()
    {
        return $this->hasMany(TourAddOn::class, 'tour_id');
    }

    public function views()
    {
        return $this->hasMany(TourView::class);
    }

    public function getAvailabilityStatusAttribute(): array
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        // --------------------------
        // 1. Check explicit tour availabilities
        // --------------------------
        $availabilityDates = $this->availabilities->pluck('is_available', 'date')->toArray();

        if (!isset($availabilityDates[$today]) || $availabilityDates[$today] == 0) {
            return [
                'available' => false,
                'user_message' => 'This tour is not available today.',
            ];
        }

        $isAvailable = true;
        $messages = [];

        // --------------------------
        // 2. Check open hours if enabled
        // --------------------------
        if ((int) $this->is_open_hours === 1) {
            $hours = json_decode($this->availability_open_hours, true);
            
            if (!is_array($hours)) {
                $isAvailable = false;
                $messages[] = 'Tour hours data is invalid. Please contact support.';
            } else {
                $todayDay = strtolower($now->format('l'));
                $todayHours = collect($hours)
                    ->first(fn($h) => strtolower($h['day']) === $todayDay);

                if (empty($todayHours['open_time']) || empty($todayHours['close_time'])) {
                    $isAvailable = false;
                    $messages[] = 'This tour is not available today.';
                } else {
                    try {
                        $open = Carbon::createFromTimeString($todayHours['open_time']);
                        $close = Carbon::createFromTimeString($todayHours['close_time']);

                        if ($close->lt($open)) {
                            $close->addDay();
                        }

                        if (! $now->between($open, $close)) {
                            $isAvailable = false;
                            $messages[] =
                                'Tour is currently closed. It will be open from ' .
                                $open->format('h:i A') .
                                ' to ' .
                                $close->format('h:i A') .
                                ' today.';
                        }
                    } catch (\Exception $e) {
                        $isAvailable = false;
                        $messages[] = 'Tour hours are invalid today. Please contact support.';
                    }
                }
            }
        }

        // --------------------------
        // 3. Check advance booking rules if enabled
        // --------------------------
        if ((int) $this->is_advance_booking === 1) {
            $config = json_decode($this->availability_advance_booking, true);
            $type = $config['advance_booking_type'] ?? 'immediately';
            $days = (int) ($config['days'] ?? 0);
            $time = $config['time'] ?? '00:00';

            if ($type === 'immediately') {
                // no additional check
            } elseif ($days > 0) {
                $isAvailable = false;
                $messages[] = 'This tour must be booked at least ' . $days . ' day(s) in advance.';
            } else {
                try {
                    [$h, $m] = explode(':', $time);
                    $cutoff = Carbon::today()->setHour((int)$h)->setMinute((int)$m)->setSecond(0);

                    if ($now->gt($cutoff)) {
                        $isAvailable = false;
                        $messages[] = 'Booking for today is closed. You can book for tomorrow.';
                    }
                } catch (\Exception $e) {
                    $isAvailable = false;
                    $messages[] = 'This tour is not available for booking today.';
                }
            }
        }

        // --------------------------
        // 4. Return final result
        // --------------------------
        return [
            'available' => $isAvailable,
            'user_message' => $isAvailable
                ? 'Good news! This tour is available right now.'
                : implode(' ', $messages),
        ];
    }

    public function getAdvanceBookingBadgeAttribute(): ?string
    {
        if ((int) $this->is_advance_booking !== 1) {
            return null;
        }

        $config = json_decode($this->availability_advance_booking, true);

        $type = $config['advance_booking_type'] ?? 'immediately';
        $days = (int) ($config['days'] ?? 0);
        $time = $config['time'] ?? '00:00';

        if ($type === 'immediately') {
            return null;
        }

        // Any future-day restriction means tomorrow
        if ($days > 0) {
            return 'tomorrow';
        }

        $now = Carbon::now();

        try {
            [$h, $m] = explode(':', $time);
            $cutoff = Carbon::today()
                ->setHour((int) $h)
                ->setMinute((int) $m)
                ->setSecond(0);
        } catch (\Exception $e) {
            return 'tomorrow';
        }

        // days = 0 logic
        return $now->lte($cutoff) ? 'today' : 'tomorrow';
    }


    protected function getNextAvailableDay(): ?string
    {
        $hours = json_decode($this->availability_open_hours, true);
        if (! is_array($hours) || empty($hours)) {
            return null;
        }

        $now = Carbon::now();
        for ($i = 1; $i <= 7; $i++) {
            $nextDay = $now->copy()->addDays($i)->format('l');
            $match = collect($hours)->first(fn($h) => strtolower($h['day']) === strtolower($nextDay));
            if ($match) {
                return strtolower($nextDay);
            }
        }

        return null;
    }

    public function getDetailUrlAttribute()
    {
        return buildTourDetailUrl($this);
    }

    public function getTourLowestPriceAttribute()
    {
        switch ($this->price_type) {
            case 'normal':
                return $this->calculateNormalPrice();
            case 'private':
                return $this->calculatePrivatePrice();
            case 'water':
                return $this->calculateWaterPrice();
            case 'promo':
                return $this->calculatePromoPrice();
            default:
                return $this->calculateDefaultPrice();
        }
    }

    protected function calculateNormalPrice()
    {
        return (int) ($this->normalPrices->min('price') ?? 0);
    }

    protected function calculatePrivatePrice()
    {
        return (int) ($this->privatePrices->car_price ?? 0);
    }

    protected function calculateWaterPrice()
    {
        return (int) ($this->waterPrices->min('water_price') ?? 0);
    }

    protected function calculatePromoPrice()
    {
        return $this->getLowestPromoPriceAttribute()['discounted'];
    }

    protected function calculateDefaultPrice()
    {
        return (int) ($this->sale_price ?? $this->regular_price ?? 0);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if ($item->isForceDeleting()) {
                self::deleteImageIfNotUsed($item->promotional_image);
                self::deleteImageIfNotUsed($item->banner_image);
                self::deleteImageIfNotUsed($item->featured_image);

                if ($item->seo) {
                    self::deleteImageIfNotUsed($item->seo->seo_featured_image);
                    self::deleteImageIfNotUsed($item->seo->fb_featured_image);
                    self::deleteImageIfNotUsed($item->seo->tw_featured_image);
                    $item->seo->delete();
                }

                $item->attributes()->detach();
                $item->media()->each(function ($media) {
                    self::deleteImageIfNotUsed($media->file_path);
                });
            }
        });
    }

    public static function deleteImageIfNotUsed($imagePath)
    {
        if ($imagePath) {
            $imageUsedByAnotherTour = \App\Models\Tour::whereHas('seo', function ($query) use ($imagePath) {
                $query
                    ->where('seo_featured_image', $imagePath)
                    ->orWhere('fb_featured_image', $imagePath)
                    ->orWhere('tw_featured_image', $imagePath);
            })->orWhereHas('media', function ($query) use ($imagePath) {
                $query->where('file_path', $imagePath);
            })->orWhere(function ($query) use ($imagePath) {
                $query
                    ->where('promotional_image', $imagePath)
                    ->orWhere('banner_image', $imagePath)
                    ->orWhere('featured_image', $imagePath);
            })->exists();

            if (! $imageUsedByAnotherTour) {
                self::deleteImage($imagePath);
            }
        }
    }

    public static function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
