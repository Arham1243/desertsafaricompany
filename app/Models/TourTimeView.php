<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourTimeView extends Model
{
    protected $fillable = ['tour_time_id', 'ip_address', 'view_date'];
}
