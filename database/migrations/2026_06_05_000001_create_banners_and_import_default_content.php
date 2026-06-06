<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('subtitle')->nullable();
                $table->string('link')->nullable();
                $table->string('image');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        $this->importDefaultActivities();
        $this->importDefaultBanners();
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }

    private function importDefaultActivities(): void
    {
        if (! Schema::hasTable('activities')) {
            return;
        }

        $activities = [
            [
                'title' => 'বৃক্ষরোপণ',
                'title_en' => 'Tree Plantation',
                'description' => 'পরিবেশ রক্ষায় আমরা নিয়মিত বৃক্ষরোপণ কর্মসূচি পরিচালনা করি। সবুজায়নের মাধ্যমে আমরা একটি টেকসই ভবিষ্যৎ গড়ে তুলতে চাই।',
                'asset' => 'tree.jpg',
            ],
            [
                'title' => 'রক্তদান',
                'title_en' => 'Blood Donation',
                'description' => 'জীবন বাঁচাতে নিয়মিত রক্তদান ক্যাম্পের আয়োজন। আমাদের রক্তদান কার্যক্রম ইতিমধ্যে শত শত রোগীকে সহায়তা করেছে।',
                'asset' => 'blood.jpeg',
            ],
            [
                'title' => 'শিক্ষা সহায়তা',
                'title_en' => 'Educational Support',
                'description' => 'অসহায় ও মেধাবী শিক্ষার্থীদের জন্য বৃত্তি ও শিক্ষা সামগ্রী প্রদান। শিক্ষার আলো ছড়িয়ে দিতেই আমাদের এই প্রচেষ্টা।',
                'asset' => 'education.jpeg',
            ],
            [
                'title' => 'শীতবস্ত্র বিতরণ',
                'title_en' => 'Winter Clothing',
                'description' => 'শীতার্ত মানুষের পাশে দাঁড়িয়ে উষ্ণ পোশাক বিতরণ। প্রতিবছর আমরা শত শত পরিবারের মাঝে শীতবস্ত্র পৌঁছে দেই।',
                'asset' => 'winter.jpg',
            ],
            [
                'title' => 'খেলাধুলা',
                'title_en' => 'Community Sports',
                'description' => 'খেলাধুলা প্রোগ্রামের মাধ্যমে মানুষের স্বাস্থ্য ও সামাজিক যোগাযোগ উন্নয়ন।',
                'asset' => 'sports.jpeg',
            ],
            [
                'title' => 'স্বাস্থ্য সচেতনতা',
                'title_en' => 'Health Awareness',
                'description' => 'স্বাস্থ্য সচেতনতা ক্যাম্পেইন ও বিনামূল্যে চিকিৎসা ক্যাম্পের আয়োজন। সুস্থ সমাজ গড়াই আমাদের লক্ষ্য।',
                'asset' => 'health.jpeg',
            ],
        ];

        foreach ($activities as $activity) {
            $exists = DB::table('activities')
                ->where('title_en', $activity['title_en'])
                ->orWhere('title', $activity['title'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('activities')->insert([
                'title' => $activity['title'],
                'title_en' => $activity['title_en'],
                'description' => $activity['description'],
                'image' => $this->copyPublicAsset('activities', $activity['asset']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function importDefaultBanners(): void
    {
        if (! Schema::hasTable('banners') || DB::table('banners')->exists()) {
            return;
        }

        for ($i = 1; $i <= 6; $i++) {
            $setting = DB::table('website_settings')->where('key', 'banner_' . $i)->value('value');
            $path = $setting ?: $this->copyPublicAsset('banners', "banner-{$i}.jpeg");

            if (! $path) {
                continue;
            }

            DB::table('banners')->insert([
                'title' => 'Banner ' . $i,
                'subtitle' => null,
                'link' => null,
                'image' => $path,
                'sort_order' => $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function copyPublicAsset(string $directory, string $filename): ?string
    {
        $source = public_path('assets/' . $filename);

        if (! File::exists($source)) {
            return null;
        }

        $relative = $directory . '/' . $filename;
        $destination = storage_path('app/public/' . $relative);

        if (! File::exists(dirname($destination))) {
            File::makeDirectory(dirname($destination), 0755, true);
        }

        if (! File::exists($destination)) {
            File::copy($source, $destination);
        }

        return $relative;
    }
};
