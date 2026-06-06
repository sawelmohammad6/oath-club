/* ==========================================================================
   Admin Dashboard JS — Oath Club
   All CRUD: applications, members, committee, gallery, settings, contact
   ========================================================================== */

let allApplications = [];
let allMembers = [];
let allCommittee = [];
let allGallery = [];

/* ==============================
   HELPERS
   ============================== */
function $(sel) { return document.querySelector(sel); }
function $$(sel) { return document.querySelectorAll(sel); }
function el(tag, cls, html) { const e = document.createElement(tag); if (cls) e.className = cls; if (html) e.innerHTML = html; return e; }

function showPage(name) {
    $$('.page').forEach(p => p.classList.remove('active'));
    const pg = document.getElementById('page-' + name);
    if (pg) pg.classList.add('active');
    $$('.sidebar-link').forEach(l => l.classList.toggle('active', l.dataset.page === name));
    console.log('[Admin] Sidebar page switched:', name);
}

function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileSidebarOverlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
}

function apiCall(action, extra) {
    return fetch(GAS_URL, {
        method: 'POST',
        mode: 'no-cors',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, ...extra })
    });
}

/* ==============================
   JSONP-based apiGet (bypasses GAS CORS)
   ============================== */
function apiGet(action) {
    return new Promise((resolve) => {
        const cb = 'gas_cb_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
        const timeout = setTimeout(() => {
            if (window[cb]) {
                delete window[cb];
                const s = document.getElementById(cb);
                if (s) s.remove();
                console.warn('[Admin] JSONP timeout for:', action);
                resolve([]);
            }
        }, 15000);

        window[cb] = function(data) {
            clearTimeout(timeout);
            delete window[cb];
            const s = document.getElementById(cb);
            if (s) s.remove();
            const normalized = normalizeGASData(action, data);
            console.log('[Admin] API response -', action + ':', normalized.length !== undefined ? normalized.length + ' items' : 'OK');
            resolve(normalized);
        };

        const script = document.createElement('script');
        script.id = cb;
        // Map admin action names to GAS mode parameter
        const modeMap = {
            getApplications: 'members',
            getAllMembers: 'members',
            getCommittee: 'committee',
            getGallery: 'gallery',
            getSettings: 'settings'
        };
        const mode = modeMap[action] || action;
        script.src = GAS_URL + '?mode=' + mode + '&callback=' + cb;
        script.onerror = function() {
            clearTimeout(timeout);
            delete window[cb];
            const s = document.getElementById(cb);
            if (s) s.remove();
            console.warn('[Admin] JSONP script error for:', action);
            resolve([]);
        };
        document.body.appendChild(script);
    });
}

/* ==============================
   Normalize GAS response to admin.js expected format
   GAS returns: { members: [{id, fullName, ...}] }
   Admin expects: [{ MemberID, FullName, ... }]
   ============================== */
function normalizeGASData(action, data) {
    if (!data) return [];

    // Settings: GAS returns { settings: { key: val } }, Admin expects [{Key, Value}]
    if (action === 'getSettings') {
        const s = data.settings || data;
        if (Array.isArray(s)) return s;
        return Object.entries(s).map(([K, V]) => ({ Key: K, Value: V || '' }));
    }

    // Helper to remap camelCase GAS keys to PascalCase admin keys
    function remap(arr, keyMap) {
        if (!Array.isArray(arr)) return [];
        return arr.map(item => {
            const obj = {};
            for (const [adminKey, gasKey] of Object.entries(keyMap)) {
                obj[adminKey] = item[gasKey] !== undefined && item[gasKey] !== null ? item[gasKey] : '';
            }
            return obj;
        });
    }

    // Gallery: { gallery: [{id, imageUrl, caption, uploadDate}] }
    if (action === 'getGallery') {
        return remap(data.gallery || data, {
            ID: 'id', ImageURL: 'imageUrl', Caption: 'caption', UploadDate: 'uploadDate'
        });
    }

    // Committee: { committee: [{id, name, photoUrl, position, orderIndex}] }
    if (action === 'getCommittee') {
        return remap(data.committee || data, {
            ID: 'id', Name: 'name', PhotoURL: 'photoUrl', Position: 'position', OrderIndex: 'orderIndex'
        });
    }

    // Members / Applications: { members: [{id, fullName, phone, status, photoUrl, ...}] }
    if (action === 'getAllMembers' || action === 'getApplications') {
        return remap(data.members || data, {
            MemberID: 'id', FullName: 'fullName', FatherName: 'fatherName',
            MotherName: 'motherName', DOB: 'dob', Phone: 'phone', Email: 'email',
            Occupation: 'occupation', Address: 'address', BloodGroup: 'bloodGroup',
            TransactionID: 'transactionId', PhotoURL: 'photoUrl',
            SubmitDate: 'submitDate', Status: 'status', Position: 'position',
            CommitteeRole: 'committeeRole'
        });
    }

    return data;
}

function uploadToCloudinary(file) {
    return new Promise((resolve, reject) => {
        if (!file) return resolve('');
        const fd = new FormData();
        fd.append('file', file);
        fd.append('upload_preset', CLOUDINARY_UPLOAD_PRESET);
        fd.append('cloud_name', CLOUDINARY_CLOUD_NAME);
        fetch('https://api.cloudinary.com/v1_1/' + CLOUDINARY_CLOUD_NAME + '/image/upload', { method: 'POST', body: fd })
            .then(r => r.json()).then(d => resolve(d.secure_url || '')).catch(reject);
    });
}

function dateStr(d) {
    if (!d) return '-';
    const dt = new Date(d);
    return isNaN(dt) ? d : dt.toLocaleDateString('bn-BD', { day: 'numeric', month: 'short', year: 'numeric' });
}

/* ==============================
   TOAST / ALERTS
   ============================== */
function toast(msg, icon) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ toast: true, position: 'top-end', icon: icon || 'success', title: msg, showConfirmButton: false, timer: 2500, timerProgressBar: true });
    } else {
        console.log('[Admin] Toast:', msg);
    }
}

/* ==============================
   ERROR BANNER
   ============================== */
function showErrorBanner(message) {
    clearErrorBanner();
    const container = document.querySelector('#page-dashboard .flex.items-center.justify-between')?.parentElement || document.getElementById('page-dashboard');
    if (!container) return;
    const banner = document.createElement('div');
    banner.id = 'adminErrorBanner';
    banner.className = 'mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 flex items-start gap-3';
    banner.innerHTML = '<i class="fas fa-exclamation-triangle mt-1"></i><div><p class="font-semibold">Something went wrong</p><p class="text-sm mt-1">' + message + '</p></div>';
    container.prepend(banner);
}

function clearErrorBanner() {
    const existing = document.getElementById('adminErrorBanner');
    if (existing) existing.remove();
}

/* ==============================
   INIT
   ============================== */
document.addEventListener('DOMContentLoaded', function () {
    try {
        console.log('[Admin] DOMContentLoaded fired');

        if (typeof isAdminLoggedIn === 'undefined') {
            console.warn('[Admin] auth.js not loaded — isAdminLoggedIn missing');
        }

        if (!isAdminLoggedIn()) {
            console.log('[Admin] Not authenticated, redirecting to login');
            location.href = 'index.html#/admin';
            return;
        }

        console.log('[Admin] Authenticated — initializing dashboard');

        const dateEl = document.getElementById('dashboardDate');
        if (dateEl) {
            dateEl.textContent = 'Today: ' + new Date().toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
        } else {
            console.warn('[Admin] #dashboardDate not found');
        }

        loadDashboard();
        loadApplications();
        loadMembers();
        loadCommittee();
        loadGallery();
        loadSettings();
        loadContact();

        const loading = document.getElementById('appLoading');
        const content = document.getElementById('appContent');
        if (loading) loading.classList.add('hidden');
        if (content) content.classList.remove('hidden');

        console.log('[Admin] Dashboard initialized successfully');
    } catch (e) {
        console.error('[Admin] Init error:', e);
        showErrorBanner('Dashboard failed to load: ' + e.message + '. Check console for details.');
        const loading = document.getElementById('appLoading');
        if (loading) {
            loading.innerHTML = '<div class="text-center p-8"><div class="text-red-500 text-5xl mb-4"><i class="fas fa-exclamation-triangle"></i></div><h2 class="text-xl font-bold text-gray-800 mb-2">Loading Failed</h2><p class="text-gray-500 mb-4">' + e.message + '</p><button onclick="location.reload()" class="btn-primary">Retry</button></div>';
        }
    }
});

function handleLogout() {
    if (typeof adminLogout === 'function') {
        adminLogout();
    } else {
        localStorage.removeItem('oathClubAdminSession');
        location.href = 'index.html';
    }
}

/* ==============================
   DASHBOARD
   ============================== */
async function loadDashboard() {
    try {
        console.log('[Admin] Loading dashboard...');
        clearErrorBanner();

        const [a, b, c, d] = await Promise.all([
            apiGet('getAllMembers'),
            apiGet('getCommittee'),
            apiGet('getGallery'),
            apiGet('getApplications')
        ]);

        allMembers = Array.isArray(a) ? a : [];
        allCommittee = Array.isArray(b) ? b : [];
        allGallery = Array.isArray(c) ? c : [];
        allApplications = Array.isArray(d) ? d : [];

        console.log('[Admin] Dashboard data loaded:', {
            members: allMembers.length,
            committee: allCommittee.length,
            gallery: allGallery.length,
            applications: allApplications.length
        });

        const setText = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        };

        setText('statTotalApplications', allApplications.length);
        setText('statPendingApplications', allApplications.filter(x => (x.Status || 'Pending') === 'Pending').length);
        setText('statApprovedMembers', allMembers.filter(x => x.Status !== 'Rejected').length);
        setText('statCommitteeMembers', allCommittee.length);
        setText('statGalleryImages', allGallery.length);
    } catch (e) {
        console.error('[Admin] Dashboard load error:', e);
        showErrorBanner('Could not load dashboard data. GAS endpoint may be unreachable.');
    }
}

/* ==============================
   APPLICATIONS (Pending -> Approve/Reject/Delete)
   ============================== */
async function loadApplications() {
    console.log('[Admin] Loading applications...');
    const tbody = document.getElementById('applicationsTableBody');
    if (!tbody) { console.warn('[Admin] #applicationsTableBody not found'); return; }

    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading...</p></td></tr>';
    const raw = await apiGet('getApplications');
    allApplications = Array.isArray(raw) ? raw : [];

    if (!allApplications.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400"><i class="fas fa-inbox text-3xl mb-2"></i><p>No applications yet</p></td></tr>';
        console.log('[Admin] Applications count: 0');
        return;
    }
    tbody.innerHTML = '';
    allApplications.forEach(app => {
        const status = (app.Status || 'Pending');
        const badgeCls = status === 'Approved' ? 'badge-approved' : status === 'Rejected' ? 'badge-rejected' : 'badge-pending';
        const tr = el('tr');
        tr.innerHTML = `
            <td><img src="${app.PhotoURL || 'assets/placeholder.png'}" class="w-10 h-10 rounded-full object-cover" onerror="this.src='assets/placeholder.png'"></td>
            <td class="font-medium">${app.FullName || '-'}</td>
            <td class="text-gray-500">${app.Phone || '-'}</td>
            <td class="text-gray-500 font-mono">${app.TransactionID || '-'}</td>
            <td class="text-gray-500">${dateStr(app.SubmitDate)}</td>
            <td><span class="badge ${badgeCls}">${status}</span></td>
            <td class="flex gap-1.5">
                ${status === 'Pending' ? `<button onclick="approveApplication('${app.MemberID}', this)" class="btn-primary btn-sm"><i class="fas fa-check"></i></button>
                <button onclick="rejectApplication('${app.MemberID}', this)" class="btn-danger btn-sm"><i class="fas fa-times"></i></button>` : ''}
                <button onclick="deleteApplication('${app.MemberID}', this)" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-xl text-sm transition"><i class="fas fa-trash"></i></button>
            </td>`;
        tbody.appendChild(tr);
    });
    const pendingEl = document.getElementById('statPendingApplications');
    const totalEl = document.getElementById('statTotalApplications');
    if (totalEl) totalEl.textContent = allApplications.length;
    if (pendingEl) pendingEl.textContent = allApplications.filter(x => (x.Status || 'Pending') === 'Pending').length;
    console.log('[Admin] Applications count:', allApplications.length);
}

async function approveApplication(id, btn) {
    if (!btn) return;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    await apiCall('approveApplication', { memberId: id });
    toast('Application approved! Member added.');
    loadApplications();
    loadMembers();
    loadDashboard();
}

async function rejectApplication(id, btn) {
    const r = await Swal.fire({ title: 'Reject Application?', text: 'This will mark the application as rejected.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Reject' });
    if (!r.isConfirmed) return;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    await apiCall('rejectApplication', { memberId: id });
    toast('Application rejected.', 'warning');
    loadApplications();
    loadDashboard();
}

async function deleteApplication(id, btn) {
    const r = await Swal.fire({ title: 'Delete Application?', text: 'This cannot be undone.', icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Delete' });
    if (!r.isConfirmed) return;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    await apiCall('deleteApplication', { memberId: id });
    toast('Application deleted.', 'info');
    loadApplications();
    loadDashboard();
}

/* ==============================
   MEMBERS (CRUD)
   ============================== */
async function loadMembers() {
    const tbody = document.getElementById('membersTableBody');
    if (!tbody) { console.warn('[Admin] #membersTableBody not found'); return; }

    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading...</p></td></tr>';
    const raw = await apiGet('getAllMembers');
    allMembers = Array.isArray(raw) ? raw : [];
    const approved = allMembers.filter(m => m.Status !== 'Rejected');
    if (!approved.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-12 text-gray-400"><i class="fas fa-users text-3xl mb-2"></i><p>No members yet</p></td></tr>';
        return;
    }
    tbody.innerHTML = '';
    approved.forEach(m => {
        const tr = el('tr');
        tr.innerHTML = `
            <td><img src="${m.PhotoURL || 'assets/placeholder.png'}" class="w-10 h-10 rounded-full object-cover" onerror="this.src='assets/placeholder.png'"></td>
            <td class="font-medium">${m.FullName || '-'}</td>
            <td class="text-gray-500">${m.MemberID || '-'}</td>
            <td class="text-gray-500">${m.Phone || '-'}</td>
            <td class="text-gray-500">${m.Position || '-'}</td>
            <td class="flex gap-1.5">
                <button onclick="editMember('${m.MemberID}')" class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-xl text-sm transition"><i class="fas fa-edit"></i></button>
                <button onclick="deleteMember('${m.MemberID}')" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl text-sm transition"><i class="fas fa-trash"></i></button>
            </td>`;
        tbody.appendChild(tr);
    });
}

function showAddMemberModal() {
    Swal.fire({
        title: 'Add New Member',
        html: `
            <div class="text-left space-y-3">
                <input id="swalName" class="swal2-input" placeholder="Full Name">
                <input id="swalPhone" class="swal2-input" placeholder="Phone">
                <input id="swalEmail" class="swal2-input" placeholder="Email">
                <input id="swalPosition" class="swal2-input" placeholder="Position (e.g., General Member)">
                <input id="swalPhoto" type="file" class="swal2-file" accept="image/*">
            </div>`,
        showCancelButton: true, confirmButtonText: 'Add Member', confirmButtonColor: '#16a34a',
        preConfirm: () => {
            const name = document.getElementById('swalName').value.trim();
            if (!name) { Swal.showValidationMessage('Name is required'); return false; }
            return {
                name: name,
                phone: document.getElementById('swalPhone').value.trim(),
                email: document.getElementById('swalEmail').value.trim(),
                position: document.getElementById('swalPosition').value.trim(),
                photoFile: document.getElementById('swalPhoto').files[0]
            };
        }
    }).then(async (r) => {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const data = r.value;
        const photoURL = await uploadToCloudinary(data.photoFile);
        await apiCall('addMember', { FullName: data.name, Phone: data.phone, Email: data.email, Position: data.position, PhotoURL: photoURL });
        Swal.close();
        toast('Member added!');
        loadMembers();
        loadDashboard();
    });
}

async function editMember(id) {
    const m = allMembers.find(x => x.MemberID === id);
    if (!m) return;
    const { value: formValues } = await Swal.fire({
        title: 'Edit Member',
        html: `
            <div class="text-left space-y-3">
                <input id="swalName" class="swal2-input" placeholder="Full Name" value="${m.FullName || ''}">
                <input id="swalPhone" class="swal2-input" placeholder="Phone" value="${m.Phone || ''}">
                <input id="swalEmail" class="swal2-input" placeholder="Email" value="${m.Email || ''}">
                <input id="swalPosition" class="swal2-input" placeholder="Position" value="${m.Position || ''}">
                <input id="swalPhoto" type="file" class="swal2-file" accept="image/*">
                ${m.PhotoURL ? `<p class="text-xs text-gray-400">Current photo: <a href="${m.PhotoURL}" target="_blank" class="text-primary-600 underline">view</a></p>` : ''}
            </div>`,
        showCancelButton: true, confirmButtonText: 'Update', confirmButtonColor: '#2563eb',
        preConfirm: () => {
            const name = document.getElementById('swalName').value.trim();
            if (!name) { Swal.showValidationMessage('Name is required'); return false; }
            return {
                name, phone: document.getElementById('swalPhone').value.trim(),
                email: document.getElementById('swalEmail').value.trim(),
                position: document.getElementById('swalPosition').value.trim(),
                photoFile: document.getElementById('swalPhoto').files[0]
            };
        }
    });
    if (!formValues) return;
    Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    const photoURL = formValues.photoFile ? await uploadToCloudinary(formValues.photoFile) : (m.PhotoURL || '');
    await apiCall('updateMember', { MemberID: id, FullName: formValues.name, Phone: formValues.phone, Email: formValues.email, Position: formValues.position, PhotoURL: photoURL });
    Swal.close();
    toast('Member updated!');
    loadMembers();
}

async function deleteMember(id) {
    const r = await Swal.fire({ title: 'Delete Member?', text: 'This will permanently remove the member.', icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Delete' });
    if (!r.isConfirmed) return;
    await apiCall('deleteMember', { memberId: id });
    toast('Member deleted.', 'info');
    loadMembers();
    loadDashboard();
}

/* ==============================
   COMMITTEE (CRUD / Reorder)
   ============================== */
async function loadCommittee() {
    const tbody = document.getElementById('committeeTableBody');
    if (!tbody) { console.warn('[Admin] #committeeTableBody not found'); return; }

    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading...</p></td></tr>';
    const raw = await apiGet('getCommittee');
    allCommittee = Array.isArray(raw) ? raw : [];
    allCommittee.sort((a, b) => (a.OrderIndex || 0) - (b.OrderIndex || 0));

    if (!allCommittee.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-12 text-gray-400"><i class="fas fa-user-tie text-3xl mb-2"></i><p>No committee members yet</p></td></tr>';
        return;
    }
    tbody.innerHTML = '';
    allCommittee.forEach((c, i) => {
        const tr = el('tr');
        tr.innerHTML = `
            <td><img src="${c.PhotoURL || 'assets/placeholder.png'}" class="w-10 h-10 rounded-full object-cover" onerror="this.src='assets/placeholder.png'"></td>
            <td class="font-medium">${c.Name || '-'}</td>
            <td class="text-gray-500">${c.Position || '-'}</td>
            <td class="text-gray-500">${c.OrderIndex || 0}</td>
            <td class="flex gap-1.5">
                <button onclick="moveCommittee(${i}, -1)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm ${i === 0 ? 'opacity-30 cursor-default' : ''}"><i class="fas fa-chevron-up"></i></button>
                <button onclick="moveCommittee(${i}, 1)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm ${i === allCommittee.length - 1 ? 'opacity-30 cursor-default' : ''}"><i class="fas fa-chevron-down"></i></button>
                <button onclick="editCommittee('${c.ID || c.Name}')" class="px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg text-sm"><i class="fas fa-edit"></i></button>
                <button onclick="deleteCommittee('${c.ID || c.Name}')" class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg text-sm"><i class="fas fa-trash"></i></button>
            </td>`;
        tbody.appendChild(tr);
    });
}

function showAddCommitteeModal() {
    const positions = (typeof COMMITTEE_POSITIONS !== 'undefined') ? COMMITTEE_POSITIONS.map(p => `<option value="${p}">${p}</option>`).join('') : '';
    Swal.fire({
        title: 'Add Committee Member',
        html: `
            <div class="text-left space-y-3">
                <input id="swalCName" class="swal2-input" placeholder="Name">
                <select id="swalCPos" class="swal2-input">${positions || '<option>President</option><option>Vice President</option><option>Secretary</option>'}</select>
                <input id="swalCPhoto" type="file" class="swal2-file" accept="image/*">
            </div>`,
        showCancelButton: true, confirmButtonText: 'Add', confirmButtonColor: '#16a34a',
        preConfirm: () => {
            const name = document.getElementById('swalCName').value.trim();
            if (!name) { Swal.showValidationMessage('Name is required'); return false; }
            return {
                name,
                position: document.getElementById('swalCPos').value,
                photoFile: document.getElementById('swalCPhoto').files[0]
            };
        }
    }).then(async (r) => {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const d = r.value;
        const photoURL = await uploadToCloudinary(d.photoFile);
        await apiCall('addCommittee', { Name: d.name, Position: d.position, PhotoURL: photoURL, OrderIndex: allCommittee.length });
        Swal.close();
        toast('Committee member added!');
        loadCommittee();
        loadDashboard();
    });
}

async function editCommittee(id) {
    const c = allCommittee.find(x => (x.ID || x.Name) === id);
    if (!c) return;
    const positions = (typeof COMMITTEE_POSITIONS !== 'undefined') ? COMMITTEE_POSITIONS.map(p => `<option value="${p}" ${p === c.Position ? 'selected' : ''}>${p}</option>`).join('') : '';
    const { value: formValues } = await Swal.fire({
        title: 'Edit Committee Member',
        html: `
            <div class="text-left space-y-3">
                <input id="swalCName" class="swal2-input" placeholder="Name" value="${c.Name || ''}">
                <select id="swalCPos" class="swal2-input">${positions || ''}</select>
                <input id="swalCPhoto" type="file" class="swal2-file" accept="image/*">
                ${c.PhotoURL ? `<p class="text-xs text-gray-400">Current: <a href="${c.PhotoURL}" target="_blank" class="text-primary-600 underline">view</a></p>` : ''}
            </div>`,
        showCancelButton: true, confirmButtonText: 'Update', confirmButtonColor: '#2563eb',
        preConfirm: () => {
            const name = document.getElementById('swalCName').value.trim();
            if (!name) { Swal.showValidationMessage('Name is required'); return false; }
            return {
                name, position: document.getElementById('swalCPos').value,
                photoFile: document.getElementById('swalCPhoto').files[0]
            };
        }
    });
    if (!formValues) return;
    Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    const photoURL = formValues.photoFile ? await uploadToCloudinary(formValues.photoFile) : (c.PhotoURL || '');
    await apiCall('updateCommittee', { ID: id, Name: formValues.name, Position: formValues.position, PhotoURL: photoURL });
    Swal.close();
    toast('Committee member updated!');
    loadCommittee();
}

async function deleteCommittee(id) {
    const r = await Swal.fire({ title: 'Delete Committee Member?', text: 'This cannot be undone.', icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Delete' });
    if (!r.isConfirmed) return;
    await apiCall('deleteCommittee', { id });
    toast('Committee member deleted.', 'info');
    loadCommittee();
    loadDashboard();
}

async function moveCommittee(index, direction) {
    const newIndex = index + direction;
    if (newIndex < 0 || newIndex >= allCommittee.length) return;
    [allCommittee[index], allCommittee[newIndex]] = [allCommittee[newIndex], allCommittee[index]];
    allCommittee.forEach((c, i) => c.OrderIndex = i);
    await apiCall('reorderCommittee', { items: allCommittee.map(c => ({ ID: c.ID || c.Name, OrderIndex: c.OrderIndex })) });
    loadCommittee();
}

/* ==============================
   GALLERY (CRUD)
   ============================== */
async function loadGallery() {
    const grid = document.getElementById('galleryGrid');
    if (!grid) { console.warn('[Admin] #galleryGrid not found'); return; }

    grid.innerHTML = '<div class="col-span-full text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading...</p></div>';
    const raw = await apiGet('getGallery');
    allGallery = Array.isArray(raw) ? raw : [];

    if (!allGallery.length) {
        grid.innerHTML = '<div class="col-span-full text-center py-12 text-gray-400"><i class="fas fa-images text-3xl mb-2"></i><p>No images yet</p></div>';
        return;
    }
    grid.innerHTML = '';
    allGallery.forEach(g => {
        const div = el('div', 'relative group rounded-2xl overflow-hidden border border-gray-200 bg-white');
        div.innerHTML = `
            <img src="${g.ImageURL}" class="w-full h-48 object-cover" onerror="this.src='assets/placeholder.png'">
            <div class="p-2.5">
                <p class="text-xs text-gray-500 truncate">${g.Caption || ''}</p>
            </div>
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                <button onclick="editGalleryCaption('${g.ID || g.ImageURL}')" class="bg-white text-gray-800 px-3 py-1.5 rounded-lg text-sm font-semibold"><i class="fas fa-pen mr-1"></i>Edit</button>
                <button onclick="deleteGalleryImage('${g.ID || g.ImageURL}')" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold"><i class="fas fa-trash mr-1"></i>Delete</button>
            </div>`;
        grid.appendChild(div);
    });
}

function showAddGalleryModal() {
    Swal.fire({
        title: 'Add Gallery Image',
        html: `
            <div class="text-left space-y-3">
                <input id="swalGCaption" class="swal2-input" placeholder="Caption">
                <input id="swalGPhoto" type="file" class="swal2-file" accept="image/*" required>
            </div>`,
        showCancelButton: true, confirmButtonText: 'Upload', confirmButtonColor: '#16a34a',
        preConfirm: () => {
            const file = document.getElementById('swalGPhoto').files[0];
            if (!file) { Swal.showValidationMessage('Please select an image'); return false; }
            return { caption: document.getElementById('swalGCaption').value.trim(), file };
        }
    }).then(async (r) => {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const d = r.value;
        const url = await uploadToCloudinary(d.file);
        if (!url) { Swal.close(); toast('Upload failed', 'error'); return; }
        await apiCall('addGallery', { ImageURL: url, Caption: d.caption });
        Swal.close();
        toast('Image added!');
        loadGallery();
        loadDashboard();
    });
}

async function editGalleryCaption(id) {
    const g = allGallery.find(x => (x.ID || x.ImageURL) === id);
    const { value: caption } = await Swal.fire({
        title: 'Edit Caption', input: 'text', inputValue: g ? g.Caption || '' : '',
        showCancelButton: true, confirmButtonText: 'Save'
    });
    if (caption === undefined) return;
    await apiCall('updateGallery', { ID: id, Caption: caption.trim() });
    toast('Caption updated!');
    loadGallery();
}

async function deleteGalleryImage(id) {
    const r = await Swal.fire({ title: 'Delete Image?', text: 'This cannot be undone.', icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Delete' });
    if (!r.isConfirmed) return;
    await apiCall('deleteGallery', { id });
    toast('Image deleted.', 'info');
    loadGallery();
    loadDashboard();
}

/* ==============================
   SETTINGS
   ============================== */
async function loadSettings() {
    const raw = await apiGet('getSettings');
    const s = {};
    if (Array.isArray(raw)) raw.forEach(x => s[x.Key] = x.Value);

    const setVal = (id, fallback) => {
        const el = document.getElementById(id);
        if (el) el.value = s[id.replace('set', '').toLowerCase()] || fallback || '';
    };

    const val = (key) => s[key] || '';

    const clubName = document.getElementById('setClubName');
    if (clubName) clubName.value = val('clubName') || 'Oath Club';
    const tagline = document.getElementById('setTagline');
    if (tagline) tagline.value = val('tagline') || '';
    const about = document.getElementById('setAbout');
    if (about) about.value = val('about') || '';
    const vision = document.getElementById('setVision');
    if (vision) vision.value = val('vision') || '';
    const mission = document.getElementById('setMission');
    if (mission) mission.value = val('mission') || '';
    const bkash = document.getElementById('setBkash');
    if (bkash) bkash.value = val('bkash') || '';
    const nagad = document.getElementById('setNagad');
    if (nagad) nagad.value = val('nagad') || '';
    const fee = document.getElementById('setFee');
    if (fee) fee.value = val('membershipFee') || '';
}

async function saveWebsiteSettings() {
    const data = {
        clubName: $('#setClubName')?.value?.trim() || '',
        tagline: $('#setTagline')?.value?.trim() || '',
        about: $('#setAbout')?.value?.trim() || '',
        vision: $('#setVision')?.value?.trim() || '',
        mission: $('#setMission')?.value?.trim() || '',
        bkash: $('#setBkash')?.value?.trim() || '',
        nagad: $('#setNagad')?.value?.trim() || '',
        membershipFee: $('#setFee')?.value?.trim() || ''
    };
    const logoFile = document.getElementById('setLogo')?.files?.[0];
    if (logoFile) {
        Swal.fire({ title: 'Uploading logo...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        data.logo = await uploadToCloudinary(logoFile);
    }
    await apiCall('saveSettings', data);
    toast('Settings saved!');
}

/* ==============================
   CONTACT SETTINGS
   ============================== */
async function loadContact() {
    const raw = await apiGet('getSettings');
    const s = {};
    if (Array.isArray(raw)) raw.forEach(x => s[x.Key] = x.Value);

    const phone = document.getElementById('setPhone');
    if (phone) phone.value = s.phone || '';
    const email = document.getElementById('setEmail');
    if (email) email.value = s.email || '';
    const address = document.getElementById('setAddress');
    if (address) address.value = s.address || '';
    const facebook = document.getElementById('setFacebook');
    if (facebook) facebook.value = s.facebook || '';
    const accUser = document.getElementById('accUser');
    if (accUser) accUser.value = s.adminUser || (typeof ADMIN_DEFAULT_USER !== 'undefined' ? ADMIN_DEFAULT_USER : 'admin');
    const accPass = document.getElementById('accPass');
    if (accPass) accPass.value = '';
}

async function saveContactSettings() {
    const data = {
        phone: $('#setPhone')?.value?.trim() || '',
        email: $('#setEmail')?.value?.trim() || '',
        address: $('#setAddress')?.value?.trim() || '',
        facebook: $('#setFacebook')?.value?.trim() || ''
    };
    const newUser = $('#accUser')?.value?.trim();
    const newPass = $('#accPass')?.value?.trim();
    if (newUser) data.adminUser = newUser;
    if (newPass) data.adminPass = newPass;
    await apiCall('saveContact', data);
    toast('Contact info saved!');
}
