@extends('layouts.app')

@section('title', __('Dashboard'))

@push('styles')
<style>
    /* Premium Animation Keyframes */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2); opacity: 0; }
    }

    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes countdown-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
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

    @keyframes glow-pulse {
        0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
    }

    @keyframes border-dance {
        0%, 100% { border-color: rgba(99, 102, 241, 0.3); }
        50% { border-color: rgba(139, 92, 246, 0.6); }
    }

    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-pulse-ring { animation: pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    .animate-spin-slow { animation: spin-slow 3s linear infinite; }
    .animate-countdown-pulse { animation: countdown-pulse 1s ease-in-out infinite; }
    .animate-shimmer { animation: shimmer 2s infinite; }
    .animate-slide-up { animation: slide-up 0.6s ease forwards; }
    .animate-bounce-in { animation: bounce-in 0.6s ease forwards; }
    .animate-glow-pulse { animation: glow-pulse 2s ease-in-out infinite; }

    /* Hero Section */
    .dashboard-hero {
        background: var(--gradient-hero, linear-gradient(135deg, #4f46e5 0%, #7c3aed 30%, #a855f7 60%, #ec4899 100%));
        background-size: 400% 400%;
        animation: gradient-shift 12s ease infinite;
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='10'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.4;
    }

    /* Profile Avatar */
    .profile-avatar {
        position: relative;
        width: 80px;
        height: 80px;
    }

    .profile-avatar-ring {
        position: absolute;
        inset: -4px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        animation: border-dance 3s ease-in-out infinite;
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.5);
    }

    .profile-avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(255,255,255,0.3);
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
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
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-card.highlighted {
        border-color: var(--highlight-color);
        background: var(--highlight-bg);
    }

    .stat-card.highlighted::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(-5deg);
    }

    /* Section Cards */
    .section-card {
        background: white;
        border-radius: 28px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .section-card:hover {
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
    }

    /* Invitation Cards */
    .invitation-card {
        background: var(--gradient-gold, linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fcd34d 100%));
        border: 2px solid var(--gradient-gold-border, #fbbf24);
        border-radius: 20px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .invitation-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.4) 50%, transparent 60%);
        animation: shimmer 3s infinite;
    }

    .invitation-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(251, 191, 36, 0.4);
    }

    /* Task Cards */
    .task-card {
        background: white;
        border: 2px solid var(--color-border, #e2e8f0);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .task-card:hover {
        border-color: var(--color-primary, #6366f1);
        box-shadow: 0 15px 30px -10px var(--shadow-color-primary-light, rgba(99, 102, 241, 0.2));
        transform: translateY(-4px);
    }

    /* Team Cards */
    .team-card {
        background: linear-gradient(135deg, var(--color-secondary-100, #ede9fe) 0%, var(--color-secondary-200, #ddd6fe) 100%);
        border: 2px solid var(--color-secondary-400, #a78bfa);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px var(--shadow-color-secondary-light, rgba(139, 92, 246, 0.3));
    }

    /* Skill Badges */
    .skill-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .skill-badge:hover {
        transform: translateY(-2px) scale(1.05);
    }

    .skill-badge.expert {
        background: var(--gradient-card-purple, linear-gradient(135deg, #4f46e5, #7c3aed));
        color: white;
        box-shadow: 0 4px 15px -3px var(--shadow-color-primary, rgba(79, 70, 229, 0.4));
    }

    .skill-badge.advanced {
        background: var(--gradient-card-violet, linear-gradient(135deg, #7c3aed, #a855f7));
        color: white;
        box-shadow: 0 4px 15px -3px var(--shadow-color-secondary, rgba(139, 92, 246, 0.4));
    }

    .skill-badge.intermediate {
        background: var(--gradient-card-green, linear-gradient(135deg, #10b981, #14b8a6));
        color: white;
        box-shadow: 0 4px 15px -3px var(--shadow-color-success, rgba(16, 185, 129, 0.4));
    }

    .skill-badge.beginner {
        background: var(--color-slate-100, #f1f5f9);
        color: var(--color-slate-600, #475569);
        border: 1px solid var(--color-border, #e2e8f0);
    }

    /* Action Buttons */
    .btn-primary {
        background: var(--gradient-card-purple, linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%));
        color: white;
        font-weight: 700;
        padding: 0.875rem 1.75rem;
        border-radius: 14px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px var(--shadow-color-primary, rgba(79, 70, 229, 0.4));
    }

    .btn-primary:hover::before {
        transform: translateX(100%);
    }

    .btn-accept {
        background: linear-gradient(135deg, var(--color-success, #10b981) 0%, var(--color-success-dark, #059669) 100%);
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-accept:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px var(--shadow-color-success, rgba(16, 185, 129, 0.4));
    }

    .btn-decline {
        background: white;
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .btn-decline:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    /* Progress Ring */
    .progress-ring-container {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-bg {
        fill: none;
        stroke: #e2e8f0;
        stroke-width: 8;
    }

    .progress-ring-progress {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dashoffset 1s ease;
    }

    .progress-ring-text {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* CV Analysis Modal */
    .cv-analysis-modal {
        backdrop-filter: blur(8px);
    }

    .cv-modal-content {
        animation: slide-up 0.5s ease-out;
    }

    /* Empty State */
    .empty-state {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 28px;
        padding: 4rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.03'%3E%3Ccircle cx='20' cy='20' r='5'/%3E%3C/g%3E%3C/svg%3E");
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: var(--gradient-primary, linear-gradient(135deg, #6366f1, #8b5cf6));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 4s ease-in-out infinite;
        box-shadow: 0 20px 40px -10px var(--shadow-color-primary-light, rgba(99, 102, 241, 0.3));
    }

    /* Quick Actions Grid */
    .quick-action {
        background: white;
        border: 2px solid var(--color-border, #e2e8f0);
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .quick-action:hover {
        border-color: var(--color-primary, #6366f1);
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px var(--shadow-color-primary-light, rgba(99, 102, 241, 0.2));
    }

    .quick-action-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 0.75rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Education & Experience Cards */
    .timeline-card {
        position: relative;
        padding-left: 1.5rem;
        border-left: 3px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .timeline-card::before {
        content: '';
        position: absolute;
        left: -7px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--dot-color, #6366f1);
        border: 3px solid white;
        box-shadow: 0 0 0 3px var(--dot-color, #6366f1);
    }

    .timeline-card:hover {
        border-left-color: var(--dot-color, #6366f1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 2rem 1rem;
        }

        .profile-avatar {
            width: 60px;
            height: 60px;
        }

        .section-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- CV Analysis Notification Modal -->
@if($volunteer->cv_file_path && $volunteer->ai_analysis_status === 'pending')
<div id="cvAnalysisModal" class="cv-analysis-modal fixed inset-0 bg-gradient-to-br from-indigo-900/80 via-violet-900/80 to-purple-900/80 z-50 flex items-center justify-center p-4">
    <div class="cv-modal-content bg-white rounded-[2rem] shadow-2xl max-w-lg w-full overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 px-8 py-6 text-center">
            <div class="relative w-24 h-24 mx-auto mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-white/30 animate-pulse-ring"></div>
                <div class="absolute inset-2 rounded-full border-4 border-white/40 animate-pulse-ring" style="animation-delay: 0.5s;"></div>
                <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center shadow-xl">
                    <svg class="w-10 h-10 text-indigo-600 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-black text-white mb-2">{{ __('Analyzing Your CV') }}</h2>
            <p class="text-white/90 text-sm">{{ __('Our AI is extracting your skills and experience') }}</p>
        </div>

        <!-- Modal Body -->
        <div class="px-8 py-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center bg-gradient-to-r from-indigo-50 to-violet-50 border-2 border-indigo-200 rounded-2xl px-8 py-4">
                    <div class="text-center">
                        <div class="text-5xl font-black text-indigo-600 animate-countdown-pulse" id="countdownTimer">2:00</div>
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mt-1">{{ __('Estimated Time') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-6">
                <div class="flex items-center gap-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800">{{ __('CV Uploaded Successfully') }}</p>
                        <p class="text-xs text-emerald-600">{{ __('Your document is ready for processing') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-indigo-50 border border-indigo-200 rounded-xl animate-pulse">
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-indigo-800">{{ __('AI Analysis in Progress') }}</p>
                        <p class="text-xs text-indigo-600">{{ __('Extracting skills, education, and experience...') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 border border-gray-200 rounded-xl opacity-50">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-sm">3</span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-500">{{ __('Profile Updated') }}</p>
                        <p class="text-xs text-gray-400">{{ __('Your profile will be enhanced with extracted data') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-amber-800">
                        {{ __('The page will automatically refresh when analysis is complete.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="px-8 pb-8">
            <button onclick="closeModal()" class="w-full btn-primary text-center">
                {{ __('Continue to Dashboard') }}
            </button>
        </div>
    </div>
</div>

<script>
let timeLeft = 120;
const timerElement = document.getElementById('countdownTimer');
const modal = document.getElementById('cvAnalysisModal');

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    if (timeLeft > 0) {
        timeLeft--;
        setTimeout(updateTimer, 1000);
    } else {
        location.reload();
    }
}

function closeModal() {
    modal.style.display = 'none';
}

updateTimer();
setTimeout(() => { if (modal.style.display !== 'none') closeModal(); }, 10000);
</script>
@endif

<!-- CV Processing Banner -->
@if($volunteer->cv_file_path && $volunteer->ai_analysis_status === 'processing')
<div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-500 text-white py-4 px-6 mb-8 rounded-2xl shadow-lg mx-4 lg:mx-8">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <div>
                <p class="font-bold">{{ __('CV Analysis in Progress') }}</p>
                <p class="text-sm text-white/80">{{ __('Your skills are being extracted. Page will refresh automatically.') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-white/80">{{ __('Est. time') }}</p>
            <p class="font-bold">~1 min</p>
        </div>
    </div>
</div>
<script>setTimeout(() => location.reload(), 60000);</script>
@endif

<!-- Premium Hero Section -->
<div class="dashboard-hero py-10 lg:py-14 mb-10 rounded-3xl shadow-2xl mx-4 lg:mx-8 relative">
    <!-- Animated Orbs -->
    <div class="hero-orb w-64 h-64 bg-pink-400 top-0 left-0 animate-float" style="animation-delay: 0s;"></div>
    <div class="hero-orb w-96 h-96 bg-indigo-300 bottom-0 right-0 animate-float" style="animation-delay: 2s;"></div>
    <div class="hero-orb w-48 h-48 bg-violet-400 top-1/2 left-1/3 animate-float" style="animation-delay: 4s;"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
            <!-- Profile Section -->
            <div class="flex flex-col sm:flex-row items-center gap-6 flex-1">
                <!-- Avatar -->
                <div class="profile-avatar animate-bounce-in">
                    <div class="profile-avatar-ring"></div>
                    @if(auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}"
                             alt="{{ auth()->user()->name }}"
                             class="profile-avatar-img">
                    @else
                        <div class="profile-avatar-placeholder">
                            <span class="text-3xl font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Welcome Text -->
                <div class="text-center sm:text-left animate-slide-up">
                    <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full px-4 py-2 mb-3">
                        <div class="relative">
                            <div class="w-2.5 h-2.5 bg-emerald-400 rounded-full"></div>
                            <div class="absolute inset-0 w-2.5 h-2.5 bg-emerald-400 rounded-full animate-ping"></div>
                        </div>
                        <span class="text-sm font-semibold text-white">{{ __('Active Volunteer') }}</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-black text-white mb-2">
                        {{ __('Welcome back,') }}
                        <span class="bg-gradient-to-r from-yellow-200 via-pink-200 to-yellow-200 bg-clip-text text-transparent">{{ auth()->user()->name }}!</span>
                    </h1>
                    <p class="text-white/80 text-lg">{{ __('Your command center for tasks, teams, and community impact') }}</p>
                </div>
            </div>

            <!-- Quick Stats in Hero -->
            <div class="flex flex-wrap gap-4 justify-center lg:justify-end animate-slide-up" style="animation-delay: 0.2s;">
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/20 text-center min-w-[100px]">
                    <div class="text-3xl font-black text-white">{{ $stats['pending_assignments'] ?? 0 }}</div>
                    <div class="text-xs text-white/80 font-medium">{{ __('Invitations') }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/20 text-center min-w-[100px]">
                    <div class="text-3xl font-black text-emerald-300">{{ $stats['completed_tasks'] ?? 0 }}</div>
                    <div class="text-xs text-white/80 font-medium">{{ __('Completed') }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white/20 text-center min-w-[100px]">
                    <div class="text-3xl font-black text-yellow-300">{{ $volunteer->reputation_score ?? 50 }}</div>
                    <div class="text-xs text-white/80 font-medium">{{ __('Reputation') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 lg:px-8 pb-12">
    <!-- Primary Action Card -->
    <div class="section-card mb-10 animate-slide-up" style="--card-gradient: linear-gradient(90deg, #4f46e5, #7c3aed);">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-gradient-to-br from-indigo-500 to-violet-500 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ __('My Task Invitations & Progress') }}</h2>
                    <p class="text-gray-500">{{ __('View and manage your assignments') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if(($stats['pending_assignments'] ?? 0) > 0)
                <span class="px-4 py-2 bg-amber-100 border-2 border-amber-300 text-amber-700 rounded-xl font-bold text-sm">
                    {{ $stats['pending_assignments'] }} {{ __('New') }}
                </span>
                @endif
                @if(isset($activeTasks) && $activeTasks->count() > 0)
                <span class="px-4 py-2 bg-indigo-100 border-2 border-indigo-300 text-indigo-700 rounded-xl font-bold text-sm">
                    {{ $activeTasks->count() }} {{ __('Active') }}
                </span>
                @endif
                <a href="{{ route('assignments.my') }}" class="btn-primary inline-flex items-center gap-2">
                    {{ __('View All Tasks') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-10">
        <!-- Team Invitations -->
        <div class="stat-card animate-slide-up {{ ($stats['team_invitations'] ?? 0) > 0 ? 'highlighted' : '' }}"
             style="--card-gradient: linear-gradient(90deg, #8b5cf6, #a855f7); --highlight-color: #a855f7; --highlight-bg: linear-gradient(135deg, #f5f3ff, #ede9fe); animation-delay: 0.1s;">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Team Invitations') }}</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ $stats['team_invitations'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-gradient-to-br from-violet-500 to-purple-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            @if(($stats['team_invitations'] ?? 0) > 0)
            <a href="{{ route('teams.my') }}" class="text-sm text-violet-600 hover:text-violet-700 font-semibold inline-flex items-center gap-1">
                {{ __('View invitations') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @else
            <p class="text-xs text-gray-500">{{ __('No pending invitations') }}</p>
            @endif
        </div>

        <!-- Task Assignments -->
        <div class="stat-card animate-slide-up {{ ($stats['pending_assignments'] ?? 0) > 0 ? 'highlighted' : '' }}"
             style="--card-gradient: linear-gradient(90deg, #3b82f6, #6366f1); --highlight-color: #6366f1; --highlight-bg: linear-gradient(135deg, #eef2ff, #e0e7ff); animation-delay: 0.2s;">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Task Invitations') }}</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ $stats['pending_assignments'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-gradient-to-br from-blue-500 to-indigo-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            @if(($stats['pending_assignments'] ?? 0) > 0)
            <p class="text-xs text-indigo-600 font-semibold">{{ __('Awaiting response') }}</p>
            @else
            <p class="text-xs text-gray-500">{{ __('No pending tasks') }}</p>
            @endif
        </div>

        <!-- Completed Tasks -->
        <div class="stat-card animate-slide-up" style="--card-gradient: linear-gradient(90deg, #10b981, #14b8a6); animation-delay: 0.3s;">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Completed Tasks') }}</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ $stats['completed_tasks'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-teal-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ __('Successfully finished') }}
            </p>
        </div>

        <!-- Reputation Score -->
        <div class="stat-card animate-slide-up" style="--card-gradient: linear-gradient(90deg, #f59e0b, #ef4444); animation-delay: 0.4s;">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Reputation Score') }}</p>
                    <h3 class="text-4xl font-black text-gray-900">{{ $volunteer->reputation_score ?? 50 }}</h3>
                </div>
                <div class="stat-icon bg-gradient-to-br from-amber-500 to-orange-500">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500">{{ __('Community standing') }}</p>
        </div>
    </div>

    <!-- Team Invitations Section -->
    @if(isset($teamInvitations) && $teamInvitations->count() > 0)
    <div class="section-card mb-10 animate-slide-up">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon bg-gradient-to-br from-violet-500 to-purple-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900">{{ __('Team Invitations') }}</h2>
            </div>
            <a href="{{ route('teams.my') }}" class="text-sm text-violet-600 hover:text-violet-700 font-semibold inline-flex items-center gap-1">
                {{ __('View all') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        <div class="space-y-4">
            @foreach($teamInvitations->take(2) as $team)
            @php $myMembership = $team->members->where('volunteer_id', $volunteer->id)->first(); @endphp
            <div class="team-card relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $team->name }}</h3>
                            @if($myMembership->role === 'leader')
                            <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-bold">{{ __('Leader') }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($team->description, 100) }}</p>
                        <p class="text-sm text-violet-700 font-medium">{{ $team->challenge->title }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-accept inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Accept') }}
                            </button>
                        </form>
                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-decline">{{ __('Decline') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Pending Assignments Section -->
    @if($pendingAssignments->count() > 0)
    <div class="section-card mb-10 animate-slide-up">
        <div class="flex items-center gap-3 mb-6">
            <div class="stat-icon bg-gradient-to-br from-blue-500 to-indigo-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-900">{{ __('New Task Assignments') }}</h2>
                <p class="text-sm text-gray-500">{{ __('Matched based on your skills') }}</p>
            </div>
        </div>
        <div class="space-y-4">
            @foreach($pendingAssignments->take(3) as $assignment)
            <div class="invitation-card relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $assignment->task->title }}</h3>
                            <span class="px-3 py-1 bg-blue-100 border border-blue-300 text-blue-800 rounded-full text-xs font-bold">
                                {{ $assignment->match_score }}% {{ __('Match') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 mb-2">{{ Str::limit($assignment->task->description, 120) }}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $assignment->task->estimated_hours }}{{ __('h') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                {{ Str::limit($assignment->task->challenge->title, 30) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="/api/assignments/{{ $assignment->id }}/accept" method="POST">
                            @csrf
                            <button type="submit" class="btn-accept inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Accept') }}
                            </button>
                        </form>
                        <form action="/api/assignments/{{ $assignment->id }}/reject" method="POST">
                            @csrf
                            <button type="submit" class="btn-decline">{{ __('Decline') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Active Tasks Section -->
    @if(isset($activeTasks) && $activeTasks->count() > 0)
    <div class="section-card mb-10 animate-slide-up">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-teal-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900">{{ __('My Active Tasks') }}</h2>
            </div>
            <span class="px-4 py-2 bg-emerald-100 border-2 border-emerald-300 text-emerald-800 rounded-xl font-bold text-sm">
                {{ $activeTasks->count() }} {{ Str::plural(__('task'), $activeTasks->count()) }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($activeTasks as $task)
            @php $assignment = $task->assignments->first(); @endphp
            <div class="task-card">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $task->title }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->challenge->title, 40) }}</p>
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $assignment->invitation_status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ ucfirst($assignment->invitation_status) }}
                    </span>
                    <a href="{{ route('tasks.show', $task->id) }}" class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm inline-flex items-center gap-1">
                        {{ __('View') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Profile & Skills Section -->
    <div class="section-card mb-10 animate-slide-up">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="stat-icon bg-gradient-to-br from-indigo-500 to-violet-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ __('Your Profile & Skills') }}</h2>
                    <p class="text-sm text-gray-500">{{ __('Professional profile based on CV analysis') }}</p>
                </div>
            </div>
            @if($volunteer->cv_file_path)
            <span class="px-4 py-2 rounded-xl text-xs font-bold border-2
                {{ $volunteer->ai_analysis_status === 'completed' ? 'bg-emerald-50 border-emerald-300 text-emerald-700' :
                   ($volunteer->ai_analysis_status === 'processing' ? 'bg-amber-50 border-amber-300 text-amber-700' :
                   'bg-gray-50 border-gray-300 text-gray-700') }}">
                {{ __('CV:') }} {{ __(ucfirst($volunteer->ai_analysis_status)) }}
            </span>
            @else
            <a href="{{ route('complete-profile') }}" class="btn-primary inline-flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                {{ __('Upload CV') }}
            </a>
            @endif
        </div>

        <!-- Profile Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-2xl p-5">
                <h3 class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">{{ __('Field of Expertise') }}</h3>
                <p class="text-lg font-bold text-gray-900">{{ $volunteer->field ?? __('Not set') }}</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-2xl p-5">
                <h3 class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">{{ __('Experience Level') }}</h3>
                <p class="text-lg font-bold text-gray-900">{{ $volunteer->experience_level ?? __('Not analyzed') }}</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-2xl p-5">
                <h3 class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">{{ __('Years of Experience') }}</h3>
                <p class="text-lg font-bold text-gray-900">{{ $volunteer->years_of_experience ?? 0 }} {{ __('years') }}</p>
            </div>
        </div>

        <!-- Skills -->
        @if($volunteer->skills && $volunteer->skills->count() > 0)
        <div class="bg-gradient-to-br from-indigo-50 to-violet-50 border-2 border-indigo-200 rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                {{ __('Your Skills') }}
                <span class="ml-auto text-sm font-bold text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">{{ $volunteer->skills->count() }}</span>
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($volunteer->skills as $skill)
                <span class="skill-badge {{ $skill->proficiency_level ?? 'beginner' }}">
                    {{ $skill->skill_name }}
                    @if($skill->proficiency_level)
                    <span class="text-xs opacity-80">({{ ucfirst($skill->proficiency_level) }})</span>
                    @endif
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Education -->
        @if($volunteer->education && is_array($volunteer->education) && count($volunteer->education) > 0)
        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border-2 border-cyan-200 rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                {{ __('Education') }}
            </h3>
            <div class="space-y-4">
                @foreach($volunteer->education as $edu)
                <div class="timeline-card" style="--dot-color: #06b6d4;">
                    <h4 class="font-bold text-gray-900">{{ $edu['degree'] ?? __('Degree') }}</h4>
                    <p class="text-sm text-cyan-700 font-medium">{{ $edu['institution'] ?? __('Institution') }}</p>
                    @if(isset($edu['year']) || isset($edu['graduation_year']))
                    <p class="text-xs text-gray-500 mt-1">{{ $edu['year'] ?? $edu['graduation_year'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Work Experience -->
        @if($volunteer->work_experience && is_array($volunteer->work_experience) && count($volunteer->work_experience) > 0)
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-6">
            <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                {{ __('Work Experience') }}
            </h3>
            <div class="space-y-4">
                @foreach($volunteer->work_experience as $exp)
                <div class="timeline-card" style="--dot-color: #f59e0b;">
                    <h4 class="font-bold text-gray-900">{{ $exp['title'] ?? $exp['position'] ?? __('Position') }}</h4>
                    <p class="text-sm text-amber-700 font-medium">{{ $exp['company'] ?? $exp['organization'] ?? __('Company') }}</p>
                    @if(isset($exp['duration']) || isset($exp['years']))
                    <p class="text-xs text-gray-500 mt-1">{{ $exp['duration'] ?? $exp['years'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty State for Profile -->
        @if(!($volunteer->skills && $volunteer->skills->count() > 0) && !($volunteer->education && is_array($volunteer->education) && count($volunteer->education) > 0))
        <div class="empty-state">
            <div class="relative z-10">
                <div class="empty-icon">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                @if($volunteer->cv_file_path && ($volunteer->ai_analysis_status === 'processing' || $volunteer->ai_analysis_status === 'pending'))
                <h3 class="text-xl font-bold text-gray-700 mb-2">{{ __('CV Analysis in Progress') }}</h3>
                <p class="text-gray-500 max-w-md mx-auto">{{ __('Our AI is extracting your skills and experience. This usually takes about 2 minutes.') }}</p>
                <div class="mt-4 inline-flex items-center text-indigo-600 font-semibold">
                    <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    {{ __('Analyzing...') }}
                </div>
                @elseif(!$volunteer->cv_file_path)
                <h3 class="text-xl font-bold text-gray-700 mb-2">{{ __('No Profile Data Yet') }}</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">{{ __('Upload your CV to automatically extract your skills, education, and work experience.') }}</p>
                <a href="{{ route('complete-profile') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    {{ __('Upload Your CV') }}
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-slide-up">
        <a href="{{ route('assignments.my') }}" class="quick-action">
            <div class="quick-action-icon bg-gradient-to-br from-indigo-500 to-violet-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="font-bold text-gray-900">{{ __('My Tasks') }}</p>
            <p class="text-xs text-gray-500">{{ __('View assignments') }}</p>
        </a>
        <a href="{{ route('teams.my') }}" class="quick-action">
            <div class="quick-action-icon bg-gradient-to-br from-violet-500 to-purple-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <p class="font-bold text-gray-900">{{ __('My Teams') }}</p>
            <p class="text-xs text-gray-500">{{ __('Collaborate') }}</p>
        </a>
        <a href="{{ route('profile.edit') }}" class="quick-action">
            <div class="quick-action-icon bg-gradient-to-br from-emerald-500 to-teal-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <p class="font-bold text-gray-900">{{ __('Edit Profile') }}</p>
            <p class="text-xs text-gray-500">{{ __('Update info') }}</p>
        </a>
        <a href="{{ route('community.index') }}" class="quick-action">
            <div class="quick-action-icon bg-gradient-to-br from-pink-500 to-rose-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <p class="font-bold text-gray-900">{{ __('Community') }}</p>
            <p class="text-xs text-gray-500">{{ __('Discussions') }}</p>
        </a>
    </div>
</div>
@endsection
