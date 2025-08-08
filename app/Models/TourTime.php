<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TourTime extends Model
{
    use SoftDeletes;

    protected $table = 'tour_times';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, 'tour_time_id');
    }

    public function views()
    {
        return $this->hasMany(TourTimeView::class, 'tour_time_id');
    }

    public function categories()
    {
        return $this->belongsToMany(TourCategory::class, 'tour_category_tour_time');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if ($item->isForceDeleting()) {
                if ($item->seo) {
                    self::deleteImage($item->seo->seo_featured_image);
                    self::deleteImage($item->seo->fb_featured_image);
                    self::deleteImage($item->seo->tw_featured_image);
                    $item->seo->delete();
                }
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
