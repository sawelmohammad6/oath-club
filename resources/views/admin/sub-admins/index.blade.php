@extends('layouts.admin')

@section('title', 'Sub Admin Management')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Sub Admin Management</h2>
        <p class="text-gray-500 text-sm">Manage sub-admin accounts - {{ $subAdmins->total() }} total</p>
    </div>
    <a href="{{ route('admin.sub-admins.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Create Sub Admin</a>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subAdmins as $subAdmin)
                <tr>
                    <td class="font-medium">{{ $subAdmin->name }}</td>
                    <td class="text-gray-500">{{ $subAdmin->email }}</td>
                    <td class="text-gray-500 text-sm">{{ $subAdmin->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            <a href="{{ route('admin.sub-admins.edit', $subAdmin->id) }}" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.sub-admins.destroy', $subAdmin->id) }}" data-confirm-delete data-confirm-title="Delete Sub Admin?" data-confirm-text="This sub-admin account will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-12 text-gray-400"><i class="fas fa-user-shield text-3xl mb-2"></i><p>No sub-admins yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $subAdmins->links() }}
        </div>
    </div>
</div>
@endsection
