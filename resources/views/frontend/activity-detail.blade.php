@extends('layouts.app')

@section('content')
<section class="section-padding bg-gray-50 min-h-screen pt-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">{{ $detail->activity->title ?? 'Activity' }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $detail->title }}</h2>
            @if($detail->event_date)
            <p class="text-gray-400 text-sm"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($detail->event_date)->format('d F Y') }}</p>
            @endif
        </div>

        @if($detail->description)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8" data-aos="fade-up">
            <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $detail->description }}</p>
        </div>
        @endif

        @if($detail->images->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" data-aos="fade-up">
            @foreach($detail->images as $img)
            <a href="{{ asset('storage/'.$img->image_path) }}" data-lightbox="activity-gallery" class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition h-48 md:h-56 block">
                <img src="{{ asset('storage/'.$img->image_path) }}" alt="" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"><i class="fas fa-search-plus text-white text-3xl"></i></div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-16" data-aos="fade-up">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-500">No gallery images yet.</p>
        </div>
        @endif

        <div class="text-center mt-10" data-aos="fade-up">
            <a href="{{ route('home') }}#activities" class="btn-primary inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i> Back to Activities</a>
        </div>
    </div>
</section>
@endsection
