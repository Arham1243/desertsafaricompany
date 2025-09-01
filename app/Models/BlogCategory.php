<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

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

    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_category_id');
    }

    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_category_id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
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
