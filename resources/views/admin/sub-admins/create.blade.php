@extends('layouts.admin')

@section('title', 'Create Sub Admin')

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.sub-admins') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left text-xl"></i></a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Create Sub Admin</h2>
        <p class="text-gray-500 text-sm">Add a new sub-admin account</p>
    </div>
</div>

<div class="admin-card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('admin.sub-admins.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input @error('name') border-red-400 @enderror" placeholder="Sub admin name">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input @error('email') border-red-400 @enderror" placeholder="email@example.com">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="form-input @error('password') border-red-400 @enderror" placeholder="Min 6 characters">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Create Sub Admin</button>
                <a href="{{ route('admin.sub-admins') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition inline-flex items-center gap-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
