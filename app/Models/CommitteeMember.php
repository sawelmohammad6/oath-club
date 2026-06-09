<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasSortOrder;

    protected $fillable = ['name', 'photo', 'position', 'sort_order'];

    protected $table = 'committee_members';
}
