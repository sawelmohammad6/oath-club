@extends('layouts.app')

@section('content')
<section class="section-padding bg-gradient-to-br from-primary-700 to-primary-500 text-white min-h-screen pt-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-white/20 rounded-full text-sm font-semibold mb-4 backdrop-blur-sm">সদস্য রেজিস্ট্রেশন</span>
            <h2 class="text-3xl md:text-4xl font-bold mb-3">Oath Club-এ জয়েন করুন</h2>
            <p class="text-white/80">ফর্মটি পূরণ করে সদস্য হওয়ার জন্য আবেদন করুন।</p>
        </div>
        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 md:p-10 border border-white/20" data-aos="fade-up">
            <div class="bg-white/15 rounded-xl p-4 mb-6 text-center text-sm">
                <i class="fas fa-info-circle mr-1.5"></i>
                সদস্য ফি: <strong>{{ $settings['membership_fee'] ?? '100' }} টাকা</strong> | 
                বিকাশ: <strong>{{ $settings['bkash'] ?? '' }}</strong> | 
                নগদ: <strong>{{ $settings['nagad'] ?? '' }}</strong>
            </div>
            <form id="joinForm" class="grid md:grid-cols-2 gap-5" enctype="multipart/form-data">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-user mr-1"></i> পূর্ণ নাম <span class="text-red-300">*</span></label>
                    <input type="text" name="full_name" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="আপনার পূর্ণ নাম লিখুন">
                </div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-male mr-1"></i> পিতার নাম</label><input type="text" name="father_name" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="পিতার নাম"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-female mr-1"></i> মাতার নাম</label><input type="text" name="mother_name" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="মাতার নাম"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-calendar mr-1"></i> জন্ম তারিখ <span class="text-red-300">*</span></label><input type="date" name="date_of_birth" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-primary-300 transition"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-phone mr-1"></i> মোবাইল নম্বর <span class="text-red-300">*</span></label><input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="০১৭XX-XXXXXX"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-envelope mr-1"></i> ইমেইল</label><input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="email@example.com"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-briefcase mr-1"></i> পেশা</label><input type="text" name="occupation" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="আপনার পেশা"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-tint mr-1"></i> রক্তের গ্রুপ</label>
                    <select name="blood_group" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-primary-300 transition">
                        <option value="">নির্বাচন করুন</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}">{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-map-marker-alt mr-1"></i> ঠিকানা</label><input type="text" name="address" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="বর্তমান ঠিকানা"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-receipt mr-1"></i> লেনদেন আইডি (TrxID) <span class="text-red-300">*</span></label><input type="text" name="transaction_id" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="বিকাশ/নগদ TrxID"></div>
                <div><label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-camera mr-1"></i> ছবি <span class="text-red-300">*</span></label><input type="file" name="photo" accept="image/*" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-700 border-0 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-600 file:text-white file:font-semibold file:cursor-pointer text-sm"></div>
                <div class="md:col-span-2 mt-2"><button type="submit" class="w-full px-8 py-4 bg-white text-primary-700 hover:bg-primary-50 rounded-xl font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2"><i class="fas fa-paper-plane"></i> আবেদন জমা দিন</button></div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('joinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> জমা দেওয়া হচ্ছে...';
    try {
        const res = await fetch('{{ route("apply.store") }}', { method: 'POST', body: new FormData(this) });
        const json = await res.json();
        if (json.success) {
            await Swal.fire({ icon: 'success', title: 'সফল!', text: json.message, timer: 2000, showConfirmButton: false });
            this.reset();
        } else {
            Swal.fire({ icon: 'error', title: 'ত্রুটি', text: 'পুনরায় চেষ্টা করুন' });
        }
    } catch(e) { Swal.fire({ icon: 'error', title: 'ত্রুটি', text: 'সার্ভার এরর' }); }
    btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> আবেদন জমা দিন';
});
</script>
@endpush
