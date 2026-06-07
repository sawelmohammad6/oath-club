@extends('layouts.admin')

@section('title', 'Committee')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Committee Management</h2><p class="text-gray-500 text-sm">Executive committee members</p></div>
    <button onclick="openCommitteeModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Member</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">#</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Photo</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Name</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Position</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="committeeTableBody">
                @forelse($members as $idx => $c)
                <tr class="border-t border-gray-100 hover:bg-primary-50/50" data-id="{{ $c->id }}">
                    <td class="px-4 py-3.5 text-gray-400 text-sm">{{ $idx + 1 }}</td>
                    <td class="px-4 py-3.5"><img src="{{ $c->photo ? asset('storage/'.$c->photo) : 'https://ui-avatars.com/api/?name='.urlencode($c->name).'&background=16a34a&color=fff&size=40' }}" class="w-10 h-10 rounded-full object-cover"></td>
                    <td class="px-4 py-3.5 font-medium">{{ $c->name }}</td>
                    <td class="px-4 py-3.5 text-gray-500">{{ $c->position }}</td>
                    <td class="px-4 py-3.5">
                        <div class="flex gap-1.5">
                            <button type="button" onclick="editCommittee({{ $c->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.committee.destroy', $c->id) }}" data-confirm-delete data-confirm-title="Delete Committee Member?" data-confirm-text="This committee member will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-12 text-gray-400"><i class="fas fa-user-tie text-3xl mb-2"></i><p>No committee members yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="committeeModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeCommitteeModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="committeeModalTitle">Add Committee Member</h3>
            <button type="button" onclick="closeCommitteeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="committeeForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="committeeMethod" value="POST">
            <input type="hidden" name="id" id="editCommitteeId">
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Name *</label><input type="text" name="name" id="cName" required class="form-input"></div>
                <div><label class="block text-sm font-medium text-gray-700">Position *</label>
                    <input type="text" name="position" id="cPos" required class="form-input">
                </div>
                <div class="grid md:grid-cols-[120px_1fr] gap-4 items-start">
                    <div id="committeePhotoPreview" class="w-24 h-24 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden text-gray-400">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Photo</label><input type="file" name="photo" id="cPhoto" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer"></div>
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const committeeMembers = @json($members->items());

function openCommitteeModal(data) {
    document.getElementById('committeeModal').classList.remove('hidden');
    document.getElementById('committeeModalTitle').textContent = data ? 'Edit Committee Member' : 'Add Committee Member';
    document.getElementById('committeeMethod').value = data ? 'POST' : 'POST';
    document.getElementById('editCommitteeId').value = data ? data.id : '';
    document.getElementById('cName').value = data ? data.name : '';
    document.getElementById('cPos').value = data ? data.position : '';
    document.getElementById('cPhoto').value = '';
    setCommitteePhotoPreview(data && data.photo ? '{{ asset('storage') }}/' + data.photo : '');
    document.getElementById('committeeForm').action = data ? '{{ url("admin/committee") }}/' + data.id : '{{ route("admin.committee.store") }}';
}

function editCommittee(id) {
    const m = committeeMembers.find(x => x.id === id);
    if (m) openCommitteeModal(m);
}

const initialCommitteeEditId = Number(@json(request('edit')));
if (initialCommitteeEditId) {
    const openInitialCommitteeEdit = () => editCommittee(initialCommitteeEditId);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openInitialCommitteeEdit);
    } else {
        openInitialCommitteeEdit();
    }
}

function closeCommitteeModal() {
    document.getElementById('committeeModal').classList.add('hidden');
}

function setCommitteePhotoPreview(src) {
    const box = document.getElementById('committeePhotoPreview');
    box.innerHTML = src
        ? `<img src="${src}" alt="" class="w-full h-full object-cover">`
        : '<i class="fas fa-user-tie"></i>';
}

document.getElementById('cPhoto')?.addEventListener('change', function () {
    const file = this.files && this.files[0];
    setCommitteePhotoPreview(file ? URL.createObjectURL(file) : '');
});
</script>
@endpush
