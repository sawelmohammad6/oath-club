// ============================================
// OATH CLUB - Main Application Script
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // ========== NAVBAR ==========
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-lg');
        } else {
            navbar.classList.remove('shadow-lg');
        }
    });

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
    window.toggleMobileMenu = toggleMobileMenu;

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
            <button class="w-3 h-3 rounded-full transition-all duration-300 ${i === 0 ? 'bg-white scale-110' : 'bg-white/50'}" onclick="goToSlide(${i})"></button>
        `).join('');
    }

    window.goToSlide = function(index) {
        const slides = heroSlider.querySelectorAll('[data-index]');
        const dots = heroDots.querySelectorAll('button');
        slides.forEach((s, i) => {
            s.classList.toggle('opacity-100', i === index);
            s.classList.toggle('opacity-0', i !== index);
        });
        dots.forEach((d, i) => {
            d.className = i === index ? 'w-3 h-3 rounded-full bg-white scale-110 transition-all duration-300' : 'w-3 h-3 rounded-full bg-white/50 transition-all duration-300';
        });
        currentSlide = index;
    };

    function nextSlide() {
        goToSlide((currentSlide + 1) % bannerImages.length);
    }

    if (heroSlider) {
        initCarousel();
        setInterval(nextSlide, 4000);
    }

    // ========== ABOUT ==========
    const aboutEl = document.getElementById('aboutText');
    if (aboutEl) aboutEl.textContent = aboutText;

    // ========== VISION ==========
    const visionEl = document.getElementById('visionText');
    if (visionEl) visionEl.textContent = visionText;

    // ========== MISSION ==========
    const missionEl = document.getElementById('missionText');
    if (missionEl) missionEl.textContent = missionText;

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

    // ========== GALLERY ==========
    const galleryContainer = document.getElementById('galleryContainer');
    if (galleryContainer) {
        galleryContainer.innerHTML = galleryImages.map((g, i) => `
            <a href="${g.src}" data-lightbox="club-gallery" data-title="${g.caption}" class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition h-48 md:h-56 block" onerror="this.innerHTML='<div class=\\'w-full h-full bg-gray-200 flex items-center justify-center text-gray-400\\'><i class=\\'fas fa-image text-4xl\\'></i></div>'">
                <img src="${g.src}" alt="${g.caption}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" onerror="this.closest('a').innerHTML='<div class=\\'w-full h-full bg-gray-200 flex items-center justify-center text-gray-400\\'><i class=\\'fas fa-image text-4xl\\'></i></div>'">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <i class="fas fa-search-plus text-white text-3xl"></i>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 group-hover:opacity-100 transition">
                    <p class="text-white text-sm font-medium">${g.caption}</p>
                </div>
            </a>
        `).join('');
    }

    // ========== MEMBERS ==========
    loadMembers();

    // ========== CONTACT ==========
    const contactPhone = document.getElementById('contactPhone');
    const contactEmail = document.getElementById('contactEmail');
    const contactAddress = document.getElementById('contactAddress');
    const contactFacebook = document.getElementById('contactFacebook');

    if (contactPhone) contactPhone.textContent = contactInfo.phone;
    if (contactEmail) contactEmail.innerHTML = `<a href="mailto:${contactInfo.email}" class="text-primary-600 hover:underline">${contactInfo.email}</a>`;
    if (contactAddress) contactAddress.textContent = contactInfo.address;
    if (contactFacebook) contactFacebook.href = contactInfo.facebook;

    // ========== FOOTER ==========
    const footerYear = document.getElementById('footerYear');
    if (footerYear) footerYear.textContent = new Date().getFullYear();

    // ========== JOIN FORM ==========
    const membershipFeeEl = document.getElementById('membershipFee');
    const bkashEl = document.getElementById('bkashNumber');
    const nagadEl = document.getElementById('nagadNumber');

    if (membershipFeeEl) membershipFeeEl.textContent = MEMBERSHIP_FEE;
    if (bkashEl) bkashEl.textContent = BKASH_NUMBER;
    if (nagadEl) nagadEl.textContent = NAGAD_NUMBER;

    const joinForm = document.getElementById('joinForm');
    if (joinForm) {
        joinForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                id: Date.now(),
                memberId: generateMemberId(),
                fullName: document.getElementById('jFullName').value,
                fatherName: document.getElementById('jFatherName').value,
                motherName: document.getElementById('jMotherName').value,
                dob: document.getElementById('jDob').value,
                phone: document.getElementById('jPhone').value,
                email: document.getElementById('jEmail').value,
                occupation: document.getElementById('jOccupation').value,
                address: document.getElementById('jAddress').value,
                bloodGroup: document.getElementById('jBloodGroup').value,
                transactionId: document.getElementById('jTransactionId').value,
                status: 'pending',
                createdAt: new Date().toISOString()
            };

            // Handle photo
            const photoFile = document.getElementById('jPhoto').files[0];
            if (photoFile) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    formData.photo = ev.target.result;
                    saveMember(formData);
                };
                reader.readAsDataURL(photoFile);
            } else {
                formData.photo = '';
                saveMember(formData);
            }
        });
    }

    function saveMember(data) {
        const members = JSON.parse(localStorage.getItem('oathClubMembers') || '[]');
        members.push(data);
        localStorage.setItem('oathClubMembers', JSON.stringify(members));
        showSuccessModal(data);
    }

    function showSuccessModal(data) {
        Swal.fire({
            icon: 'success',
            title: 'আবেদন জমা দেওয়া হয়েছে!',
            html: `
                <div class="text-left">
                    <p><strong>মেম্বার আইডি:</strong> ${data.memberId}</p>
                    <p><strong>নাম:</strong> ${data.fullName}</p>
                    <p><strong>অবস্থা:</strong> <span class="text-yellow-600 font-semibold">বিবেচনাধীন</span></p>
                    <hr class="my-2">
                    <p class="text-sm text-gray-500">আপনার আবেদনটি পর্যালোচনা করা হবে। অনুমোদিত হলে আপনি মেম্বার তালিকায় যুক্ত হবেন।</p>
                </div>
            `,
            confirmButtonText: 'ঠিক আছে',
            confirmButtonColor: '#15803d'
        });
        joinForm.reset();
        loadMembers();
    }

    // ========== CONTACT FORM ==========
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const data = {
                id: Date.now(),
                name: document.getElementById('cName').value,
                email: document.getElementById('cEmail').value,
                phone: document.getElementById('cPhone').value,
                subject: document.getElementById('cSubject').value,
                message: document.getElementById('cMessage').value,
                createdAt: new Date().toISOString()
            };
            const messages = JSON.parse(localStorage.getItem('oathClubMessages') || '[]');
            messages.push(data);
            localStorage.setItem('oathClubMessages', JSON.stringify(messages));
            Swal.fire({
                icon: 'success',
                title: 'বার্তা পাঠানো হয়েছে!',
                text: 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।',
                confirmButtonColor: '#15803d'
            });
            contactForm.reset();
        });
    }
});

// ========== MEMBER ID GENERATION ==========
function generateMemberId() {
    const members = JSON.parse(localStorage.getItem('oathClubMembers') || '[]');
    const count = members.length + 1;
    return `${MEMBER_ID_PREFIX}-${String(count).padStart(4, '0')}`;
}

// ========== LOAD MEMBERS ==========
function loadMembers() {
    const membersContainer = document.getElementById('membersContainer');
    if (!membersContainer) return;

    const allMembers = JSON.parse(localStorage.getItem('oathClubMembers') || '[]');
    const approvedMembers = allMembers.filter(m => m.status === 'approved');

    // Sort by member ID
    approvedMembers.sort((a, b) => {
        const numA = parseInt(a.memberId.split('-')[1]);
        const numB = parseInt(b.memberId.split('-')[1]);
        return numA - numB;
    });

    if (approvedMembers.length === 0) {
        membersContainer.innerHTML = '<div class="col-span-full text-center py-16"><i class="fas fa-users text-6xl text-gray-300 mb-4"></i><p class="text-xl text-gray-500">কোনো অনুমোদিত সদস্য নেই</p></div>';
        return;
    }

    membersContainer.innerHTML = approvedMembers.map(m => `
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 text-center" data-aos="fade-up">
            <div class="h-48 overflow-hidden bg-gray-100">
                ${m.photo
                    ? `<img src="${m.photo}" alt="${m.fullName}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200"><i class="fas fa-user-circle text-primary-600 text-7xl"></i></div>`}
            </div>
            <div class="p-4">
                <h3 class="font-bold text-gray-800 text-lg">${m.fullName}</h3>
                <p class="text-primary-600 text-sm font-semibold">${m.memberId}</p>
            </div>
        </div>
    `).join('');
}

// ========== ADMIN SECTION (Verification hidden in UI) ==========
// Admin can verify members by calling:
// approveMember(memberId) or rejectMember(memberId)
function approveMember(memberId) {
    let members = JSON.parse(localStorage.getItem('oathClubMembers') || '[]');
    members = members.map(m => {
        if (m.id === memberId) { m.status = 'approved'; }
        return m;
    });
    localStorage.setItem('oathClubMembers', JSON.stringify(members));
    loadMembers();
}

function rejectMember(memberId) {
    let members = JSON.parse(localStorage.getItem('oathClubMembers') || '[]');
    members = members.map(m => {
        if (m.id === memberId) { m.status = 'rejected'; }
        return m;
    });
    localStorage.setItem('oathClubMembers', JSON.stringify(members));
    loadMembers();
}

function getPendingMembers() {
    const all = JSON.parse(localStorage.getItem('oathClubMembers') || '[]');
    return all.filter(m => m.status === 'pending');
}
