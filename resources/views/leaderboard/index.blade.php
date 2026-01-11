@extends('layouts.app')

@section('title', __('Leaderboard'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-yellow-50 via-orange-50 to-pink-50 pt-32 pb-20">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-gradient-orange opacity-20 rounded-full blur-3xl animate-float"></div>
    <div class="floating-element absolute top-40 right-0 w-[32rem] h-[32rem] bg-gradient-purple opacity-20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>

    <div class="relative max-w-7xl mx-auto px-6 sm:px-8 md:px-10 lg:px-12 xl:px-16">
        <div class="text-center animate-slide-in-up">
            <!-- Trophy Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">{{ __('Top Performers') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ __('Hall of') }} <span class="text-gradient">{{ __('Champions') }}</span>
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                {{ __('Celebrating the top contributors making impact on Mindova') }}
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 sm:px-8 md:px-10 lg:px-12 xl:px-16 py-12">
    <!-- Filter Tabs -->
    <div class="card-premium bg-white mb-12 animate-slide-in-up" x-data="{ activeTab: '{{ $filter ?? 'reputation' }}' }">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <button @click="activeTab = 'reputation'; window.location.href = '{{ route('leaderboard') }}?filter=reputation'"
                    :class="activeTab === 'reputation' ? 'bg-gradient-purple text-white shadow-xl scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                    class="px-6 py-4 rounded-xl font-bold text-sm transition-all transform hover:scale-105 border-2"
                    :class="activeTab === 'reputation' ? 'border-purple-500' : 'border-gray-200'">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span>{{ __('Reputation') }}</span>
                </div>
            </button>

            <button @click="activeTab = 'tasks'; window.location.href = '{{ route('leaderboard') }}?filter=tasks'"
                    :class="activeTab === 'tasks' ? 'bg-gradient-blue text-white shadow-xl scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                    class="px-6 py-4 rounded-xl font-bold text-sm transition-all transform hover:scale-105 border-2"
                    :class="activeTab === 'tasks' ? 'border-blue-500' : 'border-gray-200'">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>{{ __('Tasks') }}</span>
                </div>
            </button>

            <button @click="activeTab = 'hours'; window.location.href = '{{ route('leaderboard') }}?filter=hours'"
                    :class="activeTab === 'hours' ? 'bg-gradient-green text-white shadow-xl scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                    class="px-6 py-4 rounded-xl font-bold text-sm transition-all transform hover:scale-105 border-2"
                    :class="activeTab === 'hours' ? 'border-green-500' : 'border-gray-200'">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('Hours') }}</span>
                </div>
            </button>

            <button @click="activeTab = 'ideas'; window.location.href = '{{ route('leaderboard') }}?filter=ideas'"
                    :class="activeTab === 'ideas' ? 'bg-gradient-orange text-white shadow-xl scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                    class="px-6 py-4 rounded-xl font-bold text-sm transition-all transform hover:scale-105 border-2"
                    :class="activeTab === 'ideas' ? 'border-orange-500' : 'border-gray-200'">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <span>{{ __('Ideas') }}</span>
                </div>
            </button>
        </div>
    </div>

    @if($volunteers->count() > 0)
    <!-- Top 3 Podium -->
    @if($volunteers->count() >= 3)
    <div class="mb-16 animate-slide-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-end justify-center gap-4 md:gap-8">
            <!-- 2nd Place -->
            <div class="flex-1 max-w-xs">
                <div class="card-premium bg-gradient-to-br from-gray-50 to-gray-100 text-center transform hover:scale-105 transition-all relative">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center shadow-2xl border-4 border-white">
                            <span class="text-3xl">🥈</span>
                        </div>
                    </div>
                    <div class="pt-12">
                        <h3 class="text-xl font-black text-gray-900 mb-2">{{ $volunteers[1]->user->name }}</h3>
                        <div class="text-3xl font-black text-gradient mb-2">
                            @if($filter === 'reputation') {{ $volunteers[1]->reputation_score }}
                            @elseif($filter === 'tasks') {{ $volunteers[1]->completed_tasks_count ?? 0 }}
                            @elseif($filter === 'hours') {{ $volunteers[1]->total_hours ?? 0 }}
                            @elseif($filter === 'ideas') {{ round($volunteers[1]->avg_idea_score ?? 0, 1) }}
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            @if($filter === 'reputation') {{ __('points') }}
                            @elseif($filter === 'tasks') {{ __('tasks') }}
                            @elseif($filter === 'hours') {{ __('hours') }}
                            @elseif($filter === 'ideas') {{ __('avg score') }}
                            @endif
                        </p>
                        <a href="{{ route('volunteers.show', $volunteers[1]->id) }}" class="inline-flex items-center text-blue-600 font-bold hover:text-blue-700">
                            {{ __('View Profile') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="mt-4 h-24 bg-gradient-to-t from-gray-400 to-gray-300 rounded-t-2xl shadow-xl"></div>
            </div>

            <!-- 1st Place -->
            <div class="flex-1 max-w-xs">
                <div class="card-premium bg-gradient-to-br from-yellow-50 to-orange-100 text-center transform hover:scale-105 transition-all relative">
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center shadow-2xl border-4 border-white animate-pulse-glow">
                            <span class="text-4xl">🥇</span>
                        </div>
                    </div>
                    <div class="pt-14">
                        <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $volunteers[0]->user->name }}</h3>
                        <div class="text-4xl font-black text-gradient mb-2">
                            @if($filter === 'reputation') {{ $volunteers[0]->reputation_score }}
                            @elseif($filter === 'tasks') {{ $volunteers[0]->completed_tasks_count ?? 0 }}
                            @elseif($filter === 'hours') {{ $volunteers[0]->total_hours ?? 0 }}
                            @elseif($filter === 'ideas') {{ round($volunteers[0]->avg_idea_score ?? 0, 1) }}
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            @if($filter === 'reputation') {{ __('points') }}
                            @elseif($filter === 'tasks') {{ __('tasks') }}
                            @elseif($filter === 'hours') {{ __('hours') }}
                            @elseif($filter === 'ideas') {{ __('avg score') }}
                            @endif
                        </p>
                        <a href="{{ route('volunteers.show', $volunteers[0]->id) }}" class="inline-flex items-center text-blue-600 font-bold hover:text-blue-700">
                            {{ __('View Profile') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="mt-4 h-32 bg-gradient-to-t from-yellow-500 to-yellow-400 rounded-t-2xl shadow-2xl"></div>
            </div>

            <!-- 3rd Place -->
            <div class="flex-1 max-w-xs">
                <div class="card-premium bg-gradient-to-br from-orange-50 to-orange-100 text-center transform hover:scale-105 transition-all relative">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-500 rounded-full flex items-center justify-center shadow-2xl border-4 border-white">
                            <span class="text-3xl">🥉</span>
                        </div>
                    </div>
                    <div class="pt-12">
                        <h3 class="text-xl font-black text-gray-900 mb-2">{{ $volunteers[2]->user->name }}</h3>
                        <div class="text-3xl font-black text-gradient mb-2">
                            @if($filter === 'reputation') {{ $volunteers[2]->reputation_score }}
                            @elseif($filter === 'tasks') {{ $volunteers[2]->completed_tasks_count ?? 0 }}
                            @elseif($filter === 'hours') {{ $volunteers[2]->total_hours ?? 0 }}
                            @elseif($filter === 'ideas') {{ round($volunteers[2]->avg_idea_score ?? 0, 1) }}
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            @if($filter === 'reputation') {{ __('points') }}
                            @elseif($filter === 'tasks') {{ __('tasks') }}
                            @elseif($filter === 'hours') {{ __('hours') }}
                            @elseif($filter === 'ideas') {{ __('avg score') }}
                            @endif
                        </p>
                        <a href="{{ route('volunteers.show', $volunteers[2]->id) }}" class="inline-flex items-center text-blue-600 font-bold hover:text-blue-700">
                            {{ __('View Profile') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="mt-4 h-16 bg-gradient-to-t from-orange-500 to-orange-400 rounded-t-2xl shadow-xl"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Rest of Rankings -->
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <div class="h-1 w-12 bg-gradient-purple rounded-full"></div>
            {{ __('All Rankings') }}
        </h2>

        @foreach($volunteers as $index => $volunteer)
        @if($index >= 3)
        <div class="card-premium group hover:shadow-2xl transition-all animate-slide-in-up" style="animation-delay: {{ $index * 0.05 }}s;">
            <div class="flex items-center gap-6">
                <!-- Rank Badge -->
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center shadow-md border-2 border-white group-hover:scale-110 transition-transform">
                        <span class="text-2xl font-black text-gradient">{{ $index + 1 }}</span>
                    </div>
                </div>

                <!-- Volunteer Info -->
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $volunteer->user->name }}</h3>
                    @if($volunteer->user->linkedin_profile_url)
                    <a href="{{ $volunteer->user->linkedin_profile_url }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                        {{ __('LinkedIn Profile') }}
                    </a>
                    @endif

                    <!-- Skills -->
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($volunteer->skills->take(4) as $skill)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold border-2 border-blue-200">
                            {{ $skill->skill_name }}
                        </span>
                        @endforeach
                        @if($volunteer->skills->count() > 4)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold border-2 border-gray-200">
                            {{ __('+:count more', ['count' => $volunteer->skills->count() - 4]) }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Score -->
                <div class="flex-shrink-0 text-right">
                    <div class="text-4xl font-black text-gradient mb-1">
                        @if($filter === 'reputation') {{ $volunteer->reputation_score }}
                        @elseif($filter === 'tasks') {{ $volunteer->completed_tasks_count ?? 0 }}
                        @elseif($filter === 'hours') {{ $volunteer->total_hours ?? 0 }}
                        @elseif($filter === 'ideas') {{ round($volunteer->avg_idea_score ?? 0, 1) }}
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 font-semibold">
                        @if($filter === 'reputation') {{ __('points') }}
                        @elseif($filter === 'tasks') {{ __('tasks') }}
                        @elseif($filter === 'hours') {{ __('hours') }}
                        @elseif($filter === 'ideas') {{ __('score') }}
                        @endif
                    </p>
                </div>

                <!-- View Profile Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('volunteers.show', $volunteer->id) }}" class="inline-flex items-center justify-center bg-gradient-blue text-white font-bold px-6 py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg">
                        {{ __('View') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    @else
    <!-- Empty State -->
    <div class="card-premium text-center py-20">
        <div class="icon-badge bg-gradient-purple mx-auto mb-6 w-20 h-20">
            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-3">{{ __('No Rankings Yet') }}</h3>
        <p class="text-gray-600">{{ __('Be the first to make an impact!') }}</p>
    </div>
    @endif

    <!-- Top Companies Section -->
    @if(isset($topCompanies) && $topCompanies->count() > 0)
    <div class="mt-20">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
            <div class="h-1 w-12 bg-gradient-orange rounded-full"></div>
            {{ __('Most Active Companies') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($topCompanies as $index => $company)
            <div class="card-premium group hover:shadow-2xl transition-all animate-slide-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                <div class="flex items-start gap-4 mb-6">
                    @if($company->logo_path)
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->company_name }}" class="w-16 h-16 rounded-xl shadow-md object-cover">
                    @else
                    <div class="w-16 h-16 bg-gradient-blue rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-2xl font-black text-white">{{ substr($company->company_name, 0, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $company->company_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $company->industry }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl">
                        <div class="text-3xl font-black text-gradient mb-1">{{ $company->challenges_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600 font-semibold">{{ __('Challenges') }}</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-cyan-50 rounded-xl">
                        <div class="text-3xl font-black text-gradient mb-1">{{ $company->tasks_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600 font-semibold">{{ __('Tasks') }}</div>
                    </div>
                </div>

                <a href="{{ route('companies.show', $company->id) }}" class="inline-flex items-center text-blue-600 font-bold hover:text-blue-700 group-hover:translate-x-2 transition-transform">
                    {{ __('View Company Profile') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
