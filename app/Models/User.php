<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'social_id',
        'full_name',
        'email',
        'social_token',
        'avatar',
        'signup_method',
        'password',
        'email_verification_token',
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

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->withTimestamps();
    }
}
