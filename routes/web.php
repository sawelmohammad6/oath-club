<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\HonoraryAdvisoryCouncilController;
use App\Http\Controllers\Admin\ExecutiveAdvisoryCouncilController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ActivityDetailController;
use App\Http\Controllers\Admin\SportsTeamController;
use App\Http\Controllers\Admin\DonationSettingController;
use App\Http\Controllers\Admin\BloodDonorController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubAdminController;

// ========== PUBLIC FRONTEND ==========
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/apply', [FrontendController::class, 'apply'])->name('apply');
Route::get('/donation', [FrontendController::class, 'donation'])->name('donation');
Route::get('/blood-donors', [FrontendController::class, 'bloodDonors'])->name('blood-donors');
Route::get('/activity/{slug}', [FrontendController::class, 'activityDetail'])->name('activity.detail');
Route::get('/sports', [FrontendController::class, 'sports'])->name('sports');
Route::get('/sports/team/{id}', [FrontendController::class, 'sportsTeam'])->name('sports.team');
Route::post('/apply', [FrontendController::class, 'storeApplication'])->name('apply.store');
Route::post('/contact', [FrontendController::class, 'submitContact'])->name('contact.submit');

// ========== ADMIN AUTH ==========
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Applications
        Route::get('/applications', [ApplicationController::class, 'index'])->name('admin.applications');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::post('/applications/{id}/approve', [ApplicationController::class, 'approve'])->name('admin.applications.approve');
        Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('admin.applications.reject');
        Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('admin.applications.destroy');

        // Members
        Route::get('/members', [MemberController::class, 'index'])->name('admin.members');
        Route::post('/members', [MemberController::class, 'store'])->name('admin.members.store');
        Route::post('/members/{id}', [MemberController::class, 'update'])->name('admin.members.update');
        Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('admin.members.destroy');

        // Honorary Advisory Council
        Route::get('/honorary-advisory-council', [HonoraryAdvisoryCouncilController::class, 'index'])->name('admin.honorary-advisory-council');
        Route::post('/honorary-advisory-council', [HonoraryAdvisoryCouncilController::class, 'store'])->name('admin.honorary-advisory-council.store');
        Route::post('/honorary-advisory-council/{id}', [HonoraryAdvisoryCouncilController::class, 'update'])->name('admin.honorary-advisory-council.update');
        Route::delete('/honorary-advisory-council/{id}', [HonoraryAdvisoryCouncilController::class, 'destroy'])->name('admin.honorary-advisory-council.destroy');

        // Executive Advisory Council
        Route::get('/executive-advisory-council', [ExecutiveAdvisoryCouncilController::class, 'index'])->name('admin.executive-advisory-council');
        Route::post('/executive-advisory-council', [ExecutiveAdvisoryCouncilController::class, 'store'])->name('admin.executive-advisory-council.store');
        Route::post('/executive-advisory-council/{id}', [ExecutiveAdvisoryCouncilController::class, 'update'])->name('admin.executive-advisory-council.update');
        Route::delete('/executive-advisory-council/{id}', [ExecutiveAdvisoryCouncilController::class, 'destroy'])->name('admin.executive-advisory-council.destroy');

        // Committee
        Route::get('/committee', [CommitteeController::class, 'index'])->name('admin.committee');
        Route::post('/committee', [CommitteeController::class, 'store'])->name('admin.committee.store');
        Route::post('/committee/{id}', [CommitteeController::class, 'update'])->name('admin.committee.update');
        Route::delete('/committee/{id}', [CommitteeController::class, 'destroy'])->name('admin.committee.destroy');

        // Gallery
        Route::get('/gallery', [GalleryController::class, 'index'])->name('admin.gallery');
        Route::post('/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
        Route::delete('/gallery/bulk-delete', [GalleryController::class, 'bulkDelete'])->name('admin.gallery.bulk-delete');
        Route::get('/gallery/auto-import', [GalleryController::class, 'autoImport'])->name('admin.gallery.import');
        Route::post('/gallery/{id}/caption', [GalleryController::class, 'updateCaption'])->name('admin.gallery.caption');
        Route::post('/gallery/{id}', [GalleryController::class, 'update'])->name('admin.gallery.update');
        Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

        // Activities
        Route::get('/activities', [ActivityController::class, 'index'])->name('admin.activities');
        Route::post('/activities', [ActivityController::class, 'store'])->name('admin.activities.store');
        Route::post('/activities/{id}', [ActivityController::class, 'update'])->name('admin.activities.update');
        Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('admin.activities.destroy');

        // Donation Settings
        Route::get('/donation-settings', [DonationSettingController::class, 'index'])->name('admin.donation-settings');
        Route::post('/donation-settings', [DonationSettingController::class, 'store'])->name('admin.donation-settings.store');
        Route::post('/donation-settings/{id}', [DonationSettingController::class, 'update'])->name('admin.donation-settings.update');
        Route::delete('/donation-settings/{id}', [DonationSettingController::class, 'destroy'])->name('admin.donation-settings.destroy');

        // Activity Details
        Route::get('/activity-details', [ActivityDetailController::class, 'index'])->name('admin.activity-details');
        Route::post('/activity-details', [ActivityDetailController::class, 'store'])->name('admin.activity-details.store');
        Route::post('/activity-details/{id}', [ActivityDetailController::class, 'update'])->name('admin.activity-details.update');
        Route::delete('/activity-details/{id}', [ActivityDetailController::class, 'destroy'])->name('admin.activity-details.destroy');
        Route::delete('/activity-details/image/{id}', [ActivityDetailController::class, 'destroyImage'])->name('admin.activity-details.image.destroy');

        // Sports Teams (literal routes before parameterized)
        Route::get('/sports-teams', [SportsTeamController::class, 'index'])->name('admin.sports-teams');
        Route::post('/sports-teams', [SportsTeamController::class, 'store'])->name('admin.sports-teams.store');
        Route::post('/sports-teams/players', [SportsTeamController::class, 'storePlayer'])->name('admin.sports-teams.players.store');
        Route::post('/sports-teams/players/{id}', [SportsTeamController::class, 'updatePlayer'])->name('admin.sports-teams.players.update');
        Route::delete('/sports-teams/players/{id}', [SportsTeamController::class, 'destroyPlayer'])->name('admin.sports-teams.players.destroy');
        Route::post('/sports-teams/{id}', [SportsTeamController::class, 'update'])->name('admin.sports-teams.update');
        Route::delete('/sports-teams/{id}', [SportsTeamController::class, 'destroy'])->name('admin.sports-teams.destroy');

        // Blood Donors
        Route::get('/blood-donors', [BloodDonorController::class, 'index'])->name('admin.blood-donors');
        Route::post('/blood-donors', [BloodDonorController::class, 'store'])->name('admin.blood-donors.store');
        Route::post('/blood-donors/{id}', [BloodDonorController::class, 'update'])->name('admin.blood-donors.update');
        Route::delete('/blood-donors/{id}', [BloodDonorController::class, 'destroy'])->name('admin.blood-donors.destroy');

        // Settings
        Route::get('/settings/website', [SettingController::class, 'website'])->name('admin.settings.website');
        Route::post('/settings/website', [SettingController::class, 'saveWebsite']);
        Route::get('/settings/contact', [SettingController::class, 'contact'])->name('admin.settings.contact');
        Route::post('/settings/contact', [SettingController::class, 'saveContact']);
        Route::get('/settings/banners', [SettingController::class, 'banners'])->name('admin.settings.banners');
        Route::post('/settings/banners', [SettingController::class, 'storeBanner'])->name('admin.settings.banners.store');
        Route::post('/settings/banners/{id}', [SettingController::class, 'updateBanner'])->name('admin.settings.banners.update');
        Route::delete('/settings/banners/{id}', [SettingController::class, 'destroyBanner'])->name('admin.settings.banners.destroy');

        // Sub Admin Management
        Route::get('/sub-admins', [SubAdminController::class, 'index'])->name('admin.sub-admins');
        Route::get('/sub-admins/create', [SubAdminController::class, 'create'])->name('admin.sub-admins.create');
        Route::post('/sub-admins', [SubAdminController::class, 'store'])->name('admin.sub-admins.store');
        Route::get('/sub-admins/{id}/edit', [SubAdminController::class, 'edit'])->name('admin.sub-admins.edit');
        Route::put('/sub-admins/{id}', [SubAdminController::class, 'update'])->name('admin.sub-admins.update');
        Route::delete('/sub-admins/{id}', [SubAdminController::class, 'destroy'])->name('admin.sub-admins.destroy');
    });
});
