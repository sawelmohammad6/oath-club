<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonor extends Model
{
    public const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    protected $fillable = [
        'name',
        'blood_group',
        'contact_number',
    ];

    public static function ordered()
    {
        $case = collect(self::BLOOD_GROUPS)
            ->map(fn ($group, $index) => "WHEN '{$group}' THEN " . ($index + 1))
            ->implode(' ');

        return self::orderByRaw("CASE blood_group {$case} ELSE 99 END")
            ->orderBy('name')
            ->orderBy('id');
    }
}
