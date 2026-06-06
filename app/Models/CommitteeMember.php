<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = ['name', 'photo', 'position', 'sort_order'];

    protected $table = 'committee_members';
}
