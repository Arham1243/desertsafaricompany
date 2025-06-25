<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourView extends Model
{
    protected $fillable = ['tour_id', 'ip_address', 'view_date'];
}
