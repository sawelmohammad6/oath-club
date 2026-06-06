@extends('layouts.admin')

@section('title', 'Contact Settings')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Contact Settings</h2><p class="text-gray-500 text-sm">Manage contact information and account</p></div>
</div>

<div class="max-w-2xl">
    <div class="admin-card p-6 md:p-8">
        <form method="POST" action="{{ route('admin.settings.contact') }}">
            @csrf
            <div class="space-y-5">
                <div class="grid md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone</label><input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label><input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="form-input"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Address</label><input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="form-input"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label><input type="url" name="facebook" value="{{ $settings['facebook'] ?? '' }}" class="form-input" placeholder="https://facebook.com/yourpage"></div>
                <div class="border-t border-gray-100 pt-5 mt-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Account Settings</h3>
                    <div class="grid md:grid-cols-2 gap-5">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">New Username</label><input type="text" name="username" class="form-input" placeholder="Leave empty to keep current"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">New Password</label><input type="text" name="password" class="form-input" placeholder="Leave empty to keep current"></div>
                    </div>
                </div>
                <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection