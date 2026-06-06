@extends('layouts.admin')

@section('title', 'Applications')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Applications</h2><p class="text-gray-500 text-sm">Manage membership applications — {{ $applications->count() }} total</p></div>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>TrxID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <img src="{{ $app->photo ? asset('storage/'.$app->photo) : 'https://ui-avatars.com/api/?name='.urlencode($app->full_name).'&background=16a34a&color=fff&size=40' }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                    </td>
                    <td class="font-medium">{{ $app->full_name }}</td>
                    <td class="text-gray-500">{{ $app->phone }}</td>
                    <td class="text-gray-500 font-mono text-xs">{{ $app->transaction_id }}</td>
                    <td class="text-gray-500 text-sm">{{ $app->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
                    </td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            <a href="{{ route('admin.applications.show', $app->id) }}" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600"><i class="fas fa-eye"></i></a>
                            @if($app->status === 'pending')
                            <form method="POST" action="{{ route('admin.applications.approve', $app->id) }}" class="inline" onsubmit="return confirm('Approve this application and create member record?')">
                                @csrf
                                <button class="action-btn bg-green-100 hover:bg-green-200 text-green-600"><i class="fas fa-check"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.applications.reject', $app->id) }}" class="inline">
                                @csrf
                                <button class="action-btn bg-yellow-100 hover:bg-yellow-200 text-yellow-600"><i class="fas fa-times"></i></button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.applications.destroy', $app->id) }}" class="inline" onsubmit="return confirm('Delete this application permanently?')">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-gray-100 hover:bg-gray-200 text-gray-600"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-gray-400"><i class="fas fa-inbox text-3xl mb-2"></i><p>No applications yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection