@extends('layouts.admin')

@section('title', 'Application Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.applications') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-primary-600 transition font-medium">
        <i class="fas fa-arrow-left"></i> Back to Applications
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="admin-card p-6 md:p-8">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <img src="{{ $application->photo ? asset('storage/'.$application->photo) : 'https://ui-avatars.com/api/?name='.urlencode($application->full_name).'&background=16a34a&color=fff&size=80' }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-200">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $application->full_name }}</h2>
                <span class="badge badge-{{ $application->status }} mt-1 inline-block">{{ ucfirst($application->status) }}</span>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Father Name</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->father_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Mother Name</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->mother_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Date of Birth</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->date_of_birth ? $application->date_of_birth->format('d M Y') : 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Phone</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->phone }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Email</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Occupation</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->occupation ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Blood Group</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->blood_group ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Transaction ID</label>
                <p class="font-medium text-gray-800 mt-1 font-mono">{{ $application->transaction_id }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Address</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->address ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Applied On</label>
                <p class="font-medium text-gray-800 mt-1">{{ $application->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        @if($application->status === 'pending')
        <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
            <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}">
                @csrf
                <button class="btn-primary"><i class="fas fa-check mr-1"></i> Approve & Create Member</button>
            </form>
            <form method="POST" action="{{ route('admin.applications.reject', $application->id) }}">
                @csrf
                <button class="btn-danger"><i class="fas fa-times mr-1"></i> Reject</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection