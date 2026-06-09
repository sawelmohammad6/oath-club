<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class HonoraryAdvisoryCouncilMember extends Model
{
    use HasSortOrder;

    protected $fillable = ['name', 'position', 'photo', 'sort_order'];

    protected $table = 'honorary_advisory_council_members';
}
