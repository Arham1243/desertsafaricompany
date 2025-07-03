<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourAuthor extends Model
{
    use SoftDeletes;

    protected $table = 'tour_authors';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function tours()
    {
        return $this->hasMany(Tour::class, 'author_id');
    }
}
