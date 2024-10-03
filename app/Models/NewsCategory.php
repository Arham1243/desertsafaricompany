<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsCategory extends Model
{
    protected $table = 'news_categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_category_id',
        'is_active',
    ];

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            if ($category->seo) {
                self::deleteImage($category->seo->seo_featured_image);
                self::deleteImage($category->seo->fb_featured_image);
                self::deleteImage($category->seo->tw_featured_image);
                $category->seo->delete();
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