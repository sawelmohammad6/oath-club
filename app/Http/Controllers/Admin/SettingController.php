<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function website()
    {
        $settings = WebsiteSetting::getAllAsArray();
        return view('admin.settings.website', compact('settings'));
    }

    public function saveWebsite(Request $request)
    {
        $fields = ['club_name', 'tagline', 'about_text', 'vision_text', 'mission_text',
                    'bkash', 'nagad', 'membership_fee'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                WebsiteSetting::setValue($field, $request->input($field));
            }
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            WebsiteSetting::setValue('logo', $path);
        }

        return redirect()->route('admin.settings.website')->with('success', 'Settings saved successfully!');
    }

    public function contact()
    {
        $settings = WebsiteSetting::getAllAsArray();
        return view('admin.settings.contact', compact('settings'));
    }

    public function saveContact(Request $request)
    {
        $fields = ['phone', 'email', 'facebook', 'address', 'map_link'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                WebsiteSetting::setValue($field, $request->input($field));
            }
        }

        // Update admin credentials if provided
        if ($request->filled('username')) {
            $admin = Admin::first();
            if ($admin) {
                $admin->email = $request->input('username');
                $admin->save();
            }
        }

        if ($request->filled('password')) {
            $admin = Admin::first();
            if ($admin) {
                $admin->password = Hash::make($request->input('password'));
                $admin->save();
            }
        }

        return redirect()->route('admin.settings.contact')->with('success', 'Contact settings saved!');
    }

    public function banners()
    {
        $banners = Banner::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.settings.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        if ($this->hasLegacyBannerFiles($request)) {
            return $this->saveBanners($request);
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data['image'] = $request->file('image')->store('banners', 'public');
        $data['sort_order'] = $data['sort_order'] ?? ((Banner::max('sort_order') ?? 0) + 1);
        $data['is_active'] = $request->boolean('is_active', true);

        Banner::create($data);
        $this->syncLegacyBannerSettings();

        return redirect()->route('admin.settings.banners')->with('success', 'Banner added successfully.');
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $banner->update($data);
        $this->syncLegacyBannerSettings();

        return redirect()->route('admin.settings.banners')->with('success', 'Banner updated successfully.');
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::disk('public')->delete($banner->image);
        $banner->delete();
        $this->syncLegacyBannerSettings();

        return redirect()->route('admin.settings.banners')->with('success', 'Banner deleted successfully.');
    }

    public function saveBanners(Request $request)
    {
        for ($i = 1; $i <= 6; $i++) {
            $key = 'banner_' . $i;
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('banners', 'public');
                WebsiteSetting::setValue($key, $path);
                Banner::updateOrCreate(
                    ['sort_order' => $i],
                    ['title' => 'Banner ' . $i, 'image' => $path, 'is_active' => true]
                );
            }
        }

        $this->syncLegacyBannerSettings();

        return redirect()->route('admin.settings.banners')->with('success', 'Banners updated!');
    }

    private function hasLegacyBannerFiles(Request $request): bool
    {
        for ($i = 1; $i <= 6; $i++) {
            if ($request->hasFile('banner_' . $i)) {
                return true;
            }
        }

        return false;
    }

    private function syncLegacyBannerSettings(): void
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take(6)
            ->get()
            ->values();

        for ($i = 1; $i <= 6; $i++) {
            WebsiteSetting::setValue('banner_' . $i, optional($banners->get($i - 1))->image ?? '');
        }
    }
}
