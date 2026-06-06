@extends('layouts.app')

@section('content')
<section class="section-padding bg-gray-50 min-h-screen pt-24">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div data-aos="fade-up">
            @if($team->banner_image)
            <div class="h-64 md:h-80 rounded-2xl overflow-hidden shadow-lg mb-8">
                <img src="{{ asset('storage/'.$team->banner_image) }}" alt="{{ $team->team_name }}" class="w-full h-full object-cover">
            </div>
            @endif

            <div class="flex items-center gap-4 mb-6">
                <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $team->sport_type === 'Cricket' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ $team->sport_type }}</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">{{ $team->team_name }}</h2>
            </div>

            @if($team->description)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <p class="text-gray-600 leading-relaxed">{{ $team->description }}</p>
            </div>
            @endif
        </div>

        @if($team->players->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-users text-primary-600"></i> Squad ({{ $team->players->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($team->players as $p)
                <div class="px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center"><span class="text-primary-700 font-bold">{{ strtoupper(substr($p->player_name, 0, 1)) }}</span></div>
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $p->player_name }}</h4>
                            @if($p->position)
                            <p class="text-gray-400 text-xs">{{ $p->position }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">#{{ $loop->iteration }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-16" data-aos="fade-up">
            <i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-500">No players in this team yet.</p>
        </div>
        @endif

        <div class="text-center mt-10" data-aos="fade-up">
            <a href="{{ route('sports') }}" class="btn-primary inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i> All Teams</a>
        </div>
    </div>
</section>
@endsection
