<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\CommitteeMember;
use App\Models\HonoraryAdvisoryCouncilMember;
use App\Models\ExecutiveAdvisoryCouncilMember;
use App\Models\Gallery;
use App\Models\Activity;
use App\Models\ActivityDetail;
use App\Models\SportsTeam;
use App\Models\BloodDonor;
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

        $activityDetailLabels = [
            'tree-plantation' => 'Tree Plantation',
            'educational-support' => 'Educational Support',
            'winter-clothing' => 'Winter Clothing',
            'health-awareness' => 'Health Awareness',
        ];

        $activityDetails = ActivityDetail::with('activity')
            ->whereIn('slug', array_keys($activityDetailLabels))
            ->get()
            ->keyBy('slug');

        $dashboardEditSections = [
            [
                'title' => 'Members',
                'manage_url' => route('admin.members'),
                'empty' => 'No members yet',
                'items' => Member::with('application')->latest()->take(5)->get()->map(fn ($member) => [
                    'title' => $member->name ?: ($member->application->full_name ?? $member->full_name ?? 'Member #' . $member->id),
                    'meta' => $member->member_id ?: ($member->phone ?: 'ID #' . $member->id),
                    'edit_url' => route('admin.members', ['edit' => $member->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Honorary Advisory Council',
                'manage_url' => route('admin.honorary-advisory-council'),
                'empty' => 'No honorary advisory council members yet',
                'items' => HonoraryAdvisoryCouncilMember::orderBy('created_at', 'asc')->take(5)->get()->map(fn ($member) => [
                    'title' => $member->name,
                    'meta' => $member->position,
                    'edit_url' => route('admin.honorary-advisory-council', ['edit' => $member->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Executive Advisory Council',
                'manage_url' => route('admin.executive-advisory-council'),
                'empty' => 'No executive advisory council members yet',
                'items' => ExecutiveAdvisoryCouncilMember::orderBy('created_at', 'asc')->take(5)->get()->map(fn ($member) => [
                    'title' => $member->name,
                    'meta' => $member->position,
                    'edit_url' => route('admin.executive-advisory-council', ['edit' => $member->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Committee',
                'manage_url' => route('admin.committee'),
                'empty' => 'No committee members yet',
                'items' => CommitteeMember::orderBy('created_at', 'asc')->take(5)->get()->map(fn ($member) => [
                    'title' => $member->name,
                    'meta' => $member->position,
                    'edit_url' => route('admin.committee', ['edit' => $member->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Gallery',
                'manage_url' => route('admin.gallery'),
                'empty' => 'No gallery images yet',
                'items' => Gallery::latest()->take(5)->get()->map(fn ($image) => [
                    'title' => $image->caption ?: basename($image->image),
                    'meta' => $image->created_at?->format('d M Y') ?: 'ID #' . $image->id,
                    'edit_url' => route('admin.gallery', ['edit' => $image->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Activities',
                'manage_url' => route('admin.activities'),
                'empty' => 'No activities yet',
                'items' => Activity::latest()->take(5)->get()->map(fn ($activity) => [
                    'title' => $activity->title,
                    'meta' => $activity->title_en ?: ($activity->created_at?->format('d M Y') ?: 'ID #' . $activity->id),
                    'edit_url' => route('admin.activities', ['edit' => $activity->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Activity Detail Pages',
                'manage_url' => route('admin.activity-details'),
                'empty' => 'No activity detail pages yet',
                'items' => collect($activityDetailLabels)->map(function ($label, $slug) use ($activityDetails) {
                    $detail = $activityDetails->get($slug);

                    return [
                        'title' => $label,
                        'meta' => $detail ? ($detail->title ?: $slug) : 'Detail page not found',
                        'edit_url' => $detail ? route('admin.activity-details', ['edit' => $detail->id]) : route('admin.activity-details'),
                        'editable' => (bool) $detail,
                    ];
                })->values(),
            ],
            [
                'title' => 'Sports Management',
                'manage_url' => route('admin.sports-teams'),
                'empty' => 'No teams yet',
                'items' => SportsTeam::with('players')->latest()->take(5)->get()->map(fn ($team) => [
                    'title' => $team->team_name,
                    'meta' => $team->sport_type . ' - ' . $team->players->count() . ' players',
                    'edit_url' => route('admin.sports-teams', ['edit' => $team->id]),
                    'editable' => true,
                ]),
            ],
            [
                'title' => 'Blood Donors',
                'manage_url' => route('admin.blood-donors'),
                'empty' => 'No blood donors yet',
                'items' => BloodDonor::ordered()->take(5)->get()->map(fn ($donor) => [
                    'title' => $donor->name,
                    'meta' => $donor->blood_group . ' - ' . $donor->contact_number,
                    'edit_url' => route('admin.blood-donors', ['edit' => $donor->id]),
                    'editable' => true,
                ]),
            ],
        ];

        return view('admin.dashboard', compact('stats', 'dashboardEditSections'));
    }
}
