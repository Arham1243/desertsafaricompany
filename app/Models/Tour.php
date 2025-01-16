<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['average_rating'];

    public function category()
    {
        return $this->belongsTo(TourCategory::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_tour', 'tour_id', 'city_id');
    }

    public function availabilities()
    {
        return $this->hasMany(TourAvailability::class);
    }

    public function reviews()
    {
        return $this->hasMany(TourReview::class);
    }

    public function faqs()
    {
        return $this->hasMany(TourFaq::class);
    }

    public function tourDetails()
    {
        return $this->hasMany(TourDetail::class);
    }

    public function discounts()
    {
        return $this->hasMany(TourPriceDiscount::class);
    }

    public function normalPrices()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'normal');
    }

    public function pricing()
    {
        return $this->hasMany(TourPricing::class);
    }

    public function getTotalServiceFeeAttribute()
    {
        return $this->enabled_custom_service_fee === 1 ? $this->service_fee_price : 0;
    }

    public function getTotalExtraPricesAttribute()
    {
        if ($this->is_extra_price_enabled === 1 && $this->extra_prices) {
            $extraPrices = json_decode($this->extra_prices, true);

            return collect($extraPrices)->sum('price');
        }

        return 0;
    }

    public function privatePrices()
    {
        return $this->hasOne(TourPricing::class)->where('price_type', 'private');
    }

    public function waterPrices()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'water');
    }

    public function promoPrices()
    {
        return $this->hasMany(TourPricing::class)->where('price_type', 'promo');
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
        return $this->belongsToMany(TourAttribute::class, 'tour_attribute_tour_attribute_item')
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

    public function openHours()
    {
        return $this->hasMany(TourOpenHour::class);
    }

    public function normalItineraries()
    {
        return $this->hasMany(TourItinerary::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if ($item->isForceDeleting()) {
                self::deleteImage($item->featured_image);
                if ($item->seo) {
                    self::deleteImage($item->seo->seo_featured_image);
                    self::deleteImage($item->seo->fb_featured_image);
                    self::deleteImage($item->seo->tw_featured_image);
                    $item->seo->delete();
                }
                $item->attributes()->detach();
                $item->media()->each(function ($media) {
                    self::deleteImage($media->image_path);
                });
            }
        });
    }

    public static function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
