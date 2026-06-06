@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Gallery Management</h2>
        <p class="text-gray-500 text-sm mt-0.5">Club photo gallery — {{ $images->count() }} images</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.gallery.bulk-delete') }}" id="bulkDeleteForm" data-confirm-delete data-confirm-title="Delete Selected Images?" data-confirm-text="Selected gallery images will be permanently removed.">
            @csrf @method('DELETE')
            <input type="hidden" name="ids" id="bulkIds">
            <button type="submit" id="bulkDeleteBtn" disabled class="px-4 py-2 bg-red-100 text-red-600 rounded-xl text-sm font-semibold transition hover:bg-red-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-trash-alt mr-1"></i> Delete Selected (<span id="selectedCount">0</span>)
            </button>
        </form>
        <a href="{{ route('admin.gallery.import') }}" class="px-4 py-2 bg-blue-100 text-blue-600 rounded-xl text-sm font-semibold transition hover:bg-blue-200" onclick="return confirm('Import all images from assets/gallery-* into database?')">
            <i class="fas fa-download mr-1"></i> Auto Import
        </a>
        <button onclick="openGalleryModal()" class="btn-primary"><i class="fas fa-plus mr-1"></i> Add Image</button>
    </div>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;"><input type="checkbox" id="selectAll" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"></th>
                    <th style="width: 100px;">Preview</th>
                    <th style="min-width: 180px;">Image Name</th>
                    <th>Caption</th>
                    <th style="width: 140px;">Created Date</th>
                    <th class="text-center" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($images as $img)
                <tr>
                    <td class="text-center"><input type="checkbox" class="row-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500" value="{{ $img->id }}"></td>
                    <td>
                        <button type="button" onclick='previewImage(@json(asset('storage/'.$img->image)), @json($img->caption ?? ''))' class="block">
                            <img src="{{ asset('storage/'.$img->image) }}" class="w-14 h-14 rounded-lg object-cover border border-gray-200 hover:opacity-80 transition">
                        </button>
                    </td>
                    <td class="font-mono text-xs text-gray-500">{{ basename($img->image) }}</td>
                    <td>
                        <span class="caption-text" data-id="{{ $img->id }}">{{ $img->caption ?? '-' }}</span>
                        <button type="button" onclick="editGallery({{ $img->id }})" class="text-primary-600 hover:text-primary-700 ml-1 text-xs" title="Edit"><i class="fas fa-pen"></i></button>
                    </td>
                    <td class="text-gray-500 text-sm">{{ $img->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div class="flex gap-1.5 justify-center">
                            <button type="button" onclick='previewImage(@json(asset('storage/'.$img->image)), @json($img->caption ?? ''))' class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Preview"><i class="fas fa-eye"></i></button>
                            <button type="button" onclick="editGallery({{ $img->id }})" class="action-btn bg-primary-100 hover:bg-primary-200 text-primary-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $img->id) }}" data-confirm-delete data-confirm-title="Delete Gallery Image?" data-confirm-text="This image will be permanently removed from the gallery.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-16">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fas fa-images text-5xl text-gray-300"></i>
                            <p class="text-gray-400 text-lg font-medium">No images yet</p>
                            <p class="text-gray-400 text-sm">Upload images or use "Auto Import" to import from assets folder</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $images->links() }}
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 hidden bg-black/70 flex items-center justify-center p-4" onclick="if(event.target===this)closePreview()">
    <div class="relative max-w-3xl w-full">
        <button onclick="closePreview()" class="absolute -top-10 right-0 text-white/80 hover:text-white text-xl"><i class="fas fa-times"></i></button>
        <img id="previewImage" src="" class="w-full rounded-2xl shadow-2xl max-h-[80vh] object-contain">
        <p id="previewCaption" class="text-white text-center mt-3 text-lg font-medium"></p>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeGalleryModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-lg shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="galleryModalTitle">Upload Image</h3>
            <button type="button" onclick="closeGalleryModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" id="galleryForm">
            @csrf
            <div class="space-y-4">
                <div id="galleryImagePreview" class="h-44 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden text-gray-400">
                    <i class="fas fa-image text-2xl"></i>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image <span class="text-red-500">*</span></label>
                    <input type="file" name="image" id="galleryImageInput" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer hover:file:bg-primary-100">
                    <p id="galleryImageHint" class="text-xs text-gray-400 mt-2 hidden">Leave empty while editing to keep the current image.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                    <input type="text" name="caption" id="galleryCaptionInput" class="form-input" placeholder="Image caption">
                </div>
                <button type="submit" class="btn-primary w-full" id="gallerySubmitBtn"><i class="fas fa-upload"></i> Upload</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const galleryImages = @json($images);

function openGalleryModal(data) {
    const form = document.getElementById('galleryForm');
    const imageInput = document.getElementById('galleryImageInput');
    document.getElementById('galleryModal').classList.remove('hidden');
    document.getElementById('galleryModalTitle').textContent = data ? 'Edit Gallery Image' : 'Upload Image';
    document.getElementById('galleryCaptionInput').value = data ? (data.caption || '') : '';
    document.getElementById('gallerySubmitBtn').innerHTML = data ? '<i class="fas fa-save"></i> Save Image' : '<i class="fas fa-upload"></i> Upload';
    document.getElementById('galleryImageHint').classList.toggle('hidden', !data);
    form.action = data ? '{{ url("admin/gallery") }}/' + data.id : '{{ route("admin.gallery.store") }}';
    imageInput.required = !data;
    imageInput.value = '';
    setGalleryImagePreview(data && data.image ? '{{ asset('storage') }}/' + data.image : '');
}

function editGallery(id) {
    const image = galleryImages.find(item => item.id === id);
    if (image) openGalleryModal(image);
}

function closeGalleryModal() { document.getElementById('galleryModal').classList.add('hidden'); }

function previewImage(src, caption) {
    document.getElementById('previewImage').src = src;
    document.getElementById('previewCaption').textContent = caption || '';
    document.getElementById('previewModal').classList.remove('hidden');
}
function closePreview() { document.getElementById('previewModal').classList.add('hidden'); }

async function editCaption(id) {
    const currentEl = document.querySelector(`.caption-text[data-id="${id}"]`);
    const current = currentEl ? currentEl.textContent : '';
    const { value } = await Swal.fire({
        title: 'Edit Caption',
        input: 'text',
        inputValue: current === '-' ? '' : current,
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
        inputValidator: (v) => { if (v === undefined || v === null) return; }
    });
    if (value !== undefined && value !== null) {
        const form = new FormData();
        form.append('_token', '{{ csrf_token() }}');
        form.append('caption', value || '');
        try {
            const res = await fetch('{{ url("admin/gallery") }}/' + id + '/caption', { method: 'POST', body: form });
            if (res.ok) window.location.reload();
            else Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update caption' });
        } catch(e) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update caption' });
        }
    }
}

document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    updateBulkDelete();
});

document.querySelectorAll('.row-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkDelete);
});

function updateBulkDelete() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const count = checked.length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('bulkDeleteBtn').disabled = count === 0;
}

document.getElementById('bulkDeleteForm')?.addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const ids = Array.from(checked).map(cb => cb.value);
    if (ids.length === 0) { e.preventDefault(); return; }
    document.getElementById('bulkIds').value = JSON.stringify(ids);
});

function setGalleryImagePreview(src) {
    const box = document.getElementById('galleryImagePreview');
    box.innerHTML = src
        ? `<img src="${src}" alt="" class="w-full h-full object-cover">`
        : '<i class="fas fa-image text-2xl"></i>';
}

document.getElementById('galleryImageInput')?.addEventListener('change', function () {
    const file = this.files && this.files[0];
    setGalleryImagePreview(file ? URL.createObjectURL(file) : '');
});
</script>
@endpush
