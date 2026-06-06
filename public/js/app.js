// ============================================
// OATH CLUB - Main Application Script
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ========== NAVBAR ==========
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function () {
        navbar.classList.toggle('shadow-lg', window.scrollY > 50);
    });

    window.toggleMobileMenu = function () {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    };

    // ========== HERO CAROUSEL ==========
    let currentSlide = 0;
    const heroSlider = document.getElementById('heroSlider');
    const heroDots = document.getElementById('heroDots');

    function initCarousel() {
        if (!heroSlider) return;
        heroSlider.innerHTML = bannerImages.map((img, i) => `
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out ${i === 0 ? 'opacity-100' : 'opacity-0'}" data-index="${i}">
                <img src="${img}" alt="Banner ${i+1}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/800x500?text=Oath+Club+Banner+${i+1}'">
            </div>
        `).join('');
        heroDots.innerHTML = bannerImages.map((_, i) => `
            <button class="w-2.5 h-2.5 rounded-full transition-all duration-300 ${i === 0 ? 'bg-white scale-110' : 'bg-white/50'}" onclick="goToSlide(${i})"></button>
        `).join('');
    }

    window.goToSlide = function (index) {
        const slides = heroSlider.querySelectorAll('[data-index]');
        const dots = heroDots.querySelectorAll('button');
        slides.forEach((s, i) => {
            s.classList.toggle('opacity-100', i === index);
            s.classList.toggle('opacity-0', i !== index);
        });
        dots.forEach((d, i) => {
            d.className = i === index ? 'w-2.5 h-2.5 rounded-full bg-white scale-110 transition-all duration-300' : 'w-2.5 h-2.5 rounded-full bg-white/50 transition-all duration-300';
        });
        currentSlide = index;
    };

    if (heroSlider) {
        initCarousel();
        setInterval(() => goToSlide((currentSlide + 1) % bannerImages.length), 4000);
    }

    // ========== STATIC CONTENT (fallback defaults) ==========
    const setText = (id, text) => { const el = document.getElementById(id); if (el) el.textContent = text; };
    setText('aboutText', aboutText);
    setText('visionText', visionText);
    setText('missionText', missionText);
    setText('membershipFee', MEMBERSHIP_FEE);
    setText('bkashNumber', BKASH_NUMBER);
    setText('nagadNumber', NAGAD_NUMBER);
    setText('contactPhone', contactInfo.phone);
    setText('contactEmailContent', contactInfo.email);
    setText('contactAddress', contactInfo.address);
    const fbLink = document.getElementById('contactFacebook');
    if (fbLink) fbLink.href = contactInfo.facebook;

    document.getElementById('footerYear').textContent = new Date().getFullYear();
    const fp = document.getElementById('footerPhone');
    const fe = document.getElementById('footerEmail');
    const fa = document.getElementById('footerAddress');
    if (fp) fp.textContent = contactInfo.phone;
    if (fe) fe.textContent = contactInfo.email;
    if (fa) fa.textContent = contactInfo.address;

    // ========== WHY JOIN ==========
    const whyJoinGrid = document.getElementById('whyJoinGrid');
    if (whyJoinGrid) {
        whyJoinGrid.innerHTML = whyJoinPoints.map(p => `
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-primary-500" data-aos="fade-up">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                    <i class="fas ${p.icon} text-primary-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">${p.title}</h3>
                <p class="text-gray-600 text-sm">${p.desc}</p>
            </div>
        `).join('');
    }

    // ========== ACTIVITIES ==========
    const activitiesGrid = document.getElementById('activitiesGrid');
    if (activitiesGrid) {
        activitiesGrid.innerHTML = activities.map(a => `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2" data-aos="fade-up">
                <div class="h-52 overflow-hidden bg-gray-100">
                    <img src="${a.image}" alt="${a.title}" class="w-full h-full object-cover hover:scale-110 transition duration-500" onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center\\'><i class=\\'fas fa-hands-helping text-white text-6xl\\'></i></div>'">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">${a.title}</h3>
                    <p class="text-sm text-gray-500 mb-2">${a.titleEn}</p>
                    <p class="text-gray-600 leading-relaxed text-sm">${a.desc}</p>
                </div>
            </div>
        `).join('');
    }

    // ========== GALLERY (from GAS, fallback to static) ==========
    const galleryContainer = document.getElementById('galleryContainer');
    function renderGallery(images) {
        if (!galleryContainer) return;
        galleryContainer.innerHTML = images.length > 0
            ? images.map(g => `
                <a href="${g.imageUrl || g.src}" data-lightbox="club-gallery" data-title="${g.caption}" class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition h-48 md:h-56 block">
                    <img src="${g.imageUrl || g.src}" alt="${g.caption}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" onerror="this.closest('a').innerHTML='<div class=\\'w-full h-full bg-gray-200 flex items-center justify-center text-gray-400\\'><i class=\\'fas fa-image text-4xl\\'></i></div>'">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"><i class="fas fa-search-plus text-white text-3xl"></i></div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 group-hover:opacity-100 transition"><p class="text-white text-sm font-medium">${g.caption}</p></div>
                </a>
            `).join('')
            : '<div class="col-span-full text-center py-16 text-gray-400"><i class="fas fa-images text-5xl mb-4"></i><p>গ্যালারিতে কোনো ছবি নেই</p></div>';
    }
    // Use static gallery as default, GAS data will override if available
    renderGallery(galleryImages);

    // ========== CONTACT FORM ==========
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'বার্তা পাঠানো হয়েছে!',
                text: 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।',
                confirmButtonColor: '#15803d'
            });
            contactForm.reset();
        });
    }

    // ========== JOIN FORM ==========
    const joinForm = document.getElementById('joinForm');
    if (joinForm) {
        joinForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = joinForm.querySelector('button[type="submit"]');
            const originalBtnHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> যাচাই করা হচ্ছে...';

            try {
                const photoFile = document.getElementById('jPhoto').files[0];
                if (!photoFile) throw new Error('অনুগ্রহ করে একটি ছবি নির্বাচন করুন');
                if (!photoFile.type.startsWith('image/')) throw new Error('শুধুমাত্র ইমেজ ফাইল (JPG, PNG, etc.) আপলোড করা যাবে');
                const maxSize = 5 * 1024 * 1024;
                if (photoFile.size > maxSize) throw new Error('ছবির আকার ৫MB এর কম হতে হবে');

                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ছবি আপলোড হচ্ছে...';
                const cloudinaryResult = await uploadToCloudinary(photoFile);
                if (!cloudinaryResult.success) throw new Error('ছবি আপলোড ব্যর্থ হয়েছে:\n' + cloudinaryResult.error);

                const photoUrl = cloudinaryResult.url;

                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> জমা হচ্ছে...';
                const formData = {
                    action: 'register',
                    fullName: document.getElementById('jFullName').value,
                    fatherName: document.getElementById('jFatherName').value || '',
                    motherName: document.getElementById('jMotherName').value || '',
                    dob: document.getElementById('jDob').value,
                    phone: document.getElementById('jPhone').value,
                    email: document.getElementById('jEmail').value || '',
                    occupation: document.getElementById('jOccupation').value || '',
                    address: document.getElementById('jAddress').value || '',
                    bloodGroup: document.getElementById('jBloodGroup').value || '',
                    transactionId: document.getElementById('jTransactionId').value,
                    photoUrl: photoUrl
                };

                const result = await submitToGoogleSheet(formData);
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'আবেদন জমা দেওয়া হয়েছে!',
                        html: `
                            <div class="text-left">
                                <p><strong>মেম্বার আইডি:</strong> ${result.memberId}</p>
                                <p><strong>নাম:</strong> ${formData.fullName}</p>
                                <p><strong>ছবি:</strong> <span class="text-green-600">সফলভাবে আপলোড হয়েছে</span></p>
                                <p><strong>অবস্থা:</strong> <span class="text-yellow-600 font-semibold">বিবেচনাধীন</span></p>
                                <hr class="my-2">
                                <p class="text-sm text-gray-500">আপনার আবেদনটি পর্যালোচনা করা হবে। অনুমোদিত হলে আপনি সদস্য তালিকায় যুক্ত হবেন।</p>
                            </div>
                        `,
                        confirmButtonText: 'ঠিক আছে',
                        confirmButtonColor: '#15803d'
                    });
                    joinForm.reset();
                } else {
                    throw new Error(result.error || 'জমা দিতে ব্যর্থ হয়েছে');
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'ত্রুটি!', text: e.message, confirmButtonColor: '#dc2626' });
            }

            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        });
    }

    // ========== LOAD DATA FROM GAS ==========
    loadSettingsFromGAS();
    loadApprovedMembers();
    loadCommittee();
    loadGalleryFromGAS();

}); // end DOMContentLoaded

// ============================================
// LOAD SETTINGS FROM GAS
// ============================================
async function loadSettingsFromGAS() {
    if (!GAS_URL || GAS_URL.includes('YOUR_APPS_SCRIPT_ID')) return;
    try {
        const res = await fetch(GAS_URL + '?mode=settings', { method: 'GET', mode: 'cors' });
        if (!res.ok) return;
        const data = await res.json();
        if (!data.settings) return;
        const s = data.settings;
        if (s.clubName) {
            document.querySelectorAll('.site-name').forEach(el => el.textContent = s.clubName);
            document.title = s.clubName + ' - ' + SITE_TAGLINE;
        }
        if (s.aboutText) { const el = document.getElementById('aboutText'); if (el) el.textContent = s.aboutText; }
        if (s.visionText) { const el = document.getElementById('visionText'); if (el) el.textContent = s.visionText; }
        if (s.missionText) { const el = document.getElementById('missionText'); if (el) el.textContent = s.missionText; }
        if (s.phone) {
            document.querySelectorAll('.contact-phone').forEach(el => el.textContent = s.phone);
        }
        if (s.email) {
            document.querySelectorAll('.contact-email').forEach(el => el.textContent = s.email);
        }
        if (s.address) {
            document.querySelectorAll('.contact-address').forEach(el => el.textContent = s.address);
        }
        if (s.facebook) {
            const el = document.getElementById('contactFacebook');
            if (el) el.href = s.facebook;
        }
        if (s.siteTagline) {
            const el = document.querySelector('.hero-tagline');
            if (el) el.textContent = s.siteTagline;
        }
        console.log('[GAS] Settings loaded successfully');
    } catch (e) {
        console.warn('[GAS] Could not load settings, using defaults');
    }
}

// ============================================
// LOAD GALLERY FROM GAS
// ============================================
async function loadGalleryFromGAS() {
    const container = document.getElementById('galleryContainer');
    if (!container) return;
    if (!GAS_URL || GAS_URL.includes('YOUR_APPS_SCRIPT_ID')) return;
    try {
        const res = await fetch(GAS_URL + '?mode=gallery', { method: 'GET', mode: 'cors' });
        if (!res.ok) return;
        const data = await res.json();
        if (data.gallery && data.gallery.length > 0) {
            // Override static gallery with GAS data
            renderGalleryItems(data.gallery, container);
            console.log('[GAS] Gallery loaded:', data.gallery.length, 'items');
        }
    } catch (e) {
        console.warn('[GAS] Could not load gallery, using static defaults');
    }
}

function renderGalleryItems(items, container) {
    container.innerHTML = items.map(g => `
        <a href="${g.imageUrl}" data-lightbox="club-gallery" data-title="${g.caption}" class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition h-48 md:h-56 block">
            <img src="${g.imageUrl}" alt="${g.caption}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" onerror="this.closest('a').innerHTML='<div class=\\'w-full h-full bg-gray-200 flex items-center justify-center text-gray-400\\'><i class=\\'fas fa-image text-4xl\\'></i></div>'">
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"><i class="fas fa-search-plus text-white text-3xl"></i></div>
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 group-hover:opacity-100 transition"><p class="text-white text-sm font-medium">${g.caption}</p></div>
        </a>
    `).join('');
}

// ============================================
// LOAD COMMITTEE FROM GAS
// ============================================
async function loadCommittee() {
    const container = document.getElementById('committeeContainer');
    if (!container) return;
    if (!GAS_URL || GAS_URL.includes('YOUR_APPS_SCRIPT_ID')) {
        container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">কমিটি তথ্য কনফিগার করা হয়নি</div>';
        return;
    }
    try {
        const res = await fetch(GAS_URL + '?mode=committee', { method: 'GET', mode: 'cors' });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (!data.committee || data.committee.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">কোনো কমিটি সদস্য নেই</div>';
            return;
        }
        container.innerHTML = data.committee.map(m => `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 text-center" data-aos="fade-up">
                <div class="h-48 overflow-hidden bg-gray-100">
                    ${m.photoUrl
                        ? `<img src="${m.photoUrl}" alt="${m.name}" class="w-full h-full object-cover" onerror="this.closest('div').innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200\\'><i class=\\'fas fa-user-circle text-primary-600 text-7xl\\'></i></div>'">`
                        : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200"><i class="fas fa-user-circle text-primary-600 text-7xl"></i></div>`}
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg">${m.name}</h3>
                    <p class="text-primary-600 text-sm font-semibold">${m.position}</p>
                </div>
            </div>
        `).join('');
    } catch (e) {
        console.error('[GAS] Failed to load committee:', e);
        container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">কমিটি তথ্য লোড করা যায়নি</div>';
    }
}

// ============================================
// LOAD APPROVED MEMBERS
// ============================================
async function loadApprovedMembers() {
    const container = document.getElementById('membersContainer');
    if (!container) return;
    if (!GAS_URL || GAS_URL.includes('YOUR_APPS_SCRIPT_ID')) {
        container.innerHTML = '<div class="col-span-full text-center py-16"><i class="fas fa-users text-6xl text-gray-300 mb-4"></i><p class="text-xl text-gray-500">সদস্য তথ্য লোড করা যায়নি</p></div>';
        return;
    }
    try {
        const res = await fetch(GAS_URL + '?mode=members', { method: 'GET', mode: 'cors' });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (!data.members || data.members.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-16"><i class="fas fa-users text-6xl text-gray-300 mb-4"></i><p class="text-xl text-gray-500">কোনো অনুমোদিত সদস্য নেই</p></div>';
            return;
        }
        // Filter only approved, exclude committee members (shown in committee section)
        const approved = data.members.filter(m => m.status === 'Approved');
        if (approved.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-16"><i class="fas fa-users text-6xl text-gray-300 mb-4"></i><p class="text-xl text-gray-500">কোনো অনুমোদিত সদস্য নেই</p></div>';
            return;
        }
        container.innerHTML = approved.map(m => `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 text-center" data-aos="fade-up">
                <div class="h-48 overflow-hidden bg-gray-100">
                    ${m.photoUrl
                        ? `<img src="${m.photoUrl}" alt="${m.fullName}" class="w-full h-full object-cover" onerror="this.closest('div').innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200\\'><i class=\\'fas fa-user-circle text-primary-600 text-7xl\\'></i></div>'">`
                        : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200"><i class="fas fa-user-circle text-primary-600 text-7xl"></i></div>`}
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg">${m.fullName}</h3>
                    <p class="text-primary-600 text-sm font-semibold">${m.memberId}</p>
                    ${m.position ? `<p class="text-gray-500 text-xs mt-1">${m.position}</p>` : ''}
                </div>
            </div>
        `).join('');
    } catch (e) {
        console.error('[GAS] Failed to load members:', e);
        container.innerHTML = '<div class="col-span-full text-center py-16"><i class="fas fa-users text-6xl text-gray-300 mb-4"></i><p class="text-xl text-gray-500">সদস্য তথ্য লোড করা যায়নি</p></div>';
    }
}

// ========== MEMBER SEARCH ==========
function filterMembers() {
    const search = document.getElementById('memberSearch')?.value.toLowerCase() || '';
    const cards = document.querySelectorAll('#membersContainer > div');
    cards.forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
}

// ============================================
// CLOUDINARY IMAGE UPLOAD
// ============================================
async function uploadToCloudinary(file) {
    if (!CLOUDINARY_CLOUD_NAME || CLOUDINARY_CLOUD_NAME === 'YOUR_CLOUD_NAME') {
        return { success: false, error: 'Cloudinary ক্লাউড নাম কনফিগার করা হয়নি' };
    }
    if (!CLOUDINARY_UPLOAD_PRESET || CLOUDINARY_UPLOAD_PRESET === 'YOUR_UPLOAD_PRESET') {
        return { success: false, error: 'Cloudinary আপলোড প্রিসেট কনফিগার করা হয়নি' };
    }
    const url = `https://api.cloudinary.com/v1_1/${CLOUDINARY_CLOUD_NAME}/image/upload`;
    const formData = new FormData();
    formData.append('file', file);
    formData.append('upload_preset', CLOUDINARY_UPLOAD_PRESET);
    try {
        const res = await fetch(url, { method: 'POST', body: formData });
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); } catch (e) {
            return { success: false, error: 'JSON parse error: ' + text.substring(0, 200) };
        }
        if (data.secure_url) return { success: true, url: data.secure_url };
        if (data.error) return { success: false, error: 'Cloudinary: ' + (data.error.message || JSON.stringify(data.error)) };
        return { success: false, error: 'Unknown Cloudinary response' };
    } catch (e) {
        return { success: false, error: 'Network error: ' + e.message };
    }
}

// ============================================
// GOOGLE SHEET SUBMISSION (via Apps Script)
// ============================================
async function submitToGoogleSheet(data) {
    if (!GAS_URL || GAS_URL.includes('YOUR_APPS_SCRIPT_ID')) {
        return { success: false, error: 'Google Apps Script URL কনফিগার করা হয়নি' };
    }
    try {
        const res = await fetch(GAS_URL, {
            method: 'POST', mode: 'no-cors',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        return { success: true, memberId: data.action === 'register' ? 'OC-____' : '', message: 'Submitted' };
    } catch (e) {
        return { success: false, error: 'Network error: ' + e.message };
    }
}

// ============================================
// ADMIN API FUNCTION (for admin.js)
// ============================================
async function gasPost(data) {
    if (!GAS_URL || GAS_URL.includes('YOUR_APPS_SCRIPT_ID')) {
        return { error: 'GAS URL not configured' };
    }
    try {
        const res = await fetch(GAS_URL, {
            method: 'POST', mode: 'cors',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        if (!res.ok) return { error: 'HTTP ' + res.status };
        return await res.json();
    } catch (e) {
        // fallback to no-cors
        try {
            await fetch(GAS_URL, {
                method: 'POST', mode: 'no-cors',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            return { success: true };
        } catch (e2) {
            return { error: e2.message };
        }
    }
}
