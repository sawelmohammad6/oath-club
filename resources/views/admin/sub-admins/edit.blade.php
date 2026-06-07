@extends('layouts.admin')

@section('title', 'Edit Sub Admin')

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.sub-admins') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left text-xl"></i></a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Sub Admin</h2>
        <p class="text-gray-500 text-sm">Update sub-admin account details</p>
    </div>
</div>

<div class="admin-card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('admin.sub-admins.update', $subAdmin->id) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $subAdmin->name) }}" required class="form-input @error('name') border-red-400 @enderror" placeholder="Sub admin name">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $subAdmin->email) }}" required class="form-input @error('email') border-red-400 @enderror" placeholder="email@example.com">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="form-input @error('password') border-red-400 @enderror" placeholder="Leave blank to keep current password">
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep the current password.</p>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Sub Admin</button>
                <a href="{{ route('admin.sub-admins') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition inline-flex items-center gap-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
