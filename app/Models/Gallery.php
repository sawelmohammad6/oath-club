<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasSortOrder;

    protected $fillable = ['image', 'caption', 'sort_order'];
}
