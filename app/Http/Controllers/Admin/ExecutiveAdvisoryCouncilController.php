<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExecutiveAdvisoryCouncilMember;
use Illuminate\Http\Request;

class ExecutiveAdvisoryCouncilController extends Controller
{
    public function index()
    {
        $members = ExecutiveAdvisoryCouncilMember::withoutGlobalScope('sort_order')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.executive-advisory-council.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('executive-advisory-council', 'public');
        }

        $data['sort_order'] = ExecutiveAdvisoryCouncilMember::max('sort_order') + 1;
        ExecutiveAdvisoryCouncilMember::create($data);
        return redirect()->route('admin.executive-advisory-council')->with('success', 'Executive advisory council member added.');
    }

    public function update(Request $request, $id)
    {
        $member = ExecutiveAdvisoryCouncilMember::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) \Storage::disk('public')->delete($member->photo);
            $data['photo'] = $request->file('photo')->store('executive-advisory-council', 'public');
        }

        $member->update($data);
        return redirect()->route('admin.executive-advisory-council')->with('success', 'Executive advisory council member updated.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            ExecutiveAdvisoryCouncilMember::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $member = ExecutiveAdvisoryCouncilMember::findOrFail($id);
        if ($member->photo) \Storage::disk('public')->delete($member->photo);
        $member->delete();
        return redirect()->route('admin.executive-advisory-council')->with('success', 'Executive advisory council member deleted.');
    }
}
