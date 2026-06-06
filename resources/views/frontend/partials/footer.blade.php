<footer class="bg-gray-900 text-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('assets/oath.png') }}" alt="Oath Club" class="h-12 w-12 object-contain" onerror="this.onerror=null;this.src='{{ asset('assets/logo.png') }}';">
                    <span class="text-2xl font-bold text-white">{{ $settings['club_name'] ?? 'Oath Club' }}</span>
                </div>
                <p class="text-sm leading-relaxed">{{ $settings['about_text'] ?? 'একতা, নেতৃত্ব ও সমাজসেবায় আমাদের অঙ্গীকার।' }}</p>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg mb-4">দ্রুত লিংক</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#about" class="hover:text-primary-400 transition">আমাদের সম্পর্কে</a></li>
                    <li><a href="#activities" class="hover:text-primary-400 transition">কার্যক্রম</a></li>
                    <li><a href="#members" class="hover:text-primary-400 transition">সদস্য</a></li>
                    <li><a href="#committee" class="hover:text-primary-400 transition">কমিটি</a></li>
                    <li><a href="#gallery" class="hover:text-primary-400 transition">গ্যালারি</a></li>
                    <li><a href="{{ route('apply') }}" class="hover:text-primary-400 transition">সদস্য হোন</a></li>
                    <li><a href="#contact" class="hover:text-primary-400 transition">যোগাযোগ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg mb-4">যোগাযোগ</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3"><i class="fas fa-phone mt-1 text-primary-400"></i><span>{{ $settings['phone'] ?? '' }}</span></li>
                    <li class="flex items-start gap-3"><i class="fas fa-envelope mt-1 text-primary-400"></i><span>{{ $settings['email'] ?? '' }}</span></li>
                    <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt mt-1 text-primary-400"></i><span>{{ $settings['address'] ?? '' }}</span></li>
                </ul>
                <div class="flex gap-3 mt-6">
                    <a href="{{ $settings['facebook'] ?? '#' }}" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-xl flex items-center justify-center transition"><i class="fab fa-facebook-f text-white"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-xl flex items-center justify-center transition"><i class="fab fa-youtube text-white"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-xl flex items-center justify-center transition"><i class="fab fa-instagram text-white"></i></a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-10 pt-8 text-center text-xs">
            &copy; {{ date('Y') }} {{ $settings['club_name'] ?? 'Oath Club' }}. All rights reserved.
            <span class="mx-2">|</span>
            <a href="{{ route('admin.login') }}" class="text-gray-500 hover:text-primary-400 transition"><i class="fas fa-shield-alt mr-1"></i>Admin</a>
        </div>
    </div>
</footer>
