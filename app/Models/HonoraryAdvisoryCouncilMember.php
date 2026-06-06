<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HonoraryAdvisoryCouncilMember extends Model
{
    protected $fillable = ['name', 'position', 'photo'];

    protected $table = 'honorary_advisory_council_members';
}
