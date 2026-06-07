@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Oath Club management overview</p>
    </div>
    <span class="text-xs text-gray-500 bg-white border border-gray-200 px-3 py-1.5 rounded-lg">{{ now()->format('l, M d, Y') }}</span>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5">
    <a href="{{ route('admin.applications') }}" class="stat-card block bg-gradient-to-br from-blue-600 to-blue-700">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-file-invoice text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Total</span></div>
        <div class="text-3xl font-bold">{{ $stats['total_applications'] }}</div>
        <div class="text-sm opacity-80 mt-1">Applications</div>
    </a>
    <a href="{{ route('admin.applications') }}" class="stat-card block bg-gradient-to-br from-yellow-500 to-yellow-600">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-clock text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Pending</span></div>
        <div class="text-3xl font-bold">{{ $stats['pending_applications'] }}</div>
        <div class="text-sm opacity-80 mt-1">Applications</div>
    </a>
    <a href="{{ route('admin.members') }}" class="stat-card block bg-gradient-to-br from-green-600 to-green-700">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-users text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Approved</span></div>
        <div class="text-3xl font-bold">{{ $stats['approved_members'] }}</div>
        <div class="text-sm opacity-80 mt-1">Members</div>
    </a>
    <a href="{{ route('admin.committee') }}" class="stat-card block bg-gradient-to-br from-slate-700 to-slate-800">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-user-tie text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Team</span></div>
        <div class="text-3xl font-bold">{{ $stats['committee_members'] }}</div>
        <div class="text-sm opacity-80 mt-1">Committee Members</div>
    </a>
    <a href="{{ route('admin.gallery') }}" class="stat-card block bg-gradient-to-br from-rose-600 to-rose-700">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-images text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Media</span></div>
        <div class="text-3xl font-bold">{{ $stats['gallery_images'] }}</div>
        <div class="text-sm opacity-80 mt-1">Gallery Images</div>
    </a>
    <a href="{{ route('admin.activities') }}" class="stat-card block bg-gradient-to-br from-indigo-600 to-indigo-700">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-tasks text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Content</span></div>
        <div class="text-3xl font-bold">{{ $stats['activities'] }}</div>
        <div class="text-sm opacity-80 mt-1">Activities</div>
    </a>
    <a href="{{ route('admin.settings.banners') }}" class="stat-card block bg-gradient-to-br from-cyan-600 to-cyan-700">
        <div class="flex items-center justify-between mb-4"><i class="fas fa-panorama text-2xl opacity-60"></i><span class="text-xs bg-white/20 px-2 py-1 rounded">Hero</span></div>
        <div class="text-3xl font-bold">{{ $stats['banners'] }}</div>
        <div class="text-sm opacity-80 mt-1">Banners</div>
    </a>
    <a href="{{ route('admin.applications') }}" class="admin-card p-5 hover:shadow-lg transition flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"><i class="fas fa-inbox text-yellow-600 text-xl"></i></div>
        <div><h3 class="font-bold text-gray-800">Review Queue</h3><p class="text-sm text-gray-500">{{ $stats['pending_applications'] }} pending applications</p></div>
    </a>
</div>

<div class="grid md:grid-cols-3 gap-5 mt-8">
    <a href="{{ route('admin.members') }}" class="admin-card p-5 hover:shadow-lg transition flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-users text-green-600 text-xl"></i></div>
        <div><h3 class="font-bold text-gray-800">Members</h3><p class="text-sm text-gray-500">Create, edit, or remove members</p></div>
    </a>
    <a href="{{ route('admin.gallery') }}" class="admin-card p-5 hover:shadow-lg transition flex items-center gap-4">
        <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center"><i class="fas fa-images text-rose-600 text-xl"></i></div>
        <div><h3 class="font-bold text-gray-800">Gallery</h3><p class="text-sm text-gray-500">Manage public gallery content</p></div>
    </a>
    <a href="{{ route('admin.settings.banners') }}" class="admin-card p-5 hover:shadow-lg transition flex items-center gap-4">
        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center"><i class="fas fa-panorama text-cyan-600 text-xl"></i></div>
        <div><h3 class="font-bold text-gray-800">Banners</h3><p class="text-sm text-gray-500">Control homepage slider images</p></div>
    </a>
</div>

<div class="mt-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Edit Shortcuts</h2>
            <p class="text-sm text-gray-500">Open existing management edit forms from the dashboard</p>
        </div>
    </div>

    <div class="grid xl:grid-cols-2 gap-5">
        @foreach($dashboardEditSections as $section)
        <div class="admin-card">
            <div class="px-4 py-3.5 border-b border-gray-100 flex items-center justify-between gap-3">
                <h3 class="font-bold text-gray-800">{{ $section['title'] }}</h3>
                <a href="{{ $section['manage_url'] }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Manage</a>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <tbody>
                        @forelse($section['items'] as $item)
                        <tr>
                            <td>
                                <div class="font-medium text-gray-800">{{ $item['title'] }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $item['meta'] ?: 'ID available on edit page' }}</div>
                            </td>
                            <td class="text-right" style="width: 84px;">
                                @if($item['editable'])
                                <a href="{{ $item['edit_url'] }}" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></a>
                                @else
                                <a href="{{ $section['manage_url'] }}" class="action-btn bg-gray-100 hover:bg-gray-200 text-gray-500" title="Open"><i class="fas fa-arrow-right"></i></a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center py-8 text-gray-400">{{ $section['empty'] }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
