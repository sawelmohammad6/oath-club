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
        $donors = BloodDonor::ordered()->paginate(20);
        return view('admin.blood-donors.index', compact('bloodGroups', 'donors'));
    }

    public function store(Request $request)
    {
        BloodDonor::create($this->validateData($request));
        return redirect()->route('admin.blood-donors')->with('success', 'Blood donor added.');
    }

    public function update(Request $request, $id)
    {
        $donor = BloodDonor::findOrFail($id);
        $donor->update($this->validateData($request));
        return redirect()->route('admin.blood-donors')->with('success', 'Blood donor updated.');
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
