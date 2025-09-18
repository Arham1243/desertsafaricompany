<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag', 'blog_id', 'blog_tag_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function media()
    {
        return $this->hasMany(BlogMedia::class, 'blog_id');
    }

    public function reactions()
    {
        return $this->hasMany(BlogReaction::class);
    }

    public function likes()
    {
        return $this->reactions()->where('reaction', 'like');
    }

    public function dislikes()
    {
        return $this->reactions()->where('reaction', 'dislike');
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($blog) {
            if ($blog->isForceDeleting()) {
                self::deleteImage($blog->featured_image);
                foreach ($blog->media as $media) {
                    self::deleteImage($media->image_path);
                }
                if ($blog->seo) {
                    self::deleteImage($blog->seo->seo_featured_image);
                    self::deleteImage($blog->seo->fb_featured_image);
                    self::deleteImage($blog->seo->tw_featured_image);
                    $blog->seo->delete();
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
