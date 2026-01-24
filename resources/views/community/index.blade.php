@extends('layouts.app')

@section('title', __('Community Challenges'))

@push('styles')
<style>
    /* Premium Animation Keyframes */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(3deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2); opacity: 0; }
    }

    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce-in {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes typing {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(20deg); }
        75% { transform: rotate(-15deg); }
    }

    .animate-float { animation: float 6s-out infinite; }
    .animate-slide-up { animation: slide-up 0.6s ease forwards; }
    .animate-bounce-in { animation: bounce-in 0.6s ease forwards; }
    .animate-wave { animation: wave 2s-out infinite; }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    /* Challenge Cards */
    .challenge-card {
        background: white;
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: 2px solid #e5e7eb;
    }

    .challenge-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.2);
        border-color: transparent;
    }

    .challenge-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--gradient-hero);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .challenge-card:hover::before {
        transform: scaleX(1);
    }

    .challenge-card-body {
        padding: 2rem;
    }

    /* Score Badge */
    .score-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .score-badge.low {
        background: var(--gradient-gold);
        color: var(--color-warning-darker);
        border: 2px solid var(--color-warning-light);
    }

    .score-badge.medium {
        background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
        color: #4f46e5;
        border: 2px solid #6366f1;
    }

    /* Field Badge */
    .field-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, var(--color-secondary-100), var(--color-secondary-200));
        color: var(--color-secondary-800);
        border: 2px solid var(--color-secondary-400);
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.875rem;
    }

    /* Comment Stats */
    .comment-stat {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s ease;
    }

    .comment-stat:hover {
        background: #e2e8f0;
    }

    .comment-stat.quality {
        background: linear-gradient(135deg, var(--color-success-50), var(--color-success-200));
        color: var(--color-success-800);
    }

    /* Action Button */
    .btn-join {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        font-weight: 700;
        padding: 1rem 2rem;
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-join::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .btn-join:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px -5px var(--shadow-color-secondary);
    }

    .btn-join:hover::before {
        transform: translateX(100%);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-dark) 100%);
        color: white;
        font-weight: 700;
        padding: 1rem 2rem;
        border-radius: 16px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px -5px var(--shadow-color-success);
    }

    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, var(--color-slate-50) 0%, var(--color-slate-100) 100%);
        border: 2px solid var(--color-border);
        border-radius: 24px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-in-progress);
    }

    /* How It Works */
    .how-it-works-step {
        position: relative;
        padding-left: 4rem;
    }

    .how-it-works-step::before {
        content: attr(data-step);
        position: absolute;
        left: 0;
        top: 0;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--color-secondary), var(--color-pink));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.25rem;
        color: white;
        box-shadow: 0 10px 25px -5px var(--shadow-color-secondary);
    }

    /* Empty State */
    .empty-state {
        background: linear-gradient(135deg, var(--color-secondary-50) 0%, var(--color-secondary-100) 50%, var(--color-secondary-200) 100%);
        border-radius: 32px;
        padding: 5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%238b5cf6' fill-opacity='0.05'%3E%3Ccircle cx='20' cy='20' r='8'/%3E%3C/g%3E%3C/svg%3E");
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, var(--color-secondary), var(--color-pink));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 4s-out infinite;
        box-shadow: 0 25px 50px -12px var(--shadow-color-secondary);
    }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(135deg, var(--color-info-50) 0%, var(--color-info-100) 100%);
        border: 2px solid var(--color-info-300);
        border-radius: 20px;
        padding: 1.5rem;
    }

    /* Modal Styling */
    .modal-overlay {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        animation: slide-up 0.4s;
    }

    /* Contributor Badge */
    .contributor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        background: linear-gradient(135deg, var(--color-secondary-100), var(--color-secondary-200));
        color: var(--color-secondary-900);
        border: 1px solid var(--color-pink-light);
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .community-hero {
            padding: 2rem 1rem;
        }

        .chat-bubble {
            display: none;
        }

        .challenge-card-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 pb-12 pt-8" id="challenges">
    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900">{{ __('Community Challenges') }}</h1>
            <p class="text-slate-600 mt-1">{{ __('Join the discussion and help solve real-world problems') }}</p>
        </div>
        @if(auth()->check() && auth()->user()->isVolunteer())
        <x-ui.button type="button" onclick="openChallengeModal()" variant="primary" class="shadow-lg shadow-primary-500/30">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Submit a Challenge') }}
        </x-ui.button>
        @endif
    </div>

    <!-- Field Filter Banner -->
    @if(isset($userField) && $userField)
    <div class="filter-card mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-14 h-14 bg-primary-400 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Filtered by Your Expertise') }}</h3>
                    <span class="field-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $userField }}
                    </span>
                </div>
                <p class="text-sm text-gray-600">{{ __('Showing level 1-2 challenges in your field. These are perfect for learning and community contribution.') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Status Filter Tabs --}}
    <div class="mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-2">
            <div class="flex flex-wrap gap-2">
                {{-- Active Tab --}}
                <a href="{{ route('community.index', ['filter' => 'active']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm
                          {{ ($filter ?? 'active') === 'active'
                              ? 'bg-white text-primary-500 border border-primary-200 shadow-lg shadow-emerald-500/30'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>{{ __('Active') }}</span>
                </a>

                {{-- Completed Tab --}}
                <a href="{{ route('community.index', ['filter' => 'completed']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm
                          {{ ($filter ?? 'active') === 'completed'
                              ? 'bg-primary-500 !text-white shadow-lg shadow-blue-500/30'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                   style="{{ ($filter ?? 'active') === 'completed' ? 'color: #ffffff !important;' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ __('Completed') }}</span>
                </a>

                {{-- All Tab --}}
                <a href="{{ route('community.index', ['filter' => 'all']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm
                          {{ ($filter ?? 'active') === 'all'
                              ? 'bg-white text-primary-500 border border-primary-200 shadow-lg shadow-violet-500/30'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>{{ __('All') }}</span>
                </a>
            </div>
        </div>
    </div>

    @if($challenges->count() > 0)
    <!-- Challenges Grid -->
    <div class="space-y-8">
        @foreach($challenges as $index => $challenge)
        <div class="challenge-card" style="animation-delay: {{ $index * 0.1 }}s;">
            <div class="challenge-card-body">
                <div class="flex flex-col xl:flex-row xl:items-start gap-6">
                    <!-- Main Content -->
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex flex-wrap items-start gap-3 mb-4">
                            <a href="{{ route('community.challenge', $challenge) }}" class="text-2xl md:text-3xl font-black text-slate-900 hover:text-indigo-600 leading-tight">
                                {{ $challenge->title }}
                            </a>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="score-badge {{ $challenge->score <= 2 ? 'low' : 'medium' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ __('Score:') }} {{ $challenge->score }}/10
                                </span>
                                @if($challenge->field)
                                <span class="field-badge">
                                    {{ $challenge->field }}
                                </span>
                                @endif
                                @if($challenge->status === 'completed' || $challenge->status === 'closed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-primary-500 border border-primary-200 rounded-full text-xs font-bold shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $challenge->hasCorrectAnswer() ? __('Solved') : __('Completed') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Author Info -->
                        <div class="flex flex-wrap items-center gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                @if($challenge->isVolunteerSubmitted())
                                <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center !text-white font-bold text-sm">
                                    {{ strtoupper(substr($challenge->volunteer->user->name ?? 'V', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-indigo-900">{{ $challenge->volunteer->user->name ?? 'Volunteer' }}</p>
                                    <span class="contributor-badge">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('Contributor') }}
                                    </span>
                                </div>
                                @else
                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center !text-white font-bold text-sm">
                                    {{ strtoupper(substr($challenge->company->company_name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-indigo-900">{{ $challenge->company->company_name ?? 'Company' }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Organization') }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $challenge->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 leading-relaxed mb-6 line-clamp-3">
                            {{ $challenge->original_description }}
                        </p>

                        <!-- Stats Row -->
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="comment-stat">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span>{{ $challenge->ideas_count ?? 0 }} {{ trans_choice('{0} ideas|{1} idea|[2,*] ideas', $challenge->ideas_count ?? 0) }}</span>
                            </span>

                            @php $qualityCount = $challenge->ideas()->where('ai_quality_score', '>=', 70)->count(); @endphp
                            @if($qualityCount > 0)
                            <span class="comment-stat quality">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>{{ __(':count quality insights', ['count' => $qualityCount]) }}</span>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Column -->
                    <div class="xl:w-52 flex flex-col gap-3">
                        <a href="{{ route('community.challenge', $challenge) }}" class="btn-join justify-center whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            {{ __('Join Discussion') }}
                        </a>

                        <!-- Preview Stats -->
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-xs text-gray-500 font-medium mb-2">{{ __('Engagement Level') }}</p>
                            <div class="flex justify-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                <div class="w-2 h-8 rounded-full {{ ($challenge->ideas_count ?? 0) > ($i * 2) ? 'bg-secondary-500' : 'bg-gray-200' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
        {{ $challenges->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="relative z-10">
            <div class="empty-icon">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">
                @if(($filter ?? 'active') === 'completed')
                    {{ __('No Completed Challenges Yet') }}
                @elseif(($filter ?? 'active') === 'all')
                    {{ __('No Challenges Found') }}
                @else
                    {{ __('No Active Challenges Yet') }}
                @endif
            </h2>
            <p class="text-gray-600 text-lg mb-8 max-w-lg mx-auto">
                @if(($filter ?? 'active') === 'completed')
                    {{ __('Challenges that have been resolved will appear here.') }}
                @elseif(isset($userField) && $userField)
                    {{ __('No challenges found in your field (:field). Check back later or submit your own!', ['field' => $userField]) }}
                @else
                    {{ __('Check back later for challenges that need community input and discussion.') }}
                @endif
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if(auth()->check() && auth()->user()->isVolunteer())
                <x-ui.button type="button" onclick="openChallengeModal()" variant="primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Submit a Challenge') }}
                </x-ui.button>
                @endif
                <x-ui.button as="a" href="{{ route('dashboard') }}" variant="outline" size="lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </x-ui.button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- How It Works Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 bg-violet-100 text-violet-700 font-bold px-4 py-2 rounded-full text-sm mb-4">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                {{ __('How It Works') }}
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">{{ __('About Community Challenges') }}</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">{{ __('Learn how to make the most of the community discussion platform') }}</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="info-card">
                <div class="how-it-works-step" data-step="1">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Educational Focus') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('Level 1-2 challenges are perfect for learning and skill development. They\'re designed to be accessible while still providing meaningful problem-solving opportunities.') }}
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="info-card">
                <div class="how-it-works-step" data-step="2">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('High-Quality Comments') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('Comments scored 7+ by AI boost your reputation and get noticed by challenge owners. Focus on providing thoughtful, actionable insights.') }}
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="info-card">
                <div class="how-it-works-step" data-step="3">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Field Matching') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('Challenges are matched to your field of expertise, ensuring you can provide valuable, relevant contributions to discussions.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@if(auth()->check() && auth()->user()->isVolunteer())
<!-- Submit Challenge Modal -->
<div id="challengeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="modal-overlay fixed inset-0 z-40" onclick="closeChallengeModal()"></div>

    <!-- Modal container -->
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <!-- Modal panel -->
        <style>
            .modal-content::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div class="modal-content relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()" style="scrollbar-width: none; -ms-overflow-style: none;">
            <form id="challengeForm" enctype="multipart/form-data">
                @csrf
                <!-- Header -->
                <div class="bg-primary-500 px-8 py-6 rounded-t-[2rem] border-b border-primary-400/30">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-black !text-white" id="modal-title" style="color: white !important;">
                                {{ __('Submit a Community Challenge') }}
                            </h3>
                            <p class="!text-white/80 text-sm mt-1" style="color: rgba(255, 255, 255, 0.8) !important;">{{ __('Share a problem for the community to solve together') }}</p>
                        </div>
                        <button type="button" onclick="closeChallengeModal()" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-colors flex-shrink-0 group">
                            <svg class="w-5 h-5 text-white group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-8 py-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="challenge_title" class="block text-sm font-bold text-gray-700 mb-2">
                            {{ __('Challenge Title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="challenge_title" required
                            class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-violet-500 focus:ring-0 text-lg"
                            placeholder="{{ __('Enter a clear, descriptive title...') }}">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="challenge_description" class="block text-sm font-bold text-gray-700 mb-2">
                            {{ __('Challenge Description') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="challenge_description" rows="6" required minlength="50"
                            class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-violet-500 focus:ring-0"
                            placeholder="{{ __('Describe your challenge in detail. Include the problem you want to solve, any constraints, and desired outcomes... (minimum 50 characters)') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-2">{{ __('Minimum 50 characters required') }}</p>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label for="challenge_attachments" class="block text-sm font-bold text-gray-700 mb-2">
                            {{ __('Attachments') }} <span class="text-gray-400 font-normal">({{ __('optional') }})</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-violet-400 cursor-pointer" onclick="document.getElementById('challenge_attachments').click()">
                            <input type="file" name="attachments[]" id="challenge_attachments" multiple
                                class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 font-medium">{{ __('Click to upload or drag and drop') }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ __('PDF, DOC, XLS, PPT, Images up to 10MB each') }}</p>
                        </div>
                        <div id="fileList" class="mt-4 space-y-2"></div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-gray-50 border-2 border-violet-200 rounded-xl p-5">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-violet-900 mb-2">{{ __('How scoring works:') }}</p>
                                <ul class="space-y-1.5 text-sm text-violet-800">
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-violet-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Your challenge will be analyzed by AI and scored 1-10') }}
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('Score 1-2: Posted for community discussion') }}
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Score 3-10: Full challenge execution with tasks') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 rounded-b-[2rem]">
                    <x-ui.button type="button" onclick="closeChallengeModal()" variant="outline">
                        {{ __('Cancel') }}
                    </x-ui.button>
                    <x-ui.button as="submit" id="submitBtn" variant="secondary">
                        <span id="submitText">{{ __('Submit Challenge') }}</span>
                        <span id="submitLoading" class="hidden">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Submitting...') }}
                        </span>
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Define modal functions globally
function openChallengeModal() {
    var modal = document.getElementById('challengeModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeChallengeModal() {
    var modal = document.getElementById('challengeModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        var form = document.getElementById('challengeForm');
        if (form) form.reset();
        var fileList = document.getElementById('fileList');
        if (fileList) fileList.innerHTML = '';
    }
}

// Attach click handler to buttons
document.querySelectorAll('#openChallengeModalBtn, #openChallengeModalBtnEmpty').forEach(function(btn) {
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openChallengeModal();
        });
    }
});

// File list display
var attachmentsInput = document.getElementById('challenge_attachments');
if (attachmentsInput) {
    attachmentsInput.addEventListener('change', function(e) {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';

        Array.from(e.target.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center gap-3 bg-violet-50 border border-violet-200 rounded-xl px-4 py-3';
            fileItem.innerHTML = `
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            `;
            fileList.appendChild(fileItem);
        });
    });
}

// Form submission
var challengeForm = document.getElementById('challengeForm');
if (challengeForm) {
    challengeForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitLoading = document.getElementById('submitLoading');

        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("volunteer.challenges.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error:', response.status, errorText);

                if (response.status === 422) {
                    try {
                        const errorData = JSON.parse(errorText);
                        const errors = errorData.errors || {};
                        const firstError = Object.values(errors)[0];
                        alert(Array.isArray(firstError) ? firstError[0] : (errorData.message || 'Validation failed'));
                    } catch (parseErr) {
                        alert('Validation failed. Please check your input.');
                    }
                } else if (response.status === 419) {
                    alert('Session expired. Please refresh the page and try again.');
                } else {
                    alert('Server error. Please try again later.');
                }
                return;
            }

            const data = await response.json();

            if (data.success) {
                closeChallengeModal();
                alert(data.message);
                window.location.href = '{{ route("volunteer.challenges.index") }}';
            } else {
                alert(data.message || 'An error occurred. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please check your connection and try again.');
        } finally {
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        }
    });
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeChallengeModal();
    }
});
</script>
@endif
@endsection
