<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourCategoryView extends Model
{
    protected $fillable = ['category_id', 'ip_address', 'view_date'];
}
