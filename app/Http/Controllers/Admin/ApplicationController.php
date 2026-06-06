<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipApplication;
use App\Models\Member;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = MembershipApplication::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = MembershipApplication::findOrFail($id);
        return view('admin.applications.show', compact('application'));
    }

    public function approve($id)
    {
        $application = MembershipApplication::findOrFail($id);
        $application->update(['status' => 'approved']);

        // Auto-create member record
        $lastMember = Member::latest()->first();
        $lastNum = $lastMember ? intval(substr($lastMember->member_id, 3)) : 0;
        $newId = 'OC-' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

        Member::create([
            'member_id' => $newId,
            'full_name' => $application->full_name,
            'name' => $application->full_name,
            'father_name' => $application->father_name,
            'mother_name' => $application->mother_name,
            'date_of_birth' => $application->date_of_birth,
            'phone' => $application->phone,
            'email' => $application->email,
            'occupation' => $application->occupation,
            'address' => $application->address,
            'blood_group' => $application->blood_group,
            'photo' => $application->photo,
        ]);

        return redirect()->route('admin.applications')->with('success', 'Application approved. Member created: ' . $newId);
    }

    public function reject($id)
    {
        $application = MembershipApplication::findOrFail($id);
        $application->update(['status' => 'rejected']);
        return redirect()->route('admin.applications')->with('success', 'Application rejected.');
    }

    public function destroy($id)
    {
        $application = MembershipApplication::findOrFail($id);
        if ($application->photo) {
            \Storage::disk('public')->delete($application->photo);
        }
        $application->delete();
        return redirect()->route('admin.applications')->with('success', 'Application deleted.');
    }
}
