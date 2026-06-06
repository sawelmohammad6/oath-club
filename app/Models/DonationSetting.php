<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationSetting extends Model
{
    protected $fillable = [
        'bkash_number',
        'nagad_number',
        'bank_name',
        'account_name',
        'account_number',
        'branch_name',
    ];
}
