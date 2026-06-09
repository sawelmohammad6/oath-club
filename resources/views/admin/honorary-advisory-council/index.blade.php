@extends('layouts.admin')

@section('title', 'Honorary Advisory Council')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Honorary Advisory Council Management</h2><p class="text-gray-500 text-sm">Honorary advisory council members</p></div>
    <button onclick="openHonoraryAdvisoryCouncilModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Member</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table w-full text-sm" id="honoraryAdvisoryCouncilTable">
            <thead>
                <tr>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase w-10"></th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Photo</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Name</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Position</th>
                    <th class="px-4 py-3.5 bg-gray-50 text-left font-semibold text-gray-600 text-xs uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="honoraryAdvisoryCouncilTableBody">
                @forelse($members as $idx => $c)
                <tr class="border-t border-gray-100 hover:bg-primary-50/50" data-id="{{ $c->id }}">
                    <td class="px-4 py-3.5 text-gray-400 text-sm cursor-grab handle"><i class="fas fa-grip-vertical"></i></td>
                    <td class="px-4 py-3.5"><img src="{{ $c->photo ? asset('storage/'.$c->photo) : 'https://ui-avatars.com/api/?name='.urlencode($c->name).'&background=16a34a&color=fff&size=40' }}" class="w-10 h-10 rounded-full object-cover"></td>
                    <td class="px-4 py-3.5 font-medium">{{ $c->name }}</td>
                    <td class="px-4 py-3.5 text-gray-500">{{ $c->position }}</td>
                    <td class="px-4 py-3.5">
                        <div class="flex gap-1.5">
                            <button type="button" onclick="editHonoraryAdvisoryCouncil({{ $c->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.honorary-advisory-council.destroy', $c->id) }}" data-confirm-delete data-confirm-title="Delete Honorary Advisory Council Member?" data-confirm-text="This honorary advisory council member will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-12 text-gray-400"><i class="fas fa-user-tie text-3xl mb-2"></i><p>No honorary advisory council members yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="honoraryAdvisoryCouncilModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeHonoraryAdvisoryCouncilModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="honoraryAdvisoryCouncilModalTitle">Add Honorary Advisory Council Member</h3>
            <button type="button" onclick="closeHonoraryAdvisoryCouncilModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="honoraryAdvisoryCouncilForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="honoraryAdvisoryCouncilMethod" value="POST">
            <input type="hidden" name="id" id="editHonoraryAdvisoryCouncilId">
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Name *</label><input type="text" name="name" id="hacName" required class="form-input"></div>
                <div><label class="block text-sm font-medium text-gray-700">Position *</label>
                    <input type="text" name="position" id="hacPos" required class="form-input">
                </div>
                <div class="grid md:grid-cols-[120px_1fr] gap-4 items-start">
                    <div id="honoraryAdvisoryCouncilPhotoPreview" class="w-24 h-24 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden text-gray-400">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Photo</label><input type="file" name="photo" id="hacPhoto" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer"></div>
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const honoraryAdvisoryCouncilMembers = @json($members->items());

function openHonoraryAdvisoryCouncilModal(data) {
    document.getElementById('honoraryAdvisoryCouncilModal').classList.remove('hidden');
    document.getElementById('honoraryAdvisoryCouncilModalTitle').textContent = data ? 'Edit Honorary Advisory Council Member' : 'Add Honorary Advisory Council Member';
    document.getElementById('honoraryAdvisoryCouncilMethod').value = data ? 'POST' : 'POST';
    document.getElementById('editHonoraryAdvisoryCouncilId').value = data ? data.id : '';
    document.getElementById('hacName').value = data ? data.name : '';
    document.getElementById('hacPos').value = data ? data.position : '';
    document.getElementById('hacPhoto').value = '';
    setHonoraryAdvisoryCouncilPhotoPreview(data && data.photo ? '{{ asset('storage') }}/' + data.photo : '');
    document.getElementById('honoraryAdvisoryCouncilForm').action = data ? '{{ url("admin/honorary-advisory-council") }}/' + data.id : '{{ route("admin.honorary-advisory-council.store") }}';
}

function editHonoraryAdvisoryCouncil(id) {
    const m = honoraryAdvisoryCouncilMembers.find(x => x.id === id);
    if (m) openHonoraryAdvisoryCouncilModal(m);
}

const initialHonoraryAdvisoryCouncilEditId = Number(@json(request('edit')));
if (initialHonoraryAdvisoryCouncilEditId) {
    const openInitialHonoraryAdvisoryCouncilEdit = () => editHonoraryAdvisoryCouncil(initialHonoraryAdvisoryCouncilEditId);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openInitialHonoraryAdvisoryCouncilEdit);
    } else {
        openInitialHonoraryAdvisoryCouncilEdit();
    }
}

function closeHonoraryAdvisoryCouncilModal() {
    document.getElementById('honoraryAdvisoryCouncilModal').classList.add('hidden');
}

function setHonoraryAdvisoryCouncilPhotoPreview(src) {
    const box = document.getElementById('honoraryAdvisoryCouncilPhotoPreview');
    box.innerHTML = src
        ? `<img src="${src}" alt="" class="w-full h-full object-cover">`
        : '<i class="fas fa-user-tie"></i>';
}

document.getElementById('hacPhoto')?.addEventListener('change', function () {
    const file = this.files && this.files[0];
    setHonoraryAdvisoryCouncilPhotoPreview(file ? URL.createObjectURL(file) : '');
});

(function() {
    const el = document.getElementById('honoraryAdvisoryCouncilTableBody');
    if (!el) return;
    Sortable.create(el, {
        handle: '.handle',
        animation: 150,
        onEnd: function() {
            const ids = [];
            el.querySelectorAll('tr').forEach(tr => {
                if (tr.dataset.id) ids.push(Number(tr.dataset.id));
            });
            fetch('{{ route("admin.honorary-advisory-council.reorder") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids })
            });
        }
    });
})();
</script>
@endpush
