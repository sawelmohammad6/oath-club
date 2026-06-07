<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin | Oath Club</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html, body { min-height: 100%; overflow-x: hidden; }
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; background: #f6f8fb; }
        .admin-sidebar { width: 280px; height: 100vh; height: 100dvh; flex-direction: column; }
        .admin-sidebar-menu { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 1rem; }
        .admin-sidebar-footer { flex: 0 0 auto; padding: 1rem; border-top: 1px solid #f1f5f9; background: #fff; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; min-width: 0; padding: 0.72rem 0.9rem; border-radius: 0.5rem; color: #64748b; transition: all 0.2s ease; cursor: pointer; font-weight: 600; line-height: 1.25; white-space: nowrap; }
        .sidebar-link:hover { background: #f0fdf4; color: #16a34a; }
        .sidebar-link.active { background: #ecfdf5; color: #15803d; box-shadow: inset 3px 0 0 #16a34a; }
        .sidebar-link i { flex: 0 0 1.25rem; width: 1.25rem; text-align: center; font-size: 1rem; }
        .sidebar-link span { min-width: 0; overflow: hidden; text-overflow: ellipsis; }
        .stat-card { border-radius: 0.5rem; padding: 1.25rem; color: white; transition: all 0.3s ease; cursor: default; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .btn-primary { padding: 0.625rem 1.1rem; background: linear-gradient(135deg, #16a34a, #22c55e); color: white; border-radius: 0.5rem; transition: all 0.2s ease; font-weight: 700; box-shadow: 0 4px 12px rgba(22,163,74,0.25); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem; line-height: 1.2; white-space: nowrap; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,0.35); }
        .btn-danger { padding: 0.625rem 1.1rem; background: linear-gradient(135deg, #dc2626, #ef4444); color: white; border-radius: 0.5rem; transition: all 0.2s ease; font-weight: 700; box-shadow: 0 4px 12px rgba(220,38,38,0.25); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.875rem; line-height: 1.2; white-space: nowrap; }
        .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(220,38,38,0.35); }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
        .admin-card { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04); border: 1px solid #e8edf4; overflow: hidden; }
        .admin-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .badge { display: inline-flex; padding: 0.2rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-approved { background: #dcfce7; color: #16a34a; }
        .badge-rejected { background: #fee2e2; color: #dc2626; }
        .page { display: none; }
        .page.active { display: block; animation: fadeSlide 0.3s ease; }
        @keyframes fadeSlide { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        table.admin-table { width: 100%; font-size: 0.875rem; }
        table.admin-table th { text-align: left; padding: 0.875rem 1rem; background: #f8fafc; font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        table.admin-table td { padding: 0.875rem 1rem; border-top: 1px solid #f1f5f9; vertical-align: middle; }
        table.admin-table tbody tr:hover { background: #f0fdf4; }
        .form-input { width: 100%; padding: 0.625rem 0.9rem; border-radius: 0.5rem; border: 1px solid #dbe3ef; transition: all 0.2s; background: white; }
        .form-input:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.15); }
        .sidebar-scroll { scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .action-btn { padding: 0.4rem 0.7rem; border-radius: 0.5rem; font-size: 0.8rem; transition: all 0.15s ease; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem; min-height: 2rem; min-width: 2rem; line-height: 1; }
        .action-btn:hover { transform: translateY(-1px); }
        .modal-panel { border-radius: 0.5rem; }
    </style>
    @stack('styles')
</head>
<body class="text-gray-800">

    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '{{ session("success") }}', showConfirmButton: false, timer: 3000, timerProgressBar: true }));</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: '{{ session("error") }}', showConfirmButton: false, timer: 3000, timerProgressBar: true }));</script>
    @endif

    <div class="min-h-screen flex">
        <aside class="hidden lg:flex admin-sidebar bg-white fixed left-0 top-0 z-30 shadow-[1px_0_0_rgba(0,0,0,0.06)]">
            <div class="p-5 border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/oath.png') }}" alt="Oath Club" class="h-12 w-12 object-contain" onerror="this.src='{{ asset('assets/logo.png') }}'">
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg leading-tight">Oath Club</h2>
                        <p class="text-gray-400 text-xs">Admin Panel</p>
                    </div>
                </div>
            </div>
            <nav class="admin-sidebar-menu sidebar-scroll space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
                <a href="{{ route('admin.applications') }}" class="sidebar-link {{ request()->routeIs('admin.applications*') ? 'active' : '' }}"><i class="fas fa-file-invoice"></i><span>Applications</span></a>
                <a href="{{ route('admin.members') }}" class="sidebar-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}"><i class="fas fa-users"></i><span>Members</span></a>
                <a href="{{ route('admin.honorary-advisory-council') }}" class="sidebar-link {{ request()->routeIs('admin.honorary-advisory-council*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i><span>Honorary Advisory Council</span></a>
                <a href="{{ route('admin.executive-advisory-council') }}" class="sidebar-link {{ request()->routeIs('admin.executive-advisory-council*') ? 'active' : '' }}"><i class="fas fa-user-tie"></i><span>Executive Advisory Council</span></a>
                <a href="{{ route('admin.committee') }}" class="sidebar-link {{ request()->routeIs('admin.committee*') ? 'active' : '' }}"><i class="fas fa-user-tie"></i><span>Committee</span></a>
                <a href="{{ route('admin.gallery') }}" class="sidebar-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}"><i class="fas fa-images"></i><span>Gallery</span></a>
                <a href="{{ route('admin.activities') }}" class="sidebar-link {{ request()->routeIs('admin.activities*') ? 'active' : '' }}"><i class="fas fa-tasks"></i><span>Activities</span></a>
                <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i><span>Tree Plantation</span></a>
                <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i><span>Educational Support</span></a>
                <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-tshirt"></i><span>Winter Clothing</span></a>
                <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-heartbeat"></i><span>Health Awareness</span></a>
                <a href="{{ route('admin.sports-teams') }}" class="sidebar-link {{ request()->routeIs('admin.sports-teams*') ? 'active' : '' }}"><i class="fas fa-running"></i><span>Sports Management</span></a>
                <a href="{{ route('admin.donation-settings') }}" class="sidebar-link {{ request()->routeIs('admin.donation-settings*') ? 'active' : '' }}"><i class="fas fa-hand-holding-heart"></i><span>Donation Settings</span></a>
                <a href="{{ route('admin.blood-donors') }}" class="sidebar-link {{ request()->routeIs('admin.blood-donors*') ? 'active' : '' }}"><i class="fas fa-tint"></i><span>Blood Donors</span></a>
                <a href="{{ route('admin.settings.website') }}" class="sidebar-link {{ request()->routeIs('admin.settings.website*') ? 'active' : '' }}"><i class="fas fa-cog"></i><span>Web Settings</span></a>
                <a href="{{ route('admin.settings.contact') }}" class="sidebar-link {{ request()->routeIs('admin.settings.contact*') ? 'active' : '' }}"><i class="fas fa-address-card"></i><span>Contact</span></a>
                <a href="{{ route('admin.settings.banners') }}" class="sidebar-link {{ request()->routeIs('admin.settings.banners*') ? 'active' : '' }}"><i class="fas fa-images"></i><span>Banners</span></a>
                @if(session('admin_is_master'))
                <a href="{{ route('admin.sub-admins') }}" class="sidebar-link {{ request()->routeIs('admin.sub-admins*') ? 'active' : '' }}"><i class="fas fa-user-cog"></i><span>Sub Admin Management</span></a>
                @endif
            </nav>
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="sidebar-link text-gray-500 hover:text-primary-600 hover:bg-primary-50"><i class="fas fa-globe"></i><span>View Website</span></a>
                <a href="{{ route('admin.logout') }}" class="sidebar-link text-red-500 hover:text-red-600 hover:bg-red-50 mt-1"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </div>
        </aside>

        <div class="lg:pl-[280px] w-full">
            <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-20 shadow-sm">
                <div class="flex items-center gap-3">
                    <button onclick="document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');document.getElementById('mobileSidebarOverlay').classList.toggle('hidden')" class="text-gray-600 text-xl"><i class="fas fa-bars"></i></button>
                    <span class="font-bold text-gray-800">Oath Club</span>
                </div>
                <span class="text-xs text-gray-400">Admin</span>
            </div>

            <div id="mobileSidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');document.getElementById('mobileSidebarOverlay').classList.toggle('hidden')"></div>
            <div id="mobileSidebar" class="fixed top-0 left-0 admin-sidebar flex flex-col bg-white z-50 transform -translate-x-full transition-transform duration-300 lg:hidden shadow-xl">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/oath.png') }}" alt="" class="h-10 w-10 object-contain" onerror="this.src='{{ asset('assets/logo.png') }}'">
                        <span class="font-bold text-gray-800 text-lg">Oath Club</span>
                    </div>
                    <button onclick="document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');document.getElementById('mobileSidebarOverlay').classList.toggle('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <nav class="admin-sidebar-menu sidebar-scroll space-y-0.5">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
                    <a href="{{ route('admin.applications') }}" class="sidebar-link {{ request()->routeIs('admin.applications*') ? 'active' : '' }}"><i class="fas fa-file-invoice"></i><span>Applications</span></a>
                    <a href="{{ route('admin.members') }}" class="sidebar-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}"><i class="fas fa-users"></i><span>Members</span></a>
                    <a href="{{ route('admin.honorary-advisory-council') }}" class="sidebar-link {{ request()->routeIs('admin.honorary-advisory-council*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i><span>Honorary Advisory Council</span></a>
                    <a href="{{ route('admin.executive-advisory-council') }}" class="sidebar-link {{ request()->routeIs('admin.executive-advisory-council*') ? 'active' : '' }}"><i class="fas fa-user-tie"></i><span>Executive Advisory Council</span></a>
                    <a href="{{ route('admin.committee') }}" class="sidebar-link {{ request()->routeIs('admin.committee*') ? 'active' : '' }}"><i class="fas fa-user-tie"></i><span>Committee</span></a>
                    <a href="{{ route('admin.gallery') }}" class="sidebar-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}"><i class="fas fa-images"></i><span>Gallery</span></a>
                    <a href="{{ route('admin.activities') }}" class="sidebar-link {{ request()->routeIs('admin.activities*') ? 'active' : '' }}"><i class="fas fa-tasks"></i><span>Activities</span></a>
                    <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i><span>Tree Plantation</span></a>
                    <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i><span>Educational Support</span></a>
                    <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-tshirt"></i><span>Winter Clothing</span></a>
                    <a href="{{ route('admin.activity-details') }}" class="sidebar-link {{ request()->routeIs('admin.activity-details*') ? 'active' : '' }}"><i class="fas fa-heartbeat"></i><span>Health Awareness</span></a>
                    <a href="{{ route('admin.sports-teams') }}" class="sidebar-link {{ request()->routeIs('admin.sports-teams*') ? 'active' : '' }}"><i class="fas fa-running"></i><span>Sports Management</span></a>
                    <a href="{{ route('admin.donation-settings') }}" class="sidebar-link {{ request()->routeIs('admin.donation-settings*') ? 'active' : '' }}"><i class="fas fa-hand-holding-heart"></i><span>Donation Settings</span></a>
                    <a href="{{ route('admin.blood-donors') }}" class="sidebar-link {{ request()->routeIs('admin.blood-donors*') ? 'active' : '' }}"><i class="fas fa-tint"></i><span>Blood Donors</span></a>
                    <a href="{{ route('admin.settings.website') }}" class="sidebar-link {{ request()->routeIs('admin.settings.website*') ? 'active' : '' }}"><i class="fas fa-cog"></i><span>Web Settings</span></a>
                    <a href="{{ route('admin.settings.contact') }}" class="sidebar-link {{ request()->routeIs('admin.settings.contact*') ? 'active' : '' }}"><i class="fas fa-address-card"></i><span>Contact</span></a>
                    <a href="{{ route('admin.settings.banners') }}" class="sidebar-link {{ request()->routeIs('admin.settings.banners*') ? 'active' : '' }}"><i class="fas fa-images"></i><span>Banners</span></a>
                    @if(session('admin_is_master'))
                    <a href="{{ route('admin.sub-admins') }}" class="sidebar-link {{ request()->routeIs('admin.sub-admins*') ? 'active' : '' }}"><i class="fas fa-user-cog"></i><span>Sub Admin Management</span></a>
                    @endif
                </nav>
                <div class="admin-sidebar-footer">
                    <a href="{{ route('home') }}" class="sidebar-link text-gray-500 hover:text-primary-600 hover:bg-primary-50"><i class="fas fa-globe"></i><span>View Website</span></a>
                    <a href="{{ route('admin.logout') }}" class="sidebar-link text-red-500 hover:text-red-600 hover:bg-red-50 mt-1"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
                </div>
            </div>

            <div class="p-4 md:p-6 lg:p-8">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('submit', function (event) {
            const form = event.target.closest('[data-confirm-delete]');
            if (!form || form.dataset.confirmed === 'true') return;

            event.preventDefault();
            Swal.fire({
                title: form.dataset.confirmTitle || 'Delete this item?',
                text: form.dataset.confirmText || 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: form.dataset.confirmButton || 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
