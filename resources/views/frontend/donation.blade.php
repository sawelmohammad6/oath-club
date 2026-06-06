@extends('layouts.app')

@section('content')
<section class="section-padding bg-gray-50 min-h-screen pt-24">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">Donation</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Donation Information</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Support Oath Club by using any available donation method below.</p>
        </div>

        <div class="flex flex-col items-center justify-center gap-6">
            @forelse($donationSettings as $setting)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full max-w-[700px] mx-auto" data-aos="fade-up">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center"><i class="fas fa-hand-holding-heart text-primary-600 text-xl"></i></div>
                    <h3 class="text-xl font-bold text-gray-800">Donation Account</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3"><span class="text-gray-500">Bkash Number</span><strong class="text-gray-800 text-right">{{ $setting->bkash_number ?: '-' }}</strong></div>
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3"><span class="text-gray-500">Nagad Number</span><strong class="text-gray-800 text-right">{{ $setting->nagad_number ?: '-' }}</strong></div>
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3"><span class="text-gray-500">Bank Name</span><strong class="text-gray-800 text-right">{{ $setting->bank_name ?: '-' }}</strong></div>
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3"><span class="text-gray-500">Account Name</span><strong class="text-gray-800 text-right">{{ $setting->account_name ?: '-' }}</strong></div>
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3"><span class="text-gray-500">Account Number</span><strong class="text-gray-800 text-right">{{ $setting->account_number ?: '-' }}</strong></div>
                    <div class="flex items-start justify-between gap-4"><span class="text-gray-500">Branch Name</span><strong class="text-gray-800 text-right">{{ $setting->branch_name ?: '-' }}</strong></div>
                </div>
            </div>
            @empty
            <div class="w-full max-w-[700px] mx-auto text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm" data-aos="fade-up">
                <i class="fas fa-hand-holding-heart text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">Donation information is not available yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
