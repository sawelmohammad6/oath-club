<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipApplication extends Model
{
    protected $fillable = [
        'full_name', 'father_name', 'mother_name', 'date_of_birth', 'phone',
        'email', 'occupation', 'address', 'blood_group', 'transaction_id',
        'photo', 'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];
}
