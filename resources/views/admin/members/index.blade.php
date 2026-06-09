@extends('layouts.admin')

@section('title', 'Members')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Member Management</h2><p class="text-gray-500 text-sm">Approved club members - {{ $members->count() }} total</p></div>
    <button onclick="openMemberModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Member</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
                    <th style="width: 80px;">Photo</th>
                    <th style="min-width: 250px;">Name</th>
                    <th>ID</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Blood</th>
                    <th class="text-center" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody id="membersTableBody">
                @forelse($members as $m)
                <tr data-id="{{ $m->id }}">
                    <td class="text-gray-400 text-sm cursor-grab handle"><i class="fas fa-grip-vertical"></i></td>
                    <td><img src="{{ $m->photo ? asset('storage/'.$m->photo) : 'https://ui-avatars.com/api/?name='.urlencode($m->name ?: ($m->application->full_name ?? 'Member')).'&background=16a34a&color=fff&size=40' }}" class="w-10 h-10 rounded-full object-cover border border-gray-200"></td>
                    <td class="font-medium">{{ $m->name ?: ($m->application->full_name ?? '') }}</td>
                    <td class="text-gray-500 font-mono text-sm">{{ $m->member_id }}</td>
                    <td class="text-gray-500">{{ $m->phone }}</td>
                    <td class="text-gray-500">{{ $m->position ?? 'Member' }}</td>
                    <td class="text-gray-500 text-sm">{{ $m->blood_group ?? '-' }}</td>
                    <td class="text-center">
                        <div class="flex gap-1.5 justify-center">
                            <button type="button" onclick="editMember({{ $m->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.members.destroy', $m->id) }}" data-confirm-delete data-confirm-title="Delete Member?" data-confirm-text="This member record will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-12 text-gray-400"><i class="fas fa-users text-3xl mb-2"></i><p>No members yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="memberModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeMemberModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="memberModalTitle">Add Member</h3>
            <button type="button" onclick="closeMemberModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="memberForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="member_id" id="editMemberId">
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label><input type="text" name="full_name" id="mName" required class="form-input"></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Father Name</label><input type="text" name="father_name" id="mFather" class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Mother Name</label><input type="text" name="mother_name" id="mMother" class="form-input"></div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Date of Birth</label><input type="date" name="date_of_birth" id="mDob" class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Phone <span class="text-red-500">*</span></label><input type="text" name="phone" id="mPhone" required class="form-input"></div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Email</label><input type="email" name="email" id="mEmail" class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700">Occupation</label><input type="text" name="occupation" id="mOcc" class="form-input"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700">Address</label><input type="text" name="address" id="mAddr" class="form-input"></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700">Blood Group</label>
                        <select name="blood_group" id="mBlood" class="form-input">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}">{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700">Position</label><input type="text" name="position" id="mPos" class="form-input" placeholder="e.g. General Member"></div>
                </div>
                <div class="grid md:grid-cols-[120px_1fr] gap-4 items-start">
                    <div id="memberPhotoPreview" class="w-24 h-24 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden text-gray-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Photo</label><input type="file" name="photo" id="mPhoto" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer"></div>
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save Member</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const members = @json($members->items());

function openMemberModal(data) {
    document.getElementById('memberModal').classList.remove('hidden');
    document.getElementById('memberModalTitle').textContent = data ? 'Edit Member' : 'Add Member';
    document.getElementById('editMemberId').value = data ? data.id : '';
    document.getElementById('mName').value = data ? data.full_name : '';
    document.getElementById('mFather').value = data ? (data.father_name||'') : '';
    document.getElementById('mMother').value = data ? (data.mother_name||'') : '';
    document.getElementById('mDob').value = data ? (data.date_of_birth||'') : '';
    document.getElementById('mPhone').value = data ? data.phone : '';
    document.getElementById('mEmail').value = data ? (data.email||'') : '';
    document.getElementById('mOcc').value = data ? (data.occupation||'') : '';
    document.getElementById('mAddr').value = data ? (data.address||'') : '';
    document.getElementById('mBlood').value = data ? (data.blood_group||'') : '';
    document.getElementById('mPos').value = data ? (data.position||'') : '';
    document.getElementById('mPhoto').value = '';
    setMemberPhotoPreview(data && data.photo ? '{{ asset('storage') }}/' + data.photo : '');
    document.getElementById('memberForm').action = data ? '{{ url("admin/members") }}/' + data.id : '{{ route("admin.members.store") }}';
}

function editMember(id) {
    const m = members.find(x => x.id === id);
    if (m) openMemberModal(m);
}

const initialMemberEditId = Number(@json(request('edit')));
if (initialMemberEditId) {
    const openInitialMemberEdit = () => editMember(initialMemberEditId);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openInitialMemberEdit);
    } else {
        openInitialMemberEdit();
    }
}

function closeMemberModal() {
    document.getElementById('memberModal').classList.add('hidden');
}

function setMemberPhotoPreview(src) {
    const box = document.getElementById('memberPhotoPreview');
    box.innerHTML = src
        ? `<img src="${src}" alt="" class="w-full h-full object-cover">`
        : '<i class="fas fa-user"></i>';
}

document.getElementById('mPhoto')?.addEventListener('change', function () {
    const file = this.files && this.files[0];
    setMemberPhotoPreview(file ? URL.createObjectURL(file) : '');
});

(function() {
    const el = document.getElementById('membersTableBody');
    if (!el) return;
    Sortable.create(el, {
        handle: '.handle',
        animation: 150,
        onEnd: function() {
            const ids = [];
            el.querySelectorAll('tr').forEach(tr => { if (tr.dataset.id) ids.push(Number(tr.dataset.id)); });
            fetch('{{ route("admin.members.reorder") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids })
            });
        }
    });
})();
</script>
@endpush
