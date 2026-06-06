<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExecutiveAdvisoryCouncilMember extends Model
{
    protected $fillable = ['name', 'position', 'photo'];

    protected $table = 'executive_advisory_council_members';
}
