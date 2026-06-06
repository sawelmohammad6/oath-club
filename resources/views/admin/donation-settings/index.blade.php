@extends('layouts.admin')

@section('title', 'Donation Settings')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Donation Settings</h2>
        <p class="text-gray-500 text-sm">Manage donation receiving accounts - {{ $donationSettings->count() }} total</p>
    </div>
    <button onclick="openModal()" class="btn-primary"><i class="fas fa-plus"></i> Add Account</button>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>bKash</th>
                    <th>Nagad</th>
                    <th>Bank</th>
                    <th>Account Holder</th>
                    <th>Account Number</th>
                    <th>Branch</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donationSettings as $d)
                <tr>
                    <td class="font-medium">{{ $d->bkash_number ?: '-' }}</td>
                    <td class="font-medium">{{ $d->nagad_number ?: '-' }}</td>
                    <td class="text-gray-500">{{ $d->bank_name ?: '-' }}</td>
                    <td class="text-gray-500">{{ $d->account_name ?: '-' }}</td>
                    <td class="text-gray-500">{{ $d->account_number ?: '-' }}</td>
                    <td class="text-gray-500">{{ $d->branch_name ?: '-' }}</td>
                    <td class="text-right">
                        <div class="flex gap-1.5 justify-end">
                            <button type="button" onclick="editDonation({{ $d->id }})" class="action-btn bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('admin.donation-settings.destroy', $d->id) }}" data-confirm-delete data-confirm-title="Delete this account?" data-confirm-text="This donation account will be permanently removed.">
                                @csrf @method('DELETE')
                                <button class="action-btn bg-red-100 hover:bg-red-200 text-red-600" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-gray-400"><i class="fas fa-hand-holding-heart text-3xl mb-2"></i><p>No donation accounts yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="donationModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeModal()">
    <div class="modal-panel bg-white p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Donation Account</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="donationForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id" id="editId">
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">bKash Number</label><input type="text" name="bkash_number" id="d_bKash" class="form-input" placeholder="017XXXXXXXX"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nagad Number</label><input type="text" name="nagad_number" id="d_Nagad" class="form-input" placeholder="017XXXXXXXX"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label><input type="text" name="bank_name" id="d_Bank" class="form-input" placeholder="e.g. Dutch-Bangla Bank Ltd."></div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label><input type="text" name="account_name" id="d_AcName" class="form-input" placeholder="Full name as per bank"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label><input type="text" name="account_number" id="d_AcNum" class="form-input" placeholder="Bank account number"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Branch Name</label><input type="text" name="branch_name" id="d_Branch" class="form-input" placeholder="Branch name"></div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-save"></i> Save Account</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const donations = @json($donationSettings);

function openModal(data) {
    document.getElementById('donationModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = data ? 'Edit Donation Account' : 'Add Donation Account';
    document.getElementById('formMethod').value = data ? 'POST' : 'POST';
    document.getElementById('editId').value = data ? data.id : '';
    document.getElementById('d_bKash').value = data ? (data.bkash_number||'') : '';
    document.getElementById('d_Nagad').value = data ? (data.nagad_number||'') : '';
    document.getElementById('d_Bank').value = data ? (data.bank_name||'') : '';
    document.getElementById('d_AcName').value = data ? (data.account_name||'') : '';
    document.getElementById('d_AcNum').value = data ? (data.account_number||'') : '';
    document.getElementById('d_Branch').value = data ? (data.branch_name||'') : '';
    document.getElementById('donationForm').action = data ? '{{ url("admin/donation-settings") }}/' + data.id : '{{ route("admin.donation-settings.store") }}';
}

function editDonation(id) {
    const d = donations.find(x => x.id === id);
    if (d) openModal(d);
}

function closeModal() { document.getElementById('donationModal').classList.add('hidden'); }
</script>
@endpush
@endsection
