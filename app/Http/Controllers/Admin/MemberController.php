<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('application')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.members.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        $data['name'] = $data['full_name'];

        $lastMember = Member::latest()->first();
        $lastNum = $lastMember ? intval(substr($lastMember->member_id, 3)) : 0;
        $data['member_id'] = 'OC-' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

        Member::create($data);
        return redirect()->route('admin.members')->with('success', 'Member added successfully.');
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) \Storage::disk('public')->delete($member->photo);
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        $data['name'] = $data['full_name'];
        $member->update($data);
        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        if ($member->photo) \Storage::disk('public')->delete($member->photo);
        $member->delete();
        return redirect()->route('admin.members')->with('success', 'Member deleted.');
    }
}
