<?php

namespace App\Models;

use App\Models\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasSortOrder;

    protected $fillable = [
        'member_id', 'full_name', 'name', 'father_name', 'mother_name', 'date_of_birth',
        'phone', 'email', 'occupation', 'address', 'blood_group', 'photo', 'position',
        'sort_order',
    ];

    public function application()
    {
        return $this->hasOne(MembershipApplication::class, 'phone', 'phone')
            ->where('status', 'approved')
            ->latest('id');
    }
}
