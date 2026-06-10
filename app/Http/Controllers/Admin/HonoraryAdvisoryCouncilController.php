<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HonoraryAdvisoryCouncilMember;
use Illuminate\Http\Request;

class HonoraryAdvisoryCouncilController extends Controller
{
    public function index()
    {
        $members = HonoraryAdvisoryCouncilMember::withoutGlobalScope('sort_order')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.honorary-advisory-council.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('honorary-advisory-council', 'public');
        }

        $data['sort_order'] = HonoraryAdvisoryCouncilMember::max('sort_order') + 1;
        HonoraryAdvisoryCouncilMember::create($data);
        return redirect()->route('admin.honorary-advisory-council')->with('success', 'Honorary advisory council member added.');
    }

    public function update(Request $request, $id)
    {
        $member = HonoraryAdvisoryCouncilMember::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) \Storage::disk('public')->delete($member->photo);
            $data['photo'] = $request->file('photo')->store('honorary-advisory-council', 'public');
        }

        $member->update($data);
        return redirect()->route('admin.honorary-advisory-council')->with('success', 'Honorary advisory council member updated.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            HonoraryAdvisoryCouncilMember::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $member = HonoraryAdvisoryCouncilMember::findOrFail($id);
        if ($member->photo) \Storage::disk('public')->delete($member->photo);
        $member->delete();
        return redirect()->route('admin.honorary-advisory-council')->with('success', 'Honorary advisory council member deleted.');
    }
}
