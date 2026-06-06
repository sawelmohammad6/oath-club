// ============================================
// OATH CLUB - Editable Content Configuration
// ============================================

// --- Google Apps Script Web App URL (REPLACE THIS) ---
const GAS_URL = "https://script.google.com/macros/s/AKfycbzNw9FlFQxWFDfuWBr7pgzCUc9Y_dNjp17ii2ZzzPtwEYaUdwi2YSq0N_mnx_5TUGZm/exec";

// --- Cloudinary Configuration ---
const CLOUDINARY_CLOUD_NAME = "diboslkyo";
const CLOUDINARY_UPLOAD_PRESET = "oath_club";
const CLOUDINARY_URL = `https://api.cloudinary.com/v1_1/${CLOUDINARY_CLOUD_NAME}/image/upload`;

// --- Admin Default Credentials (change after first login) ---
const ADMIN_DEFAULT_USER = "admin";
const ADMIN_DEFAULT_PASS = "admin123";

// --- Committee Positions (in display order) ---
const COMMITTEE_POSITIONS = [
    "সভাপতি",
    "সহ-সভাপতি",
    "সাধারণ সম্পাদক",
    "যুগ্ম সম্পাদক",
    "সাংগঠনিক সম্পাদক",
    "কোষাধ্যক্ষ",
    "সদস্য"
];

// --- Logo ---
const LOGO_PATH = "assets/oath.png";

const SITE_NAME = "Oath Club";
const SITE_TAGLINE = "একতা, নেতৃত্ব ও সমাজসেবায় আমাদের অঙ্গীকার";

// --- Hero Banner Images ---
const bannerImages = [
    "assets/banner-1.jpeg",
    "assets/banner-2.jpeg",
    "assets/banner-3.jpeg",
    "assets/banner-4.jpeg",
    "assets/banner-5.jpeg",
    "assets/banner-6.jpeg"
];

// --- About ---
const aboutText = `ওথ ক্লাব একটি কমিউনিটি ভিত্তিক সংগঠন যা সমাজকল্যাণ, যুব উন্নয়ন, শিক্ষা সহায়তা, পরিবেশ সচেতনতা এবং ইতিবাচক পরিবর্তনের জন্য নিবেদিত। আমরা বিশ্বাস করি একতা ও নেতৃত্বের মাধ্যমে একটি সুন্দর সমাজ গড়া সম্ভব।`;

// --- Vision ---
const visionText = `একটি সমাজ গঠন করা যেখানে প্রতিটি ব্যক্তি সম্প্রদায়ের উন্নয়ন ও সমাজকল্যাণে অবদান রাখে। আমরা এমন একটি ভবিষ্যৎ দেখতে চাই যেখানে সবাই মিলে কাজ করে একটি টেকসই ও অন্তর্ভুক্তিমূলক সমাজ গড়ে তোলার জন্য।`;

// --- Mission ---
const missionText = `যুব নেতৃত্ব উন্নয়ন, শিক্ষা সহায়তা প্রদান, পরিবেশ সংরক্ষণ কর্মকাণ্ড, সামাজিক সম্প্রীতি প্রচার এবং সম্প্রদায়ের সেবার মাধ্যমে ইতিবাচক পরিবর্তন আনা। আমরা সবার জন্য একটি উন্নত ভবিষ্যৎ গড়ে তুলতে প্রতিশ্রুতিবদ্ধ।`;

// --- Why Join ---
const whyJoinPoints = [
    { icon: "fa-id-card", title: "অফিসিয়াল মেম্বারশিপ আইডি", desc: "পাওয়ার জন্য অনন্য Oath Club মেম্বারশিপ আইডি কার্ড" },
    { icon: "fa-hands-helping", title: "সেচ্ছাসেবকের সুযোগ", desc: "কমিউনিটি সার্ভিস কার্যক্রমে অংশগ্রহণ" },
    { icon: "fa-network-wired", title: "নেটওয়ার্কিং", desc: "একই রকম চিন্তাভাবনার মানুষের সাথে সংযোগ" },
    { icon: "fa-chart-line", title: "নেতৃত্ব উন্নয়ন", desc: "প্রশিক্ষণের মাধ্যমে নেতৃত্বের দক্ষতা উন্নয়ন" },
    { icon: "fa-gift", title: "ইভেন্ট অ্যাক্সেস", desc: "ক্লাব ইভেন্ট ও প্রোগ্রামে অগ্রাধিকার প্রবেশাধিকার" }
];

// --- Activities ---
const activities = [
    {
        title: "বৃক্ষরোপণ",
        titleEn: "Tree Plantation",
        desc: "পরিবেশ রক্ষায় আমরা নিয়মিত বৃক্ষরোপণ কর্মসূচি পরিচালনা করি। সবুজায়নের মাধ্যমে আমরা একটি টেকসই ভবিষ্যৎ গড়ে তুলতে চাই।",
        image: "assets/tree.jpg"
    },
    {
        title: "রক্তদান",
        titleEn: "Blood Donation",
        desc: "জীবন বাঁচাতে নিয়মিত রক্তদান ক্যাম্পের আয়োজন। আমাদের রক্তদান কার্যক্রম ইতিমধ্যে শত শত রোগীকে সহায়তা করেছে।",
        image: "assets/blood.jpeg"
    },
    {
        title: "শিক্ষা সহায়তা",
        titleEn: "Educational Support",
        desc: "অসহায় ও মেধাবী শিক্ষার্থীদের জন্য বৃত্তি ও শিক্ষা সামগ্রী প্রদান। শিক্ষার আলো ছড়িয়ে দিতেই আমাদের এই প্রচেষ্টা।",
        image: "assets/education.jpeg"
    },
    {
        title: "শীতবস্ত্র বিতরণ",
        titleEn: "Winter Clothing",
        desc: "শীতার্ত মানুষের পাশে দাঁড়িয়ে উষ্ণ পোশাক বিতরণ। প্রতিবছর আমরা শত শত পরিবারের মাঝে শীতবস্ত্র পৌঁছে দেই।",
        image: "assets/winter.jpg"
    },
    {
        title: "খেলাধুলা",
        titleEn: "Community Sports",
        desc: "খেলাধুলা প্রোগ্রামের মাধ্যমে মানুষের স্বাস্থ্য ও সামাজিক যোগাযোগ উন্নয়ন।",
        image: "assets/sports.jpeg"
    },
    {
        title: "স্বাস্থ্য সচেতনতা",
        titleEn: "Health Awareness",
        desc: "স্বাস্থ্য সচেতনতা ক্যাম্পেইন ও বিনামূল্যে চিকিৎসা ক্যাম্পের আয়োজন। সুস্থ সমাজ গড়াই আমাদের লক্ষ্য।",
        image: "assets/health.jpeg"
    }
];

// --- Gallery (static fallback, overridden by GAS data) ---
const galleryImages = [
    { src: "assets/gallery-1.jpeg", caption: "Oath Club Annual Event 2025" },
    { src: "assets/gallery-2.jpeg", caption: "Tree Plantation Program" },
    { src: "assets/gallery-3.jpeg", caption: "Blood Donation Camp" },
    { src: "assets/gallery-4.jpeg", caption: "Educational Support Initiative" },
    { src: "assets/gallery-5.jpeg", caption: "Winter Clothing Distribution" },
    { src: "assets/gallery-6.jpeg", caption: "Community Cleaning Drive" },
    { src: "assets/gallery-7.jpeg", caption: "Health Awareness Camp" },
    { src: "assets/gallery-8.jpeg", caption: "Members Meetup" }
];

// --- Contact ---
const contactInfo = {
    phone: "+880 1700-000000",
    email: "info@oathclub.org",
    facebook: "https://facebook.com/oathclub",
    address: "১২৩, ক্লাব রোড, ঢাকা, বাংলাদেশ"
};

// --- Membership ---
const MEMBERSHIP_FEE = 100;
const BKASH_NUMBER = "01913474094";
const NAGAD_NUMBER = "01913474094";
