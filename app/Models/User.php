<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;
    protected $fillable = [
        'social_id',
        'full_name',
        'email',
        'phone',
        'country',
        'city',
        'social_token',
        'avatar',
        'dob',
        'signup_method',
        'password',
        'email_verification_token',
        'email_verified',
        'has_used_first_order_coupon',
    ];

    public function favoriteTours()
    {
        return $this->belongsToMany(
            Tour::class,
            'user_favorite_tours',
            'user_id',
            'tour_id'
        )->withTimestamps();
    }


    public function getAgeAttribute()
    {
        return $this->dob ? Carbon::parse($this->dob)->age : null;
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->withTimestamps();
    }

    public function hasCompletedProfile()
    {
        return $this->phone && $this->age && $this->country && $this->city;
    }
}
