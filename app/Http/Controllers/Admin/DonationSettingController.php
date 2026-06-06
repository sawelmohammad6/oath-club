<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationSetting;
use Illuminate\Http\Request;

class DonationSettingController extends Controller
{
    public function index()
    {
        $donationSettings = DonationSetting::latest()->get();
        return view('admin.donation-settings.index', compact('donationSettings'));
    }

    public function store(Request $request)
    {
        DonationSetting::create($this->validateData($request));
        return redirect()->route('admin.donation-settings')->with('success', 'Donation setting added.');
    }

    public function update(Request $request, $id)
    {
        $donationSetting = DonationSetting::findOrFail($id);
        $donationSetting->update($this->validateData($request));
        return redirect()->route('admin.donation-settings')->with('success', 'Donation setting updated.');
    }

    public function destroy($id)
    {
        DonationSetting::findOrFail($id)->delete();
        return redirect()->route('admin.donation-settings')->with('success', 'Donation setting deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'bkash_number' => 'nullable|string|max:30',
            'nagad_number' => 'nullable|string|max:30',
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'branch_name' => 'nullable|string|max:255',
        ]);
    }
}
