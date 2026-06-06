// ============================================
// OATH CLUB - Admin Session Management
// ============================================

const ADMIN_SESSION_KEY = 'oathClubAdminSession';
const ADMIN_CRED_KEY = 'oathClubAdmin';

function isAdminLoggedIn() {
    const val = localStorage.getItem(ADMIN_SESSION_KEY) === 'true';
    console.log('[Auth] isAdminLoggedIn:', val);
    return val;
}

function getAdminCredentials() {
    const stored = JSON.parse(localStorage.getItem(ADMIN_CRED_KEY) || '{}');
    return {
        username: stored.username || ADMIN_DEFAULT_USER,
        password: stored.password || ADMIN_DEFAULT_PASS
    };
}

function adminLogin(user, pass) {
    const creds = getAdminCredentials();
    if (user === creds.username && pass === creds.password) {
        localStorage.setItem(ADMIN_SESSION_KEY, 'true');
        console.log('[Auth] Login successful');
        updateAuthUI();
        return true;
    }
    console.warn('[Auth] Login failed for user:', user);
    return false;
}

function adminLogout() {
    localStorage.removeItem(ADMIN_SESSION_KEY);
    console.log('[Auth] Logged out');
    updateAuthUI();
    if (window.location.hash === '#/admin' || window.location.pathname.includes('admin')) {
        window.location.href = 'index.html';
    }
}

function updateAuthUI() {
    const loggedIn = isAdminLoggedIn();
    const hasLoginBtns = document.querySelectorAll('.admin-login-btn').length > 0;
    if (!hasLoginBtns) return;
    document.querySelectorAll('.admin-login-btn').forEach(el => el.classList.toggle('hidden', loggedIn));
    document.querySelectorAll('.admin-dashboard-btn').forEach(el => el.classList.toggle('hidden', !loggedIn));
    document.querySelectorAll('.admin-logout-btn').forEach(el => el.classList.toggle('hidden', !loggedIn));
    document.getElementById('adminLoginModal')?.classList.add('hidden');
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        window.location.href = 'index.html';
        return false;
    }
    return true;
}

// ========== LOGIN MODAL ==========
function openAdminLoginModal() {
    const modal = document.getElementById('adminLoginModal');
    if (modal) modal.classList.remove('hidden');
}

function closeAdminLoginModal() {
    const modal = document.getElementById('adminLoginModal');
    if (modal) modal.classList.add('hidden');
}

function handleAdminLogin() {
    const user = document.getElementById('adminModalUser').value.trim();
    const pass = document.getElementById('adminModalPass').value;
    if (adminLogin(user, pass)) {
        closeAdminLoginModal();
        Swal.fire({
            icon: 'success',
            title: 'স্বাগতম!',
            text: 'আপনি সফলভাবে লগইন করেছেন',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
        setTimeout(() => {
            window.location.href = 'admin.html';
        }, 500);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'ভুল তথ্য',
            text: 'Username বা Password সঠিক নয়',
            confirmButtonColor: '#dc2626'
        });
    }
}

function toggleAdminPasswordVisibility() {
    const pass = document.getElementById('adminModalPass');
    const icon = document.querySelector('.toggle-password-icon');
    if (pass && pass.type === 'password') {
        pass.type = 'text';
        if (icon) icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else if (pass) {
        pass.type = 'password';
        if (icon) icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// ========== ROUTE GUARD ==========
document.addEventListener('DOMContentLoaded', function () {
    console.log('[Auth] DOMContentLoaded, hash:', window.location.hash);

    if (window.location.hash === '#/admin') {
        if (!isAdminLoggedIn()) {
            console.log('[Auth] Not logged in — showing login modal');
            const modal = document.getElementById('adminLoginModal');
            if (modal) modal.classList.remove('hidden');
        } else {
            console.log('[Auth] Logged in — redirecting to admin.html');
            window.location.href = 'admin.html';
        }
    }

    updateAuthUI();
});
