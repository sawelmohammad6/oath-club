<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodDonor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BloodDonorController extends Controller
{
    public function index()
    {
        $bloodGroups = BloodDonor::BLOOD_GROUPS;
        $donors = BloodDonor::withoutGlobalScope('sort_order')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);
        return view('admin.blood-donors.index', compact('bloodGroups', 'donors'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = BloodDonor::max('sort_order') + 1;
        BloodDonor::create($data);
        return redirect()->route('admin.blood-donors')->with('success', 'Blood donor added.');
    }

    public function update(Request $request, $id)
    {
        $donor = BloodDonor::findOrFail($id);
        $donor->update($this->validateData($request));
        return redirect()->route('admin.blood-donors')->with('success', 'Blood donor updated.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            BloodDonor::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        BloodDonor::findOrFail($id)->delete();
        return redirect()->route('admin.blood-donors')->with('success', 'Blood donor deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'blood_group' => ['required', 'string', Rule::in(BloodDonor::BLOOD_GROUPS)],
            'contact_number' => 'required|string|max:30',
        ]);
    }
}
