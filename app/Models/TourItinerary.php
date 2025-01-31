<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourItinerary extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
