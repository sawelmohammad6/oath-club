<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class ExecutiveAdvisoryCouncilMember extends Model
{
    use HasSortOrder;

    protected $fillable = ['name', 'position', 'photo', 'sort_order'];

    protected $table = 'executive_advisory_council_members';
}
