@extends('layouts.admin')

@section('title', 'Banner Settings')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Banner Management</h2>
        <p class="text-gray-500 text-sm">Homepage slider banners - {{ $banners->count() }} total</p>
    </div>
    <button type="button" onclick="openBannerModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Banner</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Link</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>
                        <button type="button" onclick='previewBanner(@json(asset('storage/'.$banner->image)), @json($banner->title ?? 'Banner'))' class="block">
                            <img src="{{ asset('storage/'.$banner->image) }}" alt="{{ $banner->title ?? 'Banner' }}" class="w-20 h-12 rounded-lg object-cover border border-gray-200 hover:opacity-80 transition">
                        </button>
                    </td>
                    <td class="font-medium">{{ $banner->title ?? '-' }}</td>
                    <td class="text-gray-500 text-sm max-w-xs">{{ \Illuminate\Support\Str::limit($banner->subtitle, 80) ?: '-' }}</td>
                    <td class="text-gray-500 text-sm">
                        @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="text-primary-600 hover:underline">{{ \Illuminate\Support\Str::limit($banner->link, 28) }}</a>
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-gray-500">{{ $banner->sort_order }}</td>
                    <td>
                        <span class="badge {{ $banner->is_active ? 'badge-approved' : 'badge-rejected' }}">{{ $banner->is_active ? 'Active' : 'Hidden' }}</span>
                    </td>
                    <td class="text-gray-500 text-sm">{{ $banner->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            <button type="button" onclick='previewBanner(@json(asset('storage/'.$banner->image)), @json($banner->title ?? 'Banner'))' class="action-btn bg-slate-100 hover:bg-slate-200 text-slate-600" title="Preview"><i class="fas fa-eye"></i></button>
                            <button type="button" onclick="editBanner({{ $banner->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.settings.banners.destroy', $banner->id) }}" data-confirm-delete data-confirm-title="Delete Banner?" data-confirm-text="This banner image and record will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-14 text-gray-400">
                        <i class="fas fa-images text-4xl mb-3"></i>
                        <p class="font-medium">No banners yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Preview Modal -->
<div id="bannerPreviewModal" class="fixed inset-0 z-50 hidden bg-black/70 flex items-center justify-center p-4" onclick="if(event.target===this)closeBannerPreview()">
    <div class="relative max-w-4xl w-full">
        <button type="button" onclick="closeBannerPreview()" class="absolute -top-10 right-0 text-white/80 hover:text-white text-xl"><i class="fas fa-times"></i></button>
        <img id="bannerPreviewImage" src="" alt="" class="w-full max-h-[80vh] object-contain rounded-lg shadow-2xl bg-white">
        <p id="bannerPreviewTitle" class="text-white text-center mt-3 text-lg font-medium"></p>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="bannerModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeBannerModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="bannerModalTitle">Add Banner</h3>
            <button type="button" onclick="closeBannerModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="bannerForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div id="bannerImagePreview" class="h-48 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden text-gray-400">
                    <i class="fas fa-image text-2xl"></i>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="bTitle" class="form-input" placeholder="Banner title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="bOrder" min="0" class="form-input" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <textarea name="subtitle" id="bSubtitle" rows="3" class="form-input" placeholder="Optional slider subtitle"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                    <input type="url" name="link" id="bLink" class="form-input" placeholder="https://example.com">
                </div>
                <div class="grid md:grid-cols-[1fr_auto] gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image <span class="text-red-500">*</span></label>
                        <input type="file" name="image" id="bImage" accept="image/*" required class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer">
                        <p id="bannerImageHint" class="text-xs text-gray-400 mt-2 hidden">Leave empty while editing to keep the current image.</p>
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 pb-2">
                        <input type="checkbox" name="is_active" id="bActive" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" checked>
                        Active
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full" id="bannerSubmitBtn"><i class="fas fa-save"></i> Save Banner</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const banners = @json($banners);

function openBannerModal(data) {
    const form = document.getElementById('bannerForm');
    const imageInput = document.getElementById('bImage');
    document.getElementById('bannerModal').classList.remove('hidden');
    document.getElementById('bannerModalTitle').textContent = data ? 'Edit Banner' : 'Add Banner';
    document.getElementById('bTitle').value = data ? (data.title || '') : '';
    document.getElementById('bSubtitle').value = data ? (data.subtitle || '') : '';
    document.getElementById('bLink').value = data ? (data.link || '') : '';
    document.getElementById('bOrder').value = data ? (data.sort_order || 0) : '';
    document.getElementById('bActive').checked = data ? Boolean(data.is_active) : true;
    document.getElementById('bannerSubmitBtn').innerHTML = data ? '<i class="fas fa-save"></i> Save Banner' : '<i class="fas fa-plus"></i> Add Banner';
    document.getElementById('bannerImageHint').classList.toggle('hidden', !data);
    imageInput.required = !data;
    imageInput.value = '';
    form.action = data ? '{{ url("admin/settings/banners") }}/' + data.id : '{{ route("admin.settings.banners.store") }}';
    setBannerImagePreview(data && data.image ? '{{ asset('storage') }}/' + data.image : '');
}

function editBanner(id) {
    const banner = banners.find(item => item.id === id);
    if (banner) openBannerModal(banner);
}

function closeBannerModal() {
    document.getElementById('bannerModal').classList.add('hidden');
}

function previewBanner(src, title) {
    document.getElementById('bannerPreviewImage').src = src;
    document.getElementById('bannerPreviewTitle').textContent = title || '';
    document.getElementById('bannerPreviewModal').classList.remove('hidden');
}

function closeBannerPreview() {
    document.getElementById('bannerPreviewModal').classList.add('hidden');
}

function setBannerImagePreview(src) {
    const box = document.getElementById('bannerImagePreview');
    box.innerHTML = src
        ? `<img src="${src}" alt="" class="w-full h-full object-cover">`
        : '<i class="fas fa-image text-2xl"></i>';
}

document.getElementById('bImage')?.addEventListener('change', function () {
    const file = this.files && this.files[0];
    setBannerImagePreview(file ? URL.createObjectURL(file) : '');
});
</script>
@endpush
@endsection
