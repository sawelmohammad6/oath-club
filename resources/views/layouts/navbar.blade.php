<nav id="navbar" class="fixed top-0 left-0 right-0 z-[9999] bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
                <img src="{{ asset('assets/oath.png') }}" alt="Oath Club" class="h-12 w-12 object-contain" onerror="this.onerror=null;this.src='{{ asset('assets/oath.png') }}';">
                <span class="text-2xl font-bold text-primary-700">{{ $settings['club_name'] ?? 'Oath Club' }}</span>
            </a>

            <div class="hidden lg:flex items-center gap-0.5">
                <a href="{{ route('home') }}" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">হোম</a>
                <a href="{{ route('home') }}#about" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">আমাদের সম্পর্কে</a>
                <a href="{{ route('home') }}#vision" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">দৃষ্টি</a>
                <a href="{{ route('home') }}#mission" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">মিশন</a>
                <a href="{{ route('home') }}#activities" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">কার্যক্রম</a>
                <a href="{{ route('home') }}#gallery" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">গ্যালারি</a>
                <a href="{{ route('home') }}#members" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">সদস্যবৃন্দ</a>
                <a href="{{ route('home') }}#contact" class="nav-link px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-600">যোগাযোগ</a>
            </div>

            <div class="flex items-center gap-2">
                @if(session()->has('admin_id'))
                    <a href="{{ route('admin.dashboard') }}" class="hidden lg:inline-flex text-xs text-gray-400 hover:text-primary-600 transition"><i class="fas fa-tachometer-alt"></i></a>
                    <a href="{{ route('admin.logout') }}" class="hidden lg:inline-flex text-xs text-gray-400 hover:text-red-500 transition"><i class="fas fa-sign-out-alt"></i></a>
                @else
                    <a href="{{ route('admin.login') }}" class="hidden lg:inline-flex text-xs text-gray-400 hover:text-primary-600 transition"><i class="fas fa-lock"></i></a>
                @endif
                <a href="{{ route('home') }}#join" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-full text-sm font-semibold transition shadow-md hover:shadow-lg flex items-center gap-1.5 flex-shrink-0">
                    <i class="fas fa-user-plus"></i> Join Our Club
                </a>
                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden');this.querySelector('i').classList.toggle('fa-bars');this.querySelector('i').classList.toggle('fa-times')" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition ml-1" aria-label="Menu">
                    <i class="fas fa-bars text-2xl text-gray-600"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="lg:hidden hidden bg-white border-t border-gray-100 shadow-lg">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">হোম</a>
            <a href="{{ route('home') }}#about" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">আমাদের সম্পর্কে</a>
            <a href="{{ route('home') }}#vision" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">দৃষ্টি</a>
            <a href="{{ route('home') }}#mission" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">মিশন</a>
            <a href="{{ route('home') }}#activities" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">কার্যক্রম</a>
            <a href="{{ route('home') }}#gallery" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">গ্যালারি</a>
            <a href="{{ route('home') }}#members" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">সদস্যবৃন্দ</a>
            <a href="{{ route('home') }}#contact" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium">যোগাযোগ</a>
            <hr class="my-2 border-gray-100">
            @if(session()->has('admin_id'))
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium"><i class="fas fa-tachometer-alt mr-2"></i>ড্যাশবোর্ড</a>
                <a href="{{ route('admin.logout') }}" class="block px-4 py-2.5 rounded-lg text-red-500 hover:bg-red-50 font-medium"><i class="fas fa-sign-out-alt mr-2"></i>লগআউট</a>
            @else
                <a href="{{ route('admin.login') }}" class="block px-4 py-2.5 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-600 font-medium"><i class="fas fa-lock mr-2"></i>লগইন</a>
            @endif
            <a href="{{ route('home') }}#join" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block mt-2 px-4 py-2.5 bg-primary-600 text-white rounded-lg text-center font-semibold"><i class="fas fa-user-plus mr-1.5"></i>Join Our Club</a>
        </div>
    </div>
</nav>

<script>
(function() {
    var navbar = document.getElementById('navbar');
    if (!navbar) return;
    var navLinks = navbar.querySelectorAll('.nav-link');

    function updateActiveLink() {
        var scrollY = window.scrollY + 120;
        var activeId = '';

        document.querySelectorAll('section[id], #vision, #mission').forEach(function(el) {
            var top = el.offsetTop;
            var height = el.offsetHeight;
            if (scrollY >= top && scrollY < top + height) {
                activeId = el.getAttribute('id');
            }
        });

        navLinks.forEach(function(link) {
            link.classList.remove('active');
            var href = link.getAttribute('href');
            if (href && href.indexOf('#') !== -1 && href.split('#')[1] === activeId) {
                link.classList.add('active');
            }
            if (!activeId && href && (href === '{{ route('home') }}' || href === '#' || href === '{{ route('home') }}#')) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-md');
        } else {
            navbar.classList.remove('shadow-md');
            navbar.classList.add('shadow-sm');
        }
        updateActiveLink();
    });

    updateActiveLink();
})();
</script>
