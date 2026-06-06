@extends('layouts.admin')

@section('title', 'Blood Donors')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Blood Donors</h2>
        <p class="text-gray-500 text-sm">Registered blood donors - {{ $donors->count() }} total</p>
    </div>
    <button onclick="openBloodDonorModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Donor</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Blood Group</th>
                    <th>Contact Number</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donors as $donor)
                <tr>
                    <td class="font-medium">{{ $donor->name }}</td>
                    <td><span class="badge bg-red-100 text-red-600">{{ $donor->blood_group }}</span></td>
                    <td class="text-gray-500">{{ $donor->contact_number }}</td>
                    <td class="text-gray-500 text-sm">{{ $donor->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            <button type="button" onclick="editBloodDonor({{ $donor->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.blood-donors.destroy', $donor->id) }}" data-confirm-delete data-confirm-title="Delete Blood Donor?" data-confirm-text="This blood donor record will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-12 text-gray-400"><i class="fas fa-tint text-3xl mb-2"></i><p>No blood donors yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $donors->links() }}
        </div>
    </div>
</div>

<div id="bloodDonorModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeBloodDonorModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="bloodDonorModalTitle">Add Donor</h3>
            <button type="button" onclick="closeBloodDonorModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="bloodDonorForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" id="editBloodDonorId">
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" id="bdName" required class="form-input"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Blood Group <span class="text-red-500">*</span></label>
                    <select name="blood_group" id="bdBloodGroup" required class="form-input">
                        <option value="">Select Blood Group</option>
                        @foreach($bloodGroups as $group)
                        <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label><input type="text" name="contact_number" id="bdContactNumber" required class="form-input"></div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save Donor</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const bloodDonors = @json($donors);

function openBloodDonorModal(data) {
    document.getElementById('bloodDonorModal').classList.remove('hidden');
    document.getElementById('bloodDonorModalTitle').textContent = data ? 'Edit Donor' : 'Add Donor';
    document.getElementById('editBloodDonorId').value = data ? data.id : '';
    document.getElementById('bdName').value = data ? data.name : '';
    document.getElementById('bdBloodGroup').value = data ? data.blood_group : '';
    document.getElementById('bdContactNumber').value = data ? data.contact_number : '';
    document.getElementById('bloodDonorForm').action = data ? '{{ url("admin/blood-donors") }}/' + data.id : '{{ route("admin.blood-donors.store") }}';
}

function editBloodDonor(id) {
    const donor = bloodDonors.find(item => item.id === id);
    if (donor) openBloodDonorModal(donor);
}

function closeBloodDonorModal() {
    document.getElementById('bloodDonorModal').classList.add('hidden');
}
</script>
@endpush
@endsection
