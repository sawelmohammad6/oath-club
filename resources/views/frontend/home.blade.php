@extends('layouts.app')

@section('content')

<!-- HERO CAROUSEL -->
<section id="home" class="bg-gray-50 pb-8">
    <div class="w-full max-w-[1900px] mx-auto px-4 sm:px-5">
        <div class="grid lg:grid-cols-[3fr_1fr] gap-5 items-stretch">
            <div class="relative h-[330px] sm:h-[430px] lg:h-[75vh] lg:min-h-[520px] lg:max-h-[660px] rounded-[18px] overflow-hidden shadow-[0_22px_60px_rgba(15,23,42,0.14)] bg-gray-200">
                <div id="heroSlider" class="relative w-full h-full">
                    @foreach($bannerImages as $i => $banner)
                    <div class="hero-slide absolute inset-0 transition-all duration-700 ease-in-out {{ $i === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-[1.02]' }}" data-slide="{{ $i }}">
                        <img src="{{ $banner }}" alt="Oath Club banner {{ $i + 1 }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>

                <button type="button" class="hero-prev absolute left-4 sm:left-5 top-1/2 -translate-y-1/2 z-20 h-12 w-12 rounded-full bg-white/40 hover:bg-white/65 text-white shadow-lg backdrop-blur-md flex items-center justify-center transition" aria-label="Previous banner">
                    <i class="fas fa-chevron-left text-2xl drop-shadow"></i>
                </button>
                <button type="button" class="hero-next absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 z-20 h-12 w-12 rounded-full bg-white/40 hover:bg-white/65 text-white shadow-lg backdrop-blur-md flex items-center justify-center transition" aria-label="Next banner">
                    <i class="fas fa-chevron-right text-2xl drop-shadow"></i>
                </button>

                <div id="heroDots" class="absolute bottom-5 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2.5">
                    @foreach($bannerImages as $i => $banner)
                        <button type="button" class="hero-dot h-4 w-4 rounded-full border border-white/60 transition-all duration-300 {{ $i === 0 ? 'bg-white scale-110' : 'bg-white/45' }}" aria-label="Go to banner {{ $i + 1 }}" data-slide-target="{{ $i }}"></button>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-5 h-full">
                    <div class="flex-1 bg-gradient-to-br from-green-600 to-green-500 rounded-[18px] p-6 flex flex-col justify-center items-center text-white shadow-[0_22px_60px_rgba(15,23,42,0.14)] overflow-hidden relative">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                        <i class="fas fa-user-plus text-3xl mb-3"></i>
                        <h3 class="text-xl font-bold mb-1">Join Our Club</h3>
                        <p class="text-white/80 text-sm text-center mb-4">Be a part of Oath Club</p>
                        <a href="#join" class="inline-block px-6 py-2 bg-white text-green-700 rounded-full font-semibold text-sm hover:bg-green-50 transition shadow-md">Join Now</a>
                    </div>

                    <div class="flex-1 bg-gradient-to-br from-red-600 to-red-500 rounded-[18px] p-6 flex flex-col justify-center items-center text-white shadow-[0_22px_60px_rgba(15,23,42,0.14)] overflow-hidden relative">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                        <i class="fas fa-info-circle text-3xl mb-3"></i>
                        <h3 class="text-xl font-bold mb-1">Learn More</h3>
                        <p class="text-white/80 text-sm text-center mb-4">About Oath Club</p>
                        <a href="#about" class="inline-block px-6 py-2 bg-white text-red-700 rounded-full font-semibold text-sm hover:bg-red-50 transition shadow-md">Learn More</a>
                    </div>

                    <div class="flex-1 bg-gradient-to-br from-gray-100 to-gray-50 rounded-[18px] p-6 flex flex-col justify-center items-center shadow-[0_22px_60px_rgba(15,23,42,0.14)] overflow-hidden relative border border-gray-200">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-gray-200/50 rounded-full"></div>
                        <i class="fas fa-hand-holding-heart text-3xl mb-3 text-gray-700"></i>
                        <h3 class="text-xl font-bold mb-1 text-gray-800">Donate</h3>
                        <p class="text-gray-500 text-sm text-center mb-4">Support our causes</p>
                        <a href="{{ route('donation') }}" class="inline-block px-6 py-2 bg-primary-600 text-white rounded-full font-semibold text-sm hover:bg-primary-700 transition shadow-md">Donate Now</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

<!-- ABOUT -->
<section id="about" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">আমাদের সম্পর্কে</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $settings['club_name'] ?? 'Oath Club' }}</h2>
            <p class="text-gray-600 max-w-3xl mx-auto leading-relaxed text-lg">{{ $settings['about_text'] ?? '' }}</p>
        </div>
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            <div id="vision" class="bg-primary-50 rounded-2xl p-8 lg:p-10 border border-primary-100" data-aos="fade-right">
                <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-5"><i class="fas fa-eye text-white text-2xl"></i></div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">আমাদের লক্ষ্য</h3>
                <p class="text-gray-600 leading-relaxed">{{ $settings['vision_text'] ?? '' }}</p>
            </div>
            <div id="mission" class="bg-primary-50 rounded-2xl p-8 lg:p-10 border border-primary-100" data-aos="fade-left">
                <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-5"><i class="fas fa-bullseye text-white text-2xl"></i></div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">আমাদের উদ্দেশ্য</h3>
                <p class="text-gray-600 leading-relaxed">{{ $settings['mission_text'] ?? '' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- WHY JOIN -->
<section class="section-padding bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">কেন জয়েন করবেন?</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">সদস্য হওয়ার সুবিধা</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            @php $reasons = [ ['icon'=>'fa-id-card','title'=>'অফিসিয়াল মেম্বারশিপ আইডি','desc'=>'পাওয়ার জন্য অনন্য Oath Club মেম্বারশিপ আইডি কার্ড'], ['icon'=>'fa-hands-helping','title'=>'সেচ্ছাসেবকের সুযোগ','desc'=>'কমিউনিটি সার্ভিস কার্যক্রমে অংশগ্রহণ'], ['icon'=>'fa-network-wired','title'=>'নেটওয়ার্কিং','desc'=>'একই রকম চিন্তাভাবনার মানুষের সাথে সংযোগ'], ['icon'=>'fa-chart-line','title'=>'নেতৃত্ব উন্নয়ন','desc'=>'প্রশিক্ষণের মাধ্যমে নেতৃত্বের দক্ষতা উন্নয়ন'], ['icon'=>'fa-gift','title'=>'ইভেন্ট অ্যাক্সেস','desc'=>'ক্লাব ইভেন্ট ও প্রোগ্রামে অগ্রাধিকার প্রবেশাধিকার'] ]; @endphp
            @foreach($reasons as $r)
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-xl transition border border-gray-100" data-aos="fade-up">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-4"><i class="fas {{ $r['icon'] }} text-primary-600 text-xl"></i></div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $r['title'] }}</h3>
                <p class="text-gray-500 text-sm">{{ $r['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ACTIVITIES -->
<section id="activities" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">আমাদের কার্যক্রম</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">আমরা যা করি</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @forelse($activities as $activity)
            @php
            $link = match($activity->title_en) {
                'Tree Plantation' => route('activity.detail', 'tree-plantation'),
                'Blood Donation' => route('blood-donors'),
                'Educational Support' => route('activity.detail', 'educational-support'),
                'Winter Clothing' => route('activity.detail', 'winter-clothing'),
                'Sports' => route('sports'),
                'Health Awareness' => route('activity.detail', 'health-awareness'),
                default => route('activity.detail', \Illuminate\Support\Str::slug($activity->title_en ?? $activity->title)),
            };
            @endphp
            <a href="{{ $link }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition border border-gray-100 block" data-aos="fade-up">
                <div class="h-52 overflow-hidden">
                    <img src="{{ $activity->image ? asset('storage/'.$activity->image) : 'https://via.placeholder.com/400x300?text='.$activity->title }}" alt="{{ $activity->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $activity->title }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $activity->description }}</p>
                </div>
            </a>
            @empty
            @php $fallbackLinks = [route('activity.detail', 'tree-plantation'), route('blood-donors'), route('activity.detail', 'educational-support'), route('activity.detail', 'winter-clothing'), route('sports'), route('activity.detail', 'health-awareness')]; @endphp
            @foreach(['বৃক্ষরোপণ','রক্তদান','শিক্ষা সহায়তা','শীতবস্ত্র বিতরণ','খেলাধুলা','স্বাস্থ্য সচেতনতা'] as $i => $title)
            <a href="{{ $fallbackLinks[$i] }}" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 block group hover:shadow-xl transition" data-aos="fade-up">
                <div class="h-52 bg-gray-200 flex items-center justify-center"><i class="fas fa-image text-gray-400 text-5xl"></i></div>
                <div class="p-5"><h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-primary-600 transition">{{ $title }}</h3><p class="text-gray-500 text-sm">Oath Club-এর নিয়মিত কার্যক্রম</p></div>
            </a>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

<!-- MEMBERS -->
<section id="members" class="section-padding bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">আমাদের সদস্য</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">অনুমোদিত সদস্যবৃন্দ</h2>
            <p class="text-gray-500">নিচের তালিকায় শুধুমাত্র অনুমোদিত সদস্যদের তথ্য প্রদর্শিত হয়</p>
        </div>
        <div class="max-w-md mx-auto mb-8">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="memberSearch" oninput="filterMembers()" placeholder="সদস্যের নাম খুঁজুন..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 bg-white shadow-sm">
            </div>
        </div>
        <div id="membersContainer" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($members as $member)
            <div class="member-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 text-center" data-aos="fade-up">
                <div class="aspect-[4/5] bg-gray-100 overflow-hidden">
                    <img src="{{ $member->photo ? asset('storage/'.$member->photo) : 'https://ui-avatars.com/api/?name='.urlencode($member->full_name).'&background=16a34a&color=fff' }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover object-center">
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 text-lg">{{ $member->full_name }}</h3>
                    <p class="text-primary-600 text-sm font-semibold">{{ $member->member_id }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $member->position ?? 'সদস্য' }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">কোনো অনুমোদিত সদস্য নেই</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-8" id="toggleMembersWrapper" style="display:none">
            <button id="toggleMembersBtn" onclick="toggleMembers()" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-full text-sm font-semibold transition shadow-md hover:shadow-lg inline-flex items-center gap-2">
                <span id="toggleMembersText">Show More</span>
                <i class="fas fa-chevron-down text-xs transition-transform" id="toggleMembersIcon"></i>
            </button>
        </div>
    </div>
</section>

<!-- HONORARY ADVISORY COUNCIL -->
<section id="honorary-advisory-council" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">উপদেষ্টা পরিষদ</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">সম্মানিত উপদেষ্টা মন্ডলী পরিষদ</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($honoraryAdvisoryCouncilMembers as $c)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 text-center" data-aos="fade-up">
                <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                    @if($c->photo)
                    <img src="{{ asset('storage/'.$c->photo) }}" alt="{{ $c->name }}" class="w-full h-full object-cover object-center">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200"><i class="fas fa-user-circle text-primary-600 text-7xl"></i></div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg">{{ $c->name }}</h3>
                    <p class="text-primary-600 text-sm font-semibold">{{ $c->position }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-user-tie text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">সম্মানিত উপদেষ্টা মন্ডলী পরিষদ তথ্য কনফিগার করা হয়নি</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- EXECUTIVE ADVISORY COUNCIL -->
<section id="executive-advisory-council" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">উপদেষ্টা পরিষদ</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">নির্বাহী উপদেষ্টা পরিষদ</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($executiveAdvisoryCouncilMembers as $c)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 text-center" data-aos="fade-up">
                <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                    @if($c->photo)
                    <img src="{{ asset('storage/'.$c->photo) }}" alt="{{ $c->name }}" class="w-full h-full object-cover object-center">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200"><i class="fas fa-user-circle text-primary-600 text-7xl"></i></div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg">{{ $c->name }}</h3>
                    <p class="text-primary-600 text-sm font-semibold">{{ $c->position }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-user-tie text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">নির্বাহী উপদেষ্টা পরিষদ তথ্য কনফিগার করা হয়নি</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- COMMITTEE -->
<section id="committee" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">কমিটি</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">আমাদের কমিটি</h2>
            <p class="text-gray-500 mt-2">Oath Club-এর নেতৃত্ব ও পরিচালনা কমিটি</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($committee as $c)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 text-center" data-aos="fade-up">
                <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                    @if($c->photo)
                    <img src="{{ asset('storage/'.$c->photo) }}" alt="{{ $c->name }}" class="w-full h-full object-cover object-center">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200"><i class="fas fa-user-circle text-primary-600 text-7xl"></i></div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg">{{ $c->name }}</h3>
                    <p class="text-primary-600 text-sm font-semibold">{{ $c->position }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-user-tie text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">কমিটি তথ্য কনফিগার করা হয়নি</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- GALLERY -->
<section id="gallery" class="section-padding bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">গ্যালারি</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">আমাদের মুহূর্তগুলি</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($gallery as $g)
            <a href="{{ asset('storage/'.$g->image) }}" data-lightbox="club-gallery" data-title="{{ $g->caption }}" class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition h-48 md:h-56 block">
                <img src="{{ asset('storage/'.$g->image) }}" alt="{{ $g->caption }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"><i class="fas fa-search-plus text-white text-3xl"></i></div>
                @if($g->caption)
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 group-hover:opacity-100 transition"><p class="text-white text-sm font-medium">{{ $g->caption }}</p></div>
                @endif
            </a>
            @empty
            @for($i=1;$i<=8;$i++)
            <div class="rounded-xl bg-gray-200 h-48 md:h-56 flex items-center justify-center"><i class="fas fa-image text-3xl text-gray-400"></i></div>
            @endfor
            @endforelse
        </div>
        <div class="mt-10">
            {{ $gallery->onEachSide(1)->fragment('gallery')->appends(request()->query())->links() }}
        </div>
    </div>
</section>

<!-- JOIN FORM -->
<section id="join" class="section-padding bg-gradient-to-br from-primary-700 to-primary-500 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-white/20 rounded-full text-sm font-semibold mb-4 backdrop-blur-sm">সদস্য registration</span>
            <h2 class="text-3xl md:text-4xl font-bold mb-3">Oath Club-এ জয়েন করুন</h2>
            <p class="text-white/80">ফর্মটি পূরণ করে সদস্য হওয়ার জন্য আবেদন করুন। অনুমোদিত হলে আপনাকে সদস্য তালিকায় যুক্ত করা হবে।</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 md:p-10 border border-white/20" data-aos="fade-up">
            <div class="bg-white/15 rounded-xl p-4 mb-6 text-center text-sm">
                <i class="fas fa-info-circle mr-1.5"></i>
                সদস্য ফি: <strong>{{ $settings['membership_fee'] ?? '100' }} টাকা</strong> | বিকাশ: <strong>{{ $settings['bkash'] ?? '' }}</strong> | নগদ: <strong>{{ $settings['nagad'] ?? '' }}</strong>
            </div>

            <form method="POST" action="{{ route('apply.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-5" id="homeJoinForm">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-user mr-1"></i> পূর্ণ নাম <span class="text-red-300">*</span></label>
                    <input type="text" name="full_name" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="আপনার পূর্ণ নাম লিখুন">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-male mr-1"></i> পিতার নাম</label>
                    <input type="text" name="father_name" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="পিতার নাম">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-female mr-1"></i> মাতার নাম</label>
                    <input type="text" name="mother_name" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="মাতার নাম">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-calendar mr-1"></i> জন্ম তারিখ <span class="text-red-300">*</span></label>
                    <input type="date" name="date_of_birth" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-primary-300 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-phone mr-1"></i> মোবাইল নম্বর <span class="text-red-300">*</span></label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="০১৭XX-XXXXXX">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-envelope mr-1"></i> ইমেইল</label>
                    <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="email@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-briefcase mr-1"></i> পেশা</label>
                    <input type="text" name="occupation" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="আপনার পেশা">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-tint mr-1"></i> রক্তের গ্রুপ</label>
                    <select name="blood_group" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 border-0 focus:ring-2 focus:ring-primary-300 transition">
                        <option value="">নির্বাচন করুন</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}">{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-map-marker-alt mr-1"></i> ঠিকানা</label>
                    <input type="text" name="address" class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="বর্তমান ঠিকানা">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-receipt mr-1"></i> লেনদেন আইডি (TrxID) <span class="text-red-300">*</span></label>
                    <input type="text" name="transaction_id" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-400 border-0 focus:ring-2 focus:ring-primary-300 transition" placeholder="বিকাশ/নগদ TrxID">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5 text-white/90"><i class="fas fa-camera mr-1"></i> ছবি <span class="text-red-300">*</span></label>
                    <input type="file" name="photo" accept="image/*" required class="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-700 border-0 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-600 file:text-white file:font-semibold file:cursor-pointer hover:file:bg-primary-700 transition text-sm">
                </div>
                <div class="md:col-span-2 mt-2">
                    <button type="submit" class="w-full px-8 py-4 bg-white text-primary-700 hover:bg-primary-50 rounded-xl font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> আবেদন জমা দিন
                    </button>
                </div>
                <div class="md:col-span-2 text-center text-xs text-white/60">
                    <i class="fas fa-shield-alt mr-1"></i> আপনার তথ্য নিরাপদ রাখা হবে এবং শুধুমাত্র ক্লাবের অফিসিয়াল কাজে ব্যবহৃত হবে।
                </div>
            </form>
        </div>
    </div>
</section>



<!-- CONTACT -->
<section id="contact" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">যোগাযোগ</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">আমাদের সাথে যোগাযোগ করুন</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-10 lg:gap-16">
            <div class="space-y-6" data-aos="fade-right">
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-phone text-primary-600 text-xl"></i></div>
                    <div><h4 class="font-semibold text-gray-800">ফোন</h4><p class="text-gray-500">{{ $settings['phone'] ?? '' }}</p></div>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-envelope text-primary-600 text-xl"></i></div>
                    <div><h4 class="font-semibold text-gray-800">ইমেইল</h4><p class="text-gray-500">{{ $settings['email'] ?? '' }}</p></div>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fab fa-facebook text-primary-600 text-xl"></i></div>
                    <div><h4 class="font-semibold text-gray-800">Facebook</h4><a href="{{ $settings['facebook'] ?? '#' }}" target="_blank" class="text-primary-600 hover:underline">Facebook Page</a></div>
                </div>
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-map-marker-alt text-primary-600 text-xl"></i></div>
                    <div><h4 class="font-semibold text-gray-800">ঠিকানা</h4><p class="text-gray-500">{{ $settings['address'] ?? '' }}</p></div>
                </div>
            </div>
            <div data-aos="fade-left">
                <form id="contactForm" class="space-y-5" onsubmit="submitContactForm(event)">
                    @csrf
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">আপনার নাম <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 transition" placeholder="আপনার নাম"></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">ইমেইল</label><input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 transition" placeholder="email@example.com"></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">ফোন</label><input type="tel" name="phone" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 transition" placeholder="০১৭XX-XXXXXX"></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">বার্তা <span class="text-red-500">*</span></label><textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-500 transition resize-none" placeholder="আপনার বার্তা লিখুন..."></textarea></div>
                    <button type="submit" class="w-full px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-semibold transition shadow-lg flex items-center justify-center gap-2"><i class="fas fa-paper-plane"></i> বার্তা পাঠান</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.getElementById('homeJoinForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    try {
        const res = await fetch(form.action, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (json.success) {
            Swal.fire({ icon: 'success', title: 'ধন্যবাদ!', text: json.message, timer: 2000, showConfirmButton: false });
            form.reset();
        }
    } catch(e) {
        Swal.fire({ icon: 'error', title: 'ত্রুটি', text: 'আবেদন জমা দেওয়া যায়নি' });
    }
});

let currentSlide = 0;
let heroTimer = null;
const slides = Array.from(document.querySelectorAll('#heroSlider [data-slide]'));
const dots = Array.from(document.querySelectorAll('#heroDots [data-slide-target]'));
const prevButton = document.querySelector('.hero-prev');
const nextButton = document.querySelector('.hero-next');

function goToSlide(index) {
    if (!slides.length) return;

    currentSlide = (index + slides.length) % slides.length;

    slides.forEach((slide, i) => {
        const active = i === currentSlide;
        slide.classList.toggle('opacity-100', active);
        slide.classList.toggle('scale-100', active);
        slide.classList.toggle('opacity-0', !active);
        slide.classList.toggle('scale-[1.02]', !active);
    });

    dots.forEach((dot, i) => {
        const active = i === currentSlide;
        dot.classList.toggle('bg-white', active);
        dot.classList.toggle('scale-110', active);
        dot.classList.toggle('bg-white/45', !active);
    });
}

function nextSlide() {
    goToSlide(currentSlide + 1);
}

function previousSlide() {
    goToSlide(currentSlide - 1);
}

function restartHeroTimer() {
    if (slides.length < 2) return;
    clearInterval(heroTimer);
    heroTimer = setInterval(nextSlide, 4000);
}
window.goToSlide = goToSlide;

if (slides.length > 1) {
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            goToSlide(Number(dot.dataset.slideTarget));
            restartHeroTimer();
        });
    });

    prevButton?.addEventListener('click', () => {
        previousSlide();
        restartHeroTimer();
    });

    nextButton?.addEventListener('click', () => {
        nextSlide();
        restartHeroTimer();
    });

    restartHeroTimer();
}

function filterMembers() {
    const q = document.getElementById('memberSearch').value.toLowerCase();
    document.querySelectorAll('#membersContainer .member-card').forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = name.includes(q) ? '' : 'none';
    });
}

function toggleMembers() {
    const c = document.getElementById('membersContainer');
    const text = document.getElementById('toggleMembersText');
    const icon = document.getElementById('toggleMembersIcon');
    const isExpanded = c.classList.toggle('limited');
    text.textContent = isExpanded ? 'Show More' : 'Show Less';
    icon.style.transform = isExpanded ? '' : 'rotate(180deg)';
    if (!isExpanded) {
        document.getElementById('members').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

(function() {
    const c = document.getElementById('membersContainer');
    const cards = c?.querySelectorAll('.member-card');
    const wrapper = document.getElementById('toggleMembersWrapper');
    if (!c || !cards || !wrapper) return;
    const limit = window.innerWidth < 768 ? 4 : 8;
    if (cards.length > limit) {
        c.classList.add('limited');
        wrapper.style.display = '';
    }
})();

async function submitContactForm(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    try {
        const res = await fetch('{{ route("contact.submit") }}', { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (json.success) { Swal.fire({ icon: 'success', title: 'ধন্যবাদ!', text: json.message, timer: 2000, showConfirmButton: false }); form.reset(); }
    } catch(e) { Swal.fire({ icon: 'error', title: 'ত্রুটি', text: 'বার্তা পাঠানো যায়নি' }); }
}
</script>
@endpush
