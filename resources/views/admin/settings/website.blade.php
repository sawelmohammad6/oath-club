@extends('layouts.admin')

@section('title', 'Website Settings')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Website Settings</h2><p class="text-gray-500 text-sm">Customize club information</p></div>
</div>

<div class="max-w-4xl">
    <div class="admin-card p-6 md:p-8">
        <form method="POST" action="{{ route('admin.settings.website') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <div class="grid md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Club Name</label><input type="text" name="club_name" value="{{ $settings['club_name'] ?? 'Oath Club' }}" class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label><input type="text" name="tagline" value="{{ $settings['tagline'] ?? '' }}" class="form-input"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">About Text</label><textarea name="about_text" rows="3" class="form-input">{{ $settings['about_text'] ?? '' }}</textarea></div>
                <div class="grid md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Vision</label><textarea name="vision_text" rows="3" class="form-input">{{ $settings['vision_text'] ?? '' }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Mission</label><textarea name="mission_text" rows="3" class="form-input">{{ $settings['mission_text'] ?? '' }}</textarea></div>
                </div>
                <div class="grid md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Bkash Number</label><input type="text" name="bkash" value="{{ $settings['bkash'] ?? '' }}" class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nagad Number</label><input type="text" name="nagad" value="{{ $settings['nagad'] ?? '' }}" class="form-input"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Membership Fee (৳)</label><input type="number" name="membership_fee" value="{{ $settings['membership_fee'] ?? '100' }}" class="form-input max-w-xs"></div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset(!empty($settings['logo']) ? 'storage/' . $settings['logo'] : 'assets/oath.png') }}" class="h-16 w-16 object-contain rounded-lg border border-gray-200" onerror="this.src='{{ asset('assets/logo.png') }}'">
                        <input type="file" name="logo" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer hover:file:bg-primary-100">
                    </div>
                </div>
                <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection