<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\CommitteeMember;
use App\Models\Gallery;
use App\Models\Activity;
use App\Models\Banner;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_applications' => MembershipApplication::count(),
            'pending_applications' => MembershipApplication::where('status', 'pending')->count(),
            'approved_members' => Member::count(),
            'committee_members' => CommitteeMember::count(),
            'gallery_images' => Gallery::count(),
            'activities' => Activity::count(),
            'banners' => Banner::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
