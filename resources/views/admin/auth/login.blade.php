<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Oath Club</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; }
        .login-bg { background: linear-gradient(135deg, #14532d 0%, #16a34a 50%, #22c55e 100%); }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4">

    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '{{ session("success") }}', showConfirmButton: false, timer: 3000 }));</script>
    @endif

    <div class="w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-8 md:p-10 shadow-2xl">
            <div class="text-center mb-8">
                <img src="{{ asset('assets/oath.png') }}" alt="Oath Club" class="h-24 w-24 mx-auto object-contain mb-4" onerror="this.onerror=null;this.src='{{ asset('assets/logo.png') }}';">
                <h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-gray-500 mt-1">Oath Club পরিচালনা প্যানেলে স্বাগতম</p>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-red-700 text-sm">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5"><i class="fas fa-envelope text-gray-400 mr-1.5"></i>Email</label>
                    <input type="email" name="email" value="admin@oathclub.com" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition text-gray-800" placeholder="admin@oathclub.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5"><i class="fas fa-lock text-gray-400 mr-1.5"></i>Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition text-gray-800" placeholder="Enter password">
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <label for="remember">Remember Me</label>
                </div>
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white rounded-xl font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-lock"></i> লগইন
                </button>
            </form>
            <p class="text-center text-xs text-gray-400 mt-6">
                <a href="{{ route('home') }}" class="text-primary-600 hover:underline"><i class="fas fa-arrow-left mr-1"></i>Back to Website</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
