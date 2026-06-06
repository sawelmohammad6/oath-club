@extends('layouts.app')

@section('content')
<section class="section-padding bg-gray-50 min-h-screen pt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">Sports</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Oath Club Sports</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Our cricket and football teams</p>
        </div>

        @if($cricketTeams->isNotEmpty())
        <div class="mb-12" data-aos="fade-up">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-baseball-ball text-blue-600"></i></div>
                <h3 class="text-2xl font-bold text-gray-800">Cricket Teams</h3>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cricketTeams as $team)
                <a href="{{ route('sports.team', $team->id) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition border border-gray-100 block">
                    @if($team->banner_image)
                    <div class="h-44 overflow-hidden">
                        <img src="{{ asset('storage/'.$team->banner_image) }}" alt="{{ $team->team_name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    @else
                    <div class="h-44 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center"><i class="fas fa-baseball-ball text-blue-400 text-5xl"></i></div>
                    @endif
                    <div class="p-5">
                        <h4 class="text-xl font-bold text-gray-800 mb-1">{{ $team->team_name }}</h4>
                        <p class="text-gray-400 text-sm">{{ $team->players->count() }} players</p>
                        @if($team->description)
                        <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $team->description }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($footballTeams->isNotEmpty())
        <div data-aos="fade-up">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-futbol text-green-600"></i></div>
                <h3 class="text-2xl font-bold text-gray-800">Football Teams</h3>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($footballTeams as $team)
                <a href="{{ route('sports.team', $team->id) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition border border-gray-100 block">
                    @if($team->banner_image)
                    <div class="h-44 overflow-hidden">
                        <img src="{{ asset('storage/'.$team->banner_image) }}" alt="{{ $team->team_name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    @else
                    <div class="h-44 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center"><i class="fas fa-futbol text-green-400 text-5xl"></i></div>
                    @endif
                    <div class="p-5">
                        <h4 class="text-xl font-bold text-gray-800 mb-1">{{ $team->team_name }}</h4>
                        <p class="text-gray-400 text-sm">{{ $team->players->count() }} players</p>
                        @if($team->description)
                        <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $team->description }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($cricketTeams->isEmpty() && $footballTeams->isEmpty())
        <div class="text-center py-20" data-aos="fade-up">
            <i class="fas fa-running text-7xl text-gray-300 mb-4"></i>
            <p class="text-2xl text-gray-500">No teams registered yet.</p>
        </div>
        @endif
    </div>
</section>
@endsection
