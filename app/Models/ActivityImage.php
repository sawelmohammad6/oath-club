<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityImage extends Model
{
    protected $fillable = ['activity_detail_id', 'image_path'];

    public function activityDetail()
    {
        return $this->belongsTo(ActivityDetail::class);
    }
}
