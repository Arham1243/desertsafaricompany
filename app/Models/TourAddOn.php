<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourAddOn extends Model
{
    protected $fillable = ['tour_id', 'heading', 'tour_ids'];

    protected $casts = [
        'tour_ids' => 'array',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
