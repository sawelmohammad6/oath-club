@extends('layouts.admin')

@section('title', 'Activity Details')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Activity Details</h2>
        <p class="text-gray-500 text-sm">Detailed pages with galleries for club activities - {{ $details->count() }} total</p>
    </div>
    <button onclick="openModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Detail Page</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Slug</th>
                    <th>Title</th>
                    <th>Activity</th>
                    <th>Images</th>
                    <th>Event Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $d)
                <tr>
                    <td class="font-mono text-sm text-gray-500">{{ $d->slug }}</td>
                    <td class="font-medium">{{ $d->title }}</td>
                    <td class="text-gray-500">{{ $d->activity->title ?? '—' }}</td>
                    <td class="text-gray-500">{{ $d->images->count() }} images</td>
                    <td class="text-gray-500 text-sm">{{ $d->event_date ? \Carbon\Carbon::parse($d->event_date)->format('d M Y') : '-' }}</td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            <button type="button" onclick="openModal({{ $d->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.activity-details.destroy', $d->id) }}" data-confirm-delete data-confirm-title="Delete this page?" data-confirm-text="All gallery images will also be removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-12 text-gray-400"><i class="fas fa-file-alt text-3xl mb-2"></i><p>No detail pages yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $details->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Detail Page</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="detailForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id" id="editId">
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="f_title" required class="form-input" placeholder="Page title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="f_slug" class="form-input" placeholder="Auto-generated from title">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to auto-generate</p>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link to Activity</label>
                        <select name="activity_id" id="f_activity_id" class="form-input">
                            <option value="">— None —</option>
                            @foreach($activities as $a)
                            <option value="{{ $a->id }}">{{ $a->title }} ({{ $a->title_en }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                        <input type="date" name="event_date" id="f_event_date" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="f_description" rows="5" class="form-input" placeholder="Detailed description..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gallery Images</label>
                    <input type="file" name="images[]" id="f_images" accept="image/*" multiple class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">You can select multiple images. Supported: jpeg, png, jpg, webp (max 5MB each)</p>
                </div>
                <div id="existingImages" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    <div id="imageGrid" class="grid grid-cols-4 gap-3"></div>
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const details = @json($details->items());

function openModal(id) {
    const data = id ? details.find(d => d.id === id) : null;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = data ? 'Edit Detail Page' : 'Add Detail Page';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('editId').value = data ? data.id : '';
    document.getElementById('f_title').value = data ? data.title : '';
    document.getElementById('f_slug').value = data ? data.slug : '';
    document.getElementById('f_activity_id').value = data ? (data.activity_id||'') : '';
    document.getElementById('f_event_date').value = data ? (data.event_date||'') : '';
    document.getElementById('f_description').value = data ? (data.description||'') : '';
    document.getElementById('f_images').value = '';

    const imgBox = document.getElementById('imageGrid');
    const imgSection = document.getElementById('existingImages');
    imgBox.innerHTML = '';

    if (data && data.images && data.images.length) {
        imgSection.classList.remove('hidden');
        data.images.forEach(img => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group';
            wrapper.innerHTML = `<img src="/storage/${img.image_path}" class="w-full h-24 object-cover rounded-lg border">` +
                `<button type="button" onclick="deleteImage(${img.id})" class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>`;
            imgBox.appendChild(wrapper);
        });
    } else {
        imgSection.classList.add('hidden');
    }

    document.getElementById('detailForm').action = data ? '/admin/activity-details/' + data.id : '{{ route("admin.activity-details.store") }}';
}

function closeModal() { document.getElementById('detailModal').classList.add('hidden'); }

const initialDetailEditId = Number(@json(request('edit')));
if (initialDetailEditId) {
    const openInitialDetailEdit = () => openModal(initialDetailEditId);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openInitialDetailEdit);
    } else {
        openInitialDetailEdit();
    }
}

function deleteImage(id) {
    Swal.fire({
        title: 'Delete this image?',
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('/admin/activity-details/image/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(() => { location.reload(); });
        }
    });
}
</script>
@endpush
@endsection
