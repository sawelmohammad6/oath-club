<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\CommitteeMember;
use App\Models\HonoraryAdvisoryCouncilMember;
use App\Models\ExecutiveAdvisoryCouncilMember;
use App\Models\Gallery;
use App\Models\Activity;
use App\Models\Banner;
use App\Models\BloodDonor;
use App\Models\DonationSetting;
use App\Models\ActivityDetail;
use App\Models\SportsTeam;
use App\Models\WebsiteSetting;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function home()
    {
        $members = Member::get();
        $honoraryAdvisoryCouncilMembers = HonoraryAdvisoryCouncilMember::get();
        $executiveAdvisoryCouncilMembers = ExecutiveAdvisoryCouncilMember::get();
        $committee = CommitteeMember::get();
        $gallery = Gallery::latest()->paginate(20);
        $activities = Activity::get();
        $settings = WebsiteSetting::getAllAsArray();
        $managedBanners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $bannerImages = $managedBanners->isNotEmpty()
            ? $managedBanners->map(fn ($banner) => asset('storage/' . $banner->image))->values()
            : collect(range(1, 6))->map(function ($i) use ($settings) {
            $banner = trim($settings['banner_' . $i] ?? '');

            if ($banner !== '') {
                if (Str::startsWith($banner, ['http://', 'https://', '//'])) {
                    return $banner;
                }

                if (Str::startsWith($banner, ['assets/', 'storage/'])) {
                    return asset($banner);
                }

                return asset('storage/' . $banner);
            }

            return asset("assets/banner-{$i}.jpeg");
        })->values();

        return view('frontend.home', compact(
            'members', 'honoraryAdvisoryCouncilMembers', 'executiveAdvisoryCouncilMembers',
            'committee', 'gallery', 'activities', 'settings', 'bannerImages'
        ));
    }

    public function apply()
    {
        $settings = WebsiteSetting::getAllAsArray();
        return view('frontend.apply', compact('settings'));
    }

    public function donation()
    {
        $settings = WebsiteSetting::getAllAsArray();
        $donationSettings = DonationSetting::latest()->get();

        return view('frontend.donation', compact('settings', 'donationSettings'));
    }

    public function bloodDonors()
    {
        $settings = WebsiteSetting::getAllAsArray();
        $bloodGroups = BloodDonor::BLOOD_GROUPS;
        $donors = BloodDonor::ordered()->get();

        return view('frontend.blood-donors', compact('settings', 'bloodGroups', 'donors'));
    }

    public function storeApplication()
    {
        $data = request()->validate([
            'full_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|string|max:10',
            'transaction_id' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (request()->hasFile('photo')) {
            $data['photo'] = request()->file('photo')->store('applications', 'public');
        }

        $data['status'] = 'pending';
        \App\Models\MembershipApplication::create($data);

        return response()->json(['success' => true, 'message' => 'Application submitted successfully!']);
    }

    public function activityDetail($slug)
    {
        $settings = WebsiteSetting::getAllAsArray();
        $detail = ActivityDetail::where('slug', $slug)->with('images', 'activity')->firstOrFail();

        return view('frontend.activity-detail', compact('settings', 'detail'));
    }

    public function sports()
    {
        $settings = WebsiteSetting::getAllAsArray();
        $cricketTeams = SportsTeam::where('sport_type', 'Cricket')->with('players')->get();
        $footballTeams = SportsTeam::where('sport_type', 'Football')->with('players')->get();

        return view('frontend.sports', compact('settings', 'cricketTeams', 'footballTeams'));
    }

    public function sportsTeam($id)
    {
        $settings = WebsiteSetting::getAllAsArray();
        $team = SportsTeam::with('players')->findOrFail($id);

        return view('frontend.sports-team', compact('settings', 'team'));
    }

    public function submitContact()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Store contact message or send email
        \Illuminate\Support\Facades\Log::info('Contact form submission', $data);

        return response()->json(['success' => true, 'message' => 'Message sent successfully!']);
    }
}
