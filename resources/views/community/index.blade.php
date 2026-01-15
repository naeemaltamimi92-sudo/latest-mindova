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

    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-slide-up { animation: slide-up 0.6s ease forwards; }
    .animate-bounce-in { animation: bounce-in 0.6s ease forwards; }
    .animate-wave { animation: wave 2s ease-in-out infinite; }

    /* Hero Section */
    .community-hero {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 25%, #6366f1 50%, #3b82f6 75%, #06b6d4 100%);
        background-size: 400% 400%;
        animation: gradient-shift 15s ease infinite;
        position: relative;
        overflow: hidden;
    }

    .community-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M30 30c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10zm0 0c0 5.523 4.477 10 10 10s10-4.477 10-10-4.477-10-10-10-10 4.477-10 10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.5;
    }

    /* Chat Bubbles Animation */
    .chat-bubble {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 12px 16px;
        position: absolute;
        animation: float 5s ease-in-out infinite;
    }

    .chat-bubble::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 20px;
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid rgba(255,255,255,0.15);
    }

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
        background: linear-gradient(90deg, #ec4899, #8b5cf6, #6366f1);
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
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 2px solid #fbbf24;
    }

    .score-badge.medium {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border: 2px solid #10b981;
    }

    /* Field Badge */
    .field-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        color: #5b21b6;
        border: 2px solid #a78bfa;
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
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    /* Action Button */
    .btn-join {
        background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
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
        box-shadow: 0 15px 35px -5px rgba(139, 92, 246, 0.4);
    }

    .btn-join:hover::before {
        transform: translateX(100%);
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.4);
    }

    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
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
        background: linear-gradient(90deg, #8b5cf6, #ec4899);
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
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.25rem;
        color: white;
        box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
    }

    /* Empty State */
    .empty-state {
        background: linear-gradient(135deg, #fdf4ff 0%, #fae8ff 50%, #f5d0fe 100%);
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
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 4s ease-in-out infinite;
        box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.4);
    }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 2px solid #93c5fd;
        border-radius: 20px;
        padding: 1.5rem;
    }

    /* Modal Styling */
    .modal-overlay {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        animation: slide-up 0.4s ease-out;
    }

    /* Contributor Badge */
    .contributor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        background: linear-gradient(135deg, #fae8ff, #f5d0fe);
        color: #86198f;
        border: 1px solid #e879f9;
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
<!-- Premium Hero Section -->
<div class="community-hero py-16 mb-10 rounded-3xl shadow-2xl mx-4 lg:mx-8 relative">
    <!-- Animated Orbs -->
    <div class="hero-orb w-72 h-72 bg-pink-400 -top-20 -left-20 animate-float" style="animation-delay: 0s;"></div>
    <div class="hero-orb w-96 h-96 bg-violet-400 -bottom-32 -right-32 animate-float" style="animation-delay: 2s;"></div>
    <div class="hero-orb w-56 h-56 bg-cyan-400 top-1/2 left-1/4 animate-float" style="animation-delay: 4s;"></div>

    <!-- Floating Chat Bubbles -->
    <div class="chat-bubble hidden lg:block right-[10%] top-[20%]" style="animation-delay: 0.5s;">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-gradient-to-br from-pink-400 to-violet-400 rounded-full"></div>
            <span class="text-white text-sm font-medium">{{ __('Great idea!') }}</span>
        </div>
    </div>
    <div class="chat-bubble hidden lg:block left-[8%] bottom-[25%]" style="animation-delay: 1.5s;">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full"></div>
            <span class="text-white text-sm font-medium">{{ __('Let\'s collaborate!') }}</span>
        </div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-10">
        <div class="text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full px-5 py-2.5 mb-6 animate-bounce-in">
                <div class="relative">
                    <div class="w-2.5 h-2.5 bg-emerald-400 rounded-full"></div>
                    <div class="absolute inset-0 w-2.5 h-2.5 bg-emerald-400 rounded-full animate-ping"></div>
                </div>
                <span class="text-sm font-bold text-white">{{ __('Community Discussion Hub') }}</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 animate-slide-up">
                {{ __('Community') }}
                <span class="bg-gradient-to-r from-yellow-200 via-pink-200 to-cyan-200 bg-clip-text text-transparent">{{ __('Challenges') }}</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl text-white/90 font-medium mb-8 max-w-3xl mx-auto animate-slide-up" style="animation-delay: 0.1s;">
                {{ __('Collaborate with the community to refine and improve challenges through discussion, feedback, and innovative solutions') }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-center gap-4 animate-slide-up" style="animation-delay: 0.2s;">
                @if(auth()->check() && auth()->user()->isVolunteer())
                <button type="button" id="openChallengeModalBtn" class="btn-submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Submit a Challenge') }}
                </button>
                @endif
                <a href="#challenges" class="bg-white/15 backdrop-blur-md border-2 border-white/30 text-white font-bold px-8 py-4 rounded-2xl hover:bg-white/25 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    {{ __('Explore Challenges') }}
                </a>
            </div>

            <!-- Quick Stats in Hero -->
            <div class="flex flex-wrap justify-center gap-6 mt-10 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/20 text-center">
                    <div class="text-3xl font-black text-white">{{ $challenges->total() }}</div>
                    <div class="text-sm text-white/80 font-medium">{{ __('Challenges') }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/20 text-center">
                    <div class="text-3xl font-black text-yellow-300">{{ $challenges->sum('ideas_count') }}</div>
                    <div class="text-sm text-white/80 font-medium">{{ __('Ideas') }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/20 text-center">
                    <div class="text-3xl font-black text-emerald-300">{{ $challenges->sum(function($c) { return $c->ideas()->where('ai_quality_score', '>=', 70)->count(); }) }}</div>
                    <div class="text-sm text-white/80 font-medium">{{ __('Quality Insights') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 lg:px-8 pb-12" id="challenges">
    <!-- Field Filter Banner -->
    @if(isset($userField) && $userField)
    <div class="filter-card mb-8 animate-slide-up">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
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
    <div class="mb-8 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-2">
            <div class="flex flex-wrap gap-2">
                {{-- Active Tab --}}
                <a href="{{ route('community.index', ['filter' => 'active']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300
                          {{ ($filter ?? 'active') === 'active'
                              ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>{{ __('Active') }}</span>
                </a>

                {{-- Completed Tab --}}
                <a href="{{ route('community.index', ['filter' => 'completed']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300
                          {{ ($filter ?? 'active') === 'completed'
                              ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ __('Completed') }}</span>
                </a>

                {{-- All Tab --}}
                <a href="{{ route('community.index', ['filter' => 'all']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300
                          {{ ($filter ?? 'active') === 'all'
                              ? 'bg-gradient-to-r from-violet-500 to-violet-600 text-white shadow-lg shadow-violet-500/30'
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
        <div class="challenge-card animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
            <div class="challenge-card-body">
                <div class="flex flex-col xl:flex-row xl:items-start gap-6">
                    <!-- Main Content -->
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex flex-wrap items-start gap-3 mb-4">
                            <a href="{{ route('community.challenge', $challenge) }}" class="text-2xl md:text-3xl font-black text-gray-900 hover:text-violet-600 transition-colors leading-tight">
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full text-xs font-bold shadow-sm">
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
                                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($challenge->volunteer->user->name ?? 'V', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $challenge->volunteer->user->name ?? 'Volunteer' }}</p>
                                    <span class="contributor-badge">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('Contributor') }}
                                    </span>
                                </div>
                                @else
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($challenge->company->company_name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $challenge->company->company_name ?? 'Company' }}</p>
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
                        <a href="{{ route('community.challenge', $challenge) }}" class="btn-join justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            {{ __('Join Discussion') }}
                        </a>

                        <!-- Preview Stats -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 text-center">
                            <p class="text-xs text-gray-500 font-medium mb-2">{{ __('Engagement Level') }}</p>
                            <div class="flex justify-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                <div class="w-2 h-8 rounded-full {{ ($challenge->ideas_count ?? 0) > ($i * 2) ? 'bg-gradient-to-t from-violet-500 to-pink-500' : 'bg-gray-200' }}"></div>
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
    <div class="empty-state animate-slide-up">
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
                <button type="button" id="openChallengeModalBtnEmpty" class="btn-submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Submit a Challenge') }}
                </button>
                @endif
                <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 font-bold px-8 py-4 rounded-2xl hover:bg-gray-300 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- How It Works Section -->
<section class="py-20 bg-gradient-to-br from-violet-50 via-purple-50 to-pink-50">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-12 animate-slide-up">
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
            <div class="info-card animate-slide-up" style="animation-delay: 0.1s;">
                <div class="how-it-works-step" data-step="1">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Educational Focus') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('Level 1-2 challenges are perfect for learning and skill development. They\'re designed to be accessible while still providing meaningful problem-solving opportunities.') }}
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="info-card animate-slide-up" style="animation-delay: 0.2s;">
                <div class="how-it-works-step" data-step="2">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('High-Quality Comments') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('Comments scored 7+ by AI boost your reputation and get noticed by challenge owners. Focus on providing thoughtful, actionable insights.') }}
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="info-card animate-slide-up" style="animation-delay: 0.3s;">
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
        <div class="modal-content relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <form id="challengeForm" enctype="multipart/form-data">
                @csrf
                <!-- Header -->
                <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-pink-600 px-8 py-6 rounded-t-[2rem]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-black text-white" id="modal-title">
                                {{ __('Submit a Community Challenge') }}
                            </h3>
                            <p class="text-white/80 text-sm mt-1">{{ __('Share a problem for the community to solve together') }}</p>
                        </div>
                        <button type="button" onclick="closeChallengeModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-violet-500 focus:ring-0 transition-colors text-lg"
                            placeholder="{{ __('Enter a clear, descriptive title...') }}">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="challenge_description" class="block text-sm font-bold text-gray-700 mb-2">
                            {{ __('Challenge Description') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="challenge_description" rows="6" required minlength="50"
                            class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-violet-500 focus:ring-0 transition-colors"
                            placeholder="{{ __('Describe your challenge in detail. Include the problem you want to solve, any constraints, and desired outcomes... (minimum 50 characters)') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-2">{{ __('Minimum 50 characters required') }}</p>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label for="challenge_attachments" class="block text-sm font-bold text-gray-700 mb-2">
                            {{ __('Attachments') }} <span class="text-gray-400 font-normal">({{ __('optional') }})</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-violet-400 transition-colors cursor-pointer" onclick="document.getElementById('challenge_attachments').click()">
                            <input type="file" name="attachments[]" id="challenge_attachments" multiple
                                class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            <div class="w-16 h-16 bg-gradient-to-br from-violet-100 to-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
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
                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 border-2 border-violet-200 rounded-xl p-5">
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
                    <button type="button" onclick="closeChallengeModal()" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-colors">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" id="submitBtn" class="px-8 py-3 bg-gradient-to-r from-violet-600 to-pink-600 text-white font-bold rounded-xl hover:from-violet-700 hover:to-pink-700 transition-all shadow-lg inline-flex items-center gap-2">
                        <span id="submitText">{{ __('Submit Challenge') }}</span>
                        <span id="submitLoading" class="hidden">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Submitting...') }}
                        </span>
                    </button>
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
