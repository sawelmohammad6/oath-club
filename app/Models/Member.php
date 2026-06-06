<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_id', 'full_name', 'name', 'father_name', 'mother_name', 'date_of_birth',
        'phone', 'email', 'occupation', 'address', 'blood_group', 'photo', 'position'
    ];

    public function application()
    {
        return $this->hasOne(MembershipApplication::class, 'phone', 'phone')
            ->where('status', 'approved')
            ->latest('id');
    }
}
