<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityDetail extends Model
{
    protected $fillable = ['slug', 'activity_id', 'title', 'description', 'event_date'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function images()
    {
        return $this->hasMany(ActivityImage::class);
    }
}
