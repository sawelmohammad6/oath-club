<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $members = CommitteeMember::withoutGlobalScope('sort_order')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.committee.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('committee', 'public');
        }

        $data['sort_order'] = CommitteeMember::max('sort_order') + 1;
        CommitteeMember::create($data);
        return redirect()->route('admin.committee')->with('success', 'Committee member added.');
    }

    public function update(Request $request, $id)
    {
        $member = CommitteeMember::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) \Storage::disk('public')->delete($member->photo);
            $data['photo'] = $request->file('photo')->store('committee', 'public');
        }

        $member->update($data);
        return redirect()->route('admin.committee')->with('success', 'Committee member updated.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            CommitteeMember::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $member = CommitteeMember::findOrFail($id);
        if ($member->photo) \Storage::disk('public')->delete($member->photo);
        $member->delete();
        return redirect()->route('admin.committee')->with('success', 'Committee member deleted.');
    }
}
