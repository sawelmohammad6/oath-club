@extends('layouts.admin')

@section('title', 'Activities')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Activities Management</h2>
        <p class="text-gray-500 text-sm">Club activities and programs - {{ $activities->count() }} total</p>
    </div>
    <button onclick="openActivityModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Activity</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title(BN)</th>
                    <th>Title (EN)</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $a)
                <tr>
                    <td>
                        @if($a->image)
                        <button type="button" onclick='previewActivityImage(@json(asset('storage/'.$a->image)), @json($a->title))' class="block">
                            <img src="{{ asset('storage/'.$a->image) }}" alt="{{ $a->title }}" class="w-16 h-12 rounded-lg object-cover border border-gray-200 hover:opacity-80 transition">
                        </button>
                        @else
                        <div class="w-16 h-12 rounded-lg bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>
                        @endif
                    </td>
                    <td class="font-medium">{{ $a->title }}</td>
                    <td class="text-gray-500 text-sm">{{ $a->title_en ?? '-' }}</td>
                    <td class="text-gray-500 text-sm max-w-sm leading-relaxed">{{ \Illuminate\Support\Str::limit($a->description, 100) }}</td>
                    <td class="text-gray-500 text-sm">{{ $a->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            @if($a->image)
                            <button type="button" onclick='previewActivityImage(@json(asset('storage/'.$a->image)), @json($a->title))' class="action-btn bg-slate-100 hover:bg-slate-200 text-slate-600" title="Preview"><i class="fas fa-eye"></i></button>
                            @endif
                            <button type="button" onclick="editActivity({{ $a->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.activities.destroy', $a->id) }}" data-confirm-delete data-confirm-title="Delete Activity?" data-confirm-text="This activity will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-12 text-gray-400"><i class="fas fa-tasks text-3xl mb-2"></i><p>No activities yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $activities->links() }}
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="activityPreviewModal" class="fixed inset-0 z-50 hidden bg-black/70 flex items-center justify-center p-4" onclick="if(event.target===this)closeActivityPreview()">
    <div class="relative max-w-3xl w-full">
        <button type="button" onclick="closeActivityPreview()" class="absolute -top-10 right-0 text-white/80 hover:text-white text-xl"><i class="fas fa-times"></i></button>
        <img id="activityPreviewImage" src="" alt="" class="w-full max-h-[80vh] object-contain rounded-lg shadow-2xl bg-white">
        <p id="activityPreviewTitle" class="text-white text-center mt-3 text-lg font-medium"></p>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="activityModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeActivityModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="activityModalTitle">Add Activity</h3>
            <button type="button" onclick="closeActivityModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="activityForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="activityMethod" value="POST">
            <input type="hidden" name="id" id="editActivityId">
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Title (Bengali) <span class="text-red-500">*</span></label><input type="text" name="title" id="aTitle" required class="form-input"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Title (English)</label><input type="text" name="title_en" id="aTitleEn" class="form-input"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label><textarea name="description" id="aDesc" rows="4" required class="form-input"></textarea></div>
                <div class="grid md:grid-cols-[160px_1fr] gap-4 items-start">
                    <div id="activityImagePreview" class="h-28 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden text-gray-400">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="image" id="aImage" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Leave empty while editing to keep the current image.</p>
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save Activity</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const activities = @json($activities->items());

function openActivityModal(data) {
    document.getElementById('activityModal').classList.remove('hidden');
    document.getElementById('activityModalTitle').textContent = data ? 'Edit Activity' : 'Add Activity';
    document.getElementById('activityMethod').value = 'POST';
    document.getElementById('editActivityId').value = data ? data.id : '';
    document.getElementById('aTitle').value = data ? data.title : '';
    document.getElementById('aTitleEn').value = data ? (data.title_en||'') : '';
    document.getElementById('aDesc').value = data ? data.description : '';
    document.getElementById('aImage').value = '';
    setActivityImagePreview(data && data.image ? '{{ asset('storage') }}/' + data.image : '');
    document.getElementById('activityForm').action = data ? '{{ url("admin/activities") }}/' + data.id : '{{ route("admin.activities.store") }}';
}

function editActivity(id) {
    const a = activities.find(x => x.id === id);
    if (a) openActivityModal(a);
}

const initialActivityEditId = Number(@json(request('edit')));
if (initialActivityEditId) {
    const openInitialActivityEdit = () => editActivity(initialActivityEditId);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openInitialActivityEdit);
    } else {
        openInitialActivityEdit();
    }
}

function closeActivityModal() { document.getElementById('activityModal').classList.add('hidden'); }

function previewActivityImage(src, title) {
    document.getElementById('activityPreviewImage').src = src;
    document.getElementById('activityPreviewTitle').textContent = title || '';
    document.getElementById('activityPreviewModal').classList.remove('hidden');
}

function closeActivityPreview() {
    document.getElementById('activityPreviewModal').classList.add('hidden');
}

function setActivityImagePreview(src) {
    const box = document.getElementById('activityImagePreview');
    box.innerHTML = src
        ? `<img src="${src}" alt="" class="w-full h-full object-cover">`
        : '<i class="fas fa-image"></i>';
}

document.getElementById('aImage')?.addEventListener('change', function () {
    const file = this.files && this.files[0];
    setActivityImagePreview(file ? URL.createObjectURL(file) : '');
});
</script>
@endpush
