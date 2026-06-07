<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['club_name'] ?? 'Oath Club' }} - {{ $settings['tagline'] ?? 'একতা, নেতৃত্ব ও সমাজসেবা' }}</title>
    <meta name="description" content="Oath Club - একটি কমিউনিটি ভিত্তিক সংগঠন যা সমাজকল্যাণ, যুব উন্নয়ন ও শিক্ষা সহায়তার জন্য নিবেদিত।">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' }
                    },
                    fontFamily: { bengali: ['Hind Siliguri', 'Noto Sans Bengali', 'sans-serif'], english: ['Inter', 'Segoe UI', 'sans-serif'] }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { scroll-behavior: smooth; }
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; padding-top: 80px; }
        .section-padding { padding-top: 5rem; padding-bottom: 5rem; }
        @media (max-width: 768px) { .section-padding { padding-top: 3rem; padding-bottom: 3rem; } }
        .nav-link.active { color: #16a34a; font-weight: 600; }
        .hero-overlay { background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%); }
        [data-aos] { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        [data-aos].aos-animate { opacity: 1; transform: translateY(0); }
        [data-aos=fade-right] { transform: translateX(-30px); }
        [data-aos=fade-right].aos-animate { transform: translateX(0); }
        [data-aos=fade-left] { transform: translateX(30px); }
        [data-aos=fade-left].aos-animate { transform: translateX(0); }
        .member-card { transition: all 0.35s ease; }
        .member-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(22, 163, 74, 0.15); }
        #membersContainer { position: relative; }
        @media (max-width: 767px) {
            #membersContainer.limited .member-card:nth-child(n+5) {
                position: absolute;
                opacity: 0;
                pointer-events: none;
                transform: scale(0.85);
                z-index: -1;
            }
        }
        @media (min-width: 768px) {
            #membersContainer.limited .member-card:nth-child(n+9) {
                position: absolute;
                opacity: 0;
                pointer-events: none;
                transform: scale(0.85);
                z-index: -1;
            }
        }
        .glass-modal { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); }
        .admin-modal-overlay { background: rgba(0,0,0,0.5); }
    </style>
</head>
<body class="font-bengali bg-gray-50 text-gray-800">

    @include('layouts.navbar')

    @yield('content')

    @include('frontend.partials.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('aos-animate');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('[data-aos]').forEach(el => observer.observe(el));
        });
    </script>
    @stack('scripts')
</body>
</html>
