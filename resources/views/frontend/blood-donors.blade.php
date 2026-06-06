@extends('layouts.app')

@section('content')
<section class="section-padding bg-gray-50 min-h-screen pt-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-red-100 text-red-600 rounded-full text-sm font-semibold mb-4">Blood Donors</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Blood Donor List</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Find donors by blood group and contact them when support is needed.</p>
        </div>

        <div class="max-w-sm mx-auto mb-8" data-aos="fade-up">
            <label for="bloodGroupFilter" class="block text-sm font-semibold text-gray-700 mb-2">Filter by Blood Group</label>
            <select id="bloodGroupFilter" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 bg-white shadow-sm">
                <option value="">All Blood Groups</option>
                @foreach($bloodGroups as $group)
                <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500">
                            <th class="px-5 py-4 font-bold">Name</th>
                            <th class="px-5 py-4 font-bold">Blood Group</th>
                            <th class="px-5 py-4 font-bold">Contact Number</th>
                        </tr>
                    </thead>
                    <tbody id="bloodDonorList">
                        @forelse($donors as $donor)
                        <tr class="blood-donor-row border-t border-gray-100 hover:bg-primary-50/50 transition" data-blood-group="{{ $donor->blood_group }}">
                            <td class="px-5 py-4 font-semibold text-gray-800">{{ $donor->name }}</td>
                            <td class="px-5 py-4"><span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-600 font-bold">{{ $donor->blood_group }}</span></td>
                            <td class="px-5 py-4 text-gray-600">{{ $donor->contact_number }}</td>
                        </tr>
                        @empty
                        <tr id="bloodDonorEmptyDefault"><td colspan="3" class="text-center py-16 text-gray-400"><i class="fas fa-tint text-5xl mb-4"></i><p class="text-lg">No blood donors yet.</p></td></tr>
                        @endforelse
                        <tr id="bloodDonorEmptyFilter" class="hidden"><td colspan="3" class="text-center py-16 text-gray-400"><i class="fas fa-search text-5xl mb-4"></i><p class="text-lg">No donors found for this blood group.</p></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('bloodGroupFilter')?.addEventListener('change', function () {
    const selected = this.value;
    const rows = Array.from(document.querySelectorAll('.blood-donor-row'));
    let visible = 0;

    rows.forEach(row => {
        const match = !selected || row.dataset.bloodGroup === selected;
        row.classList.toggle('hidden', !match);
        if (match) visible++;
    });

    document.getElementById('bloodDonorEmptyFilter')?.classList.toggle('hidden', visible > 0 || rows.length === 0);
});
</script>
@endpush
@endsection
