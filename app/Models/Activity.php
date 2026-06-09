<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasSortOrder;

    protected $fillable = ['title', 'title_en', 'description', 'image', 'sort_order'];
}
