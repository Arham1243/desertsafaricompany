<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    protected $table = 'coupon_users';

    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'discount_applied_amount',
    ];
}
