<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@oathclub.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
            ]
        );

        $defaults = [
            ['key' => 'club_name', 'value' => 'Oath Club'],
            ['key' => 'tagline', 'value' => 'একতা, নেতৃত্ব ও সমাজসেবায় আমাদের অঙ্গীকার'],
            ['key' => 'about_text', 'value' => 'ওথ ক্লাব একটি কমিউনিটি ভিত্তিক সংগঠন যা সমাজকল্যাণ, যুব উন্নয়ন, শিক্ষা সহায়তা, পরিবেশ সচেতনতা এবং ইতিবাচক পরিবর্তনের জন্য নিবেদিত।'],
            ['key' => 'vision_text', 'value' => 'একটি সমাজ গঠন করা যেখানে প্রতিটি ব্যক্তি সম্প্রদায়ের উন্নয়ন ও সমাজকল্যাণে অবদান রাখে।'],
            ['key' => 'mission_text', 'value' => 'যুব নেতৃত্ব উন্নয়ন, শিক্ষা সহায়তা প্রদান, পরিবেশ সংরক্ষণ এবং সম্প্রদায়ের সেবার মাধ্যমে ইতিবাচক পরিবর্তন আনা।'],
            ['key' => 'bkash', 'value' => '01913474094'],
            ['key' => 'nagad', 'value' => '01913474094'],
            ['key' => 'membership_fee', 'value' => '100'],
            ['key' => 'phone', 'value' => '+880 1700-000000'],
            ['key' => 'email', 'value' => 'info@oathclub.org'],
            ['key' => 'facebook', 'value' => 'https://facebook.com/oathclub'],
            ['key' => 'address', 'value' => '১২৩, ক্লাব রোড, ঢাকা, বাংলাদেশ'],
        ];

        foreach ($defaults as $item) {
            WebsiteSetting::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value']]
            );
        }
    }
}
