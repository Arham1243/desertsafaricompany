<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['average_rating', 'formated_price_type'];

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
            ->filter(fn ($p) => $p['discounted'] > 0)
            ->sortBy('discounted')
            ->first();

        return $lowest
            ? [
                'original' => number_format($lowest['original'], 2),
                'discounted' => number_format($lowest['discounted'], 2),
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
