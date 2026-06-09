<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class ActivityDetail extends Model
{
    use HasSortOrder;

    protected $fillable = ['slug', 'activity_id', 'title', 'description', 'event_date', 'sort_order'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function images()
    {
        return $this->hasMany(ActivityImage::class);
    }
}
