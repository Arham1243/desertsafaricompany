<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPricing extends Model
{
    protected $casts = [
        'promo_addons' => 'array',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
