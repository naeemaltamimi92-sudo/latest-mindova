@extends('layouts.app')

@section('title', __('Dashboard'))

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   Dashboard — token-driven (Mindova design system)
   All colors resolve through CSS custom properties defined in
   theme.css, so light/dark mode is handled automatically by
   the cascade — no separate `.dark` overrides needed except
   for the semantic badge/chip/achievement scales below, which
   don't have dedicated dark variants in theme.css.
═══════════════════════════════════════════════════════════ */

/* Page background */
main { background-color: var(--color-background); }

/* ── Hero banner ─────────────────────────────────────────── */
.dash-hero {
    background: var(--gradient-aurora);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-glow-primary-sm);
}
.dash-hero-mesh {
    position: absolute; inset: 0;
    background-image:
        radial-gradient(ellipse 500px 300px at 88% -15%, rgba(255,255,255,0.10) 0%, transparent 55%),
        radial-gradient(ellipse 350px 250px at -5% 110%, rgba(255,255,255,0.06) 0%, transparent 50%);
}
.dash-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
}
.dash-avatar-ring {
    background: linear-gradient(135deg, var(--color-primary-100), var(--color-primary-200), var(--color-primary-300), var(--color-primary-400));
    padding: 3px; border-radius: 50%;
}
.dash-avatar-inner {
    background: linear-gradient(135deg, var(--color-primary-600), var(--color-primary-500));
    border-radius: 50%; width: 80px; height: 80px;
    display: flex; align-items: center; justify-content: center;
}
.dash-stat-card {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.28);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 14px; padding: 14px 18px; min-width: 96px;
    text-align: center;
    transition: background 0.2s, border-color 0.2s, transform 0.2s;
}
.dash-stat-card:hover {
    background: rgba(255,255,255,0.22);
    border-color: rgba(255,255,255,0.40);
    transform: translateY(-2px);
}
.dash-stat-value { font-size: 28px; font-weight: 800; color: #fff; line-height: 1; }
.dash-stat-label { font-size: 11px; font-weight: 500; color: rgba(255,255,255,0.65); margin-top: 4px; text-transform: uppercase; letter-spacing: 0.06em; }
.dash-stat-pending .dash-stat-value { color: var(--color-warning-300); }
.dash-rep-track { background: rgba(255,255,255,0.15); border-radius: 99px; height: 5px; overflow: hidden; }
.dash-rep-fill  { background: linear-gradient(90deg, var(--color-warning-300), var(--color-warning-400)); border-radius: 99px; height: 100%; transition: width 1s ease; }
.dash-level-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.28);
    border-radius: 20px; padding: 4px 10px;
    font-size: 11px; font-weight: 700; color: white;
    backdrop-filter: blur(8px);
}

/* ── Quick-action cards ───────────────────────────────────── */
.dash-qa-card {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 18px 10px; gap: 10px;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    text-decoration: none;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
}
.dash-qa-card:hover {
    border-color: var(--color-primary-200);
    box-shadow: var(--shadow-glow-primary-sm);
    transform: translateY(-3px);
}
.dash-qa-icon {
    width: 46px; height: 46px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.2s;
}
.dash-qa-card:hover .dash-qa-icon { transform: scale(1.08); }
.dash-qa-label { font-size: 11.5px; font-weight: 700; color: var(--color-text-primary); text-align: center; line-height: 1.3; }

/* ── Section cards ────────────────────────────────────────── */
.dash-section-card {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.dash-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px 14px;
    border-bottom: 1px solid var(--color-border);
}
.dash-section-title { font-size: 15px; font-weight: 700; color: var(--color-text-primary); display: flex; align-items: center; gap: 8px; }
.dash-section-link  { font-size: 12px; font-weight: 600; color: var(--color-primary-500); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.dash-section-link:hover { color: var(--color-primary-600); }

/* ── Task cards ───────────────────────────────────────────── */
.dash-task-card {
    display: flex; flex-direction: column; gap: 10px;
    padding: 16px;
    background: var(--color-surface-secondary);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    position: relative; overflow: hidden;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.dash-task-card::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: linear-gradient(180deg, var(--color-primary-500), var(--color-primary-400));
    border-radius: 3px 0 0 3px;
}
.dash-task-card:hover { border-color: var(--color-primary-200); box-shadow: var(--shadow-glow-primary-sm); }

/* ── Badges ───────────────────────────────────────────────── */
.dash-badge {
    display: inline-flex; align-items: center;
    padding: 3px 8px; border-radius: 6px;
    font-size: 10.5px; font-weight: 700;
    text-transform: capitalize; border: 1px solid;
}
.dash-badge-blue   { background: var(--color-info-50); color: var(--color-info-700); border-color: var(--color-info-200); }
.dash-badge-green  { background: var(--color-success-50); color: var(--color-success-700); border-color: var(--color-success-200); }
.dash-badge-violet { background: var(--color-primary-50); color: var(--color-primary-500); border-color: var(--color-primary-100); }
.dash-badge-amber  { background: var(--color-warning-50); color: var(--color-warning-800); border-color: var(--color-warning-200); }

/* ── Challenge rows ───────────────────────────────────────── */
.dash-challenge-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--color-border);
    text-decoration: none;
    transition: background 0.15s;
}
.dash-challenge-row:last-child { border-bottom: none; }
.dash-challenge-row:hover { background: var(--color-primary-50); }

/* ── Profile sidebar ──────────────────────────────────────── */
.dash-profile-card {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 12px; overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.dash-profile-hero {
    padding: 22px;
    background: var(--gradient-aurora);
    position: relative; overflow: hidden;
}
.dash-profile-hero::after {
    content: '';
    position: absolute; bottom: -30px; right: -30px;
    width: 120px; height: 120px; border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.dash-profile-stat-row {
    display: flex; gap: 0;
    border-top: 1px solid var(--color-border);
    border-bottom: 1px solid var(--color-border);
    background: var(--color-surface);
}
.dash-profile-stat {
    flex: 1; text-align: center;
    padding: 14px 8px;
    border-right: 1px solid var(--color-border);
}
.dash-profile-stat:last-child { border-right: none; }
.dash-profile-stat-val { font-size: 20px; font-weight: 800; color: var(--color-text-primary); }
.dash-profile-stat-lbl { font-size: 10px; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px; }

/* ── Skill chips ──────────────────────────────────────────── */
.dash-skill-chip {
    display: inline-flex; align-items: center;
    padding: 4px 10px; border-radius: 8px;
    font-size: 11px; font-weight: 600;
    border: 1px solid; cursor: default;
    transition: transform 0.15s;
}
.dash-skill-chip:hover { transform: scale(1.04); }
.skill-expert       { background: var(--color-primary-50); color: var(--color-primary-500); border-color: var(--color-primary-100); }
.skill-advanced     { background: var(--color-info-50); color: var(--color-info-700); border-color: var(--color-info-200); }
.skill-intermediate { background: var(--color-success-50); color: var(--color-success-700); border-color: var(--color-success-200); }
.skill-beginner     { background: var(--color-surface-secondary); color: var(--color-text-muted); border-color: var(--color-border); }

/* ── Action alert ─────────────────────────────────────────── */
.dash-action-alert {
    background: var(--color-warning-50);
    border: 1px solid var(--color-warning-200);
    border-radius: 12px; overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.dash-action-alert-header {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px;
    background: rgba(245,158,11,0.10);
    border-bottom: 1px solid var(--color-warning-200);
}
.dash-action-item {
    background: var(--color-surface);
    border: 1px solid var(--color-warning-100);
    border-radius: 10px; padding: 14px 16px;
}
.dash-action-item + .dash-action-item { margin-top: 10px; }

/* ── Achievement cards ────────────────────────────────────── */
.dash-ach-card {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px; border-radius: 10px; border: 1px solid;
    transition: transform 0.15s, box-shadow 0.15s;
    box-shadow: var(--shadow-xs);
}
.dash-ach-card:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
.dash-ach-gold   { background: var(--color-warning-50); border-color: var(--color-warning-200); }
.dash-ach-green  { background: var(--color-success-50); border-color: var(--color-success-200); }
.dash-ach-violet { background: var(--color-primary-50); border-color: var(--color-primary-100); }

/* ── Empty state ──────────────────────────────────────────── */
.dash-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 48px 24px; text-align: center;
}
.dash-empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--color-primary-50);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px;
}

/* ═══════════════════════════════════════════════════════════
   Dark mode — targeted overrides only.
   Surfaces/borders/text above already flip automatically via
   theme.css's `.dark { ... }` variable block. These semantic
   50/200/700 scales (badges, skill chips, achievement cards)
   aren't redefined per-theme in theme.css, so they need an
   explicit dark palette to stay legible on a dark background.
═══════════════════════════════════════════════════════════ */
.dark .dash-badge-blue   { background: rgba(59,130,246,0.15); color: var(--color-info-300); border-color: rgba(59,130,246,0.35); }
.dark .dash-badge-green  { background: rgba(16,185,129,0.15); color: var(--color-success-300); border-color: rgba(16,185,129,0.35); }
.dark .dash-badge-violet { background: rgba(90,61,235,0.18); color: var(--color-primary-300); border-color: rgba(90,61,235,0.4); }
.dark .dash-badge-amber  { background: rgba(245,158,11,0.15); color: var(--color-warning-300); border-color: rgba(245,158,11,0.35); }

.dark .skill-expert       { background: rgba(90,61,235,0.18); color: var(--color-primary-300); border-color: rgba(90,61,235,0.4); }
.dark .skill-advanced     { background: rgba(59,130,246,0.15); color: var(--color-info-300); border-color: rgba(59,130,246,0.35); }
.dark .skill-intermediate { background: rgba(16,185,129,0.15); color: var(--color-success-300); border-color: rgba(16,185,129,0.35); }
.dark .skill-beginner     { background: var(--color-surface-tertiary); color: var(--color-text-muted); border-color: var(--color-border); }

.dark .dash-ach-gold   { background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.3); }
.dark .dash-ach-green  { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.3); }
.dark .dash-ach-violet { background: rgba(90,61,235,0.15); border-color: rgba(90,61,235,0.35); }

.dark .dash-empty-icon { background: rgba(90,61,235,0.15); }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     CV ANALYSIS MODAL
══════════════════════════════════════════════════════════ --}}
@if($volunteer->cv_file_path && $volunteer->ai_analysis_status === 'pending')
<div id="cvAnalysisModal" class="fixed inset-0 bg-gray-900/70 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full overflow-hidden border border-gray-200 dark:border-gray-700 elevation-xl animate-fade-in">
        <div class="bg-aurora px-6 py-5 text-center">
            <div class="w-16 h-16 mx-auto mb-3 bg-white rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-white mb-1">{{ __('Analyzing Your CV') }}</h2>
            <p class="text-white/90 text-sm">{{ __('Our AI is extracting your skills and experience') }}</p>
        </div>
        <div class="px-6 py-6">
            <div class="text-center mb-5">
                <div class="inline-flex items-center justify-center bg-gray-50 border border-gray-200 rounded-xl px-6 py-3">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600" id="countdownTimer">2:00</div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">{{ __('Estimated Time') }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3 mb-5">
                <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <div class="w-7 h-7 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-emerald-800 text-sm">{{ __('CV Uploaded Successfully') }}</p>
                        <p class="text-xs text-emerald-600">{{ __('Your document is ready for processing') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-primary-50 border border-primary-200 rounded-lg">
                    <div class="w-7 h-7 bg-primary-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-primary-800 text-sm">{{ __('AI Analysis in Progress') }}</p>
                        <p class="text-xs text-primary-600">{{ __('Extracting skills, education, and experience...') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                    <div class="w-7 h-7 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xs">3</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500 text-sm">{{ __('Profile Updated') }}</p>
                        <p class="text-xs text-gray-400">{{ __('Your profile will be enhanced with extracted data') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 pb-6">
            <x-ui.button onclick="closeModal()" fullWidth>{{ __('Continue to Dashboard') }}</x-ui.button>
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
    if (timeLeft > 0) { timeLeft--; setTimeout(updateTimer, 1000); } else { location.reload(); }
}
function closeModal() { modal.style.display = 'none'; }
updateTimer();
setTimeout(() => { if (modal.style.display !== 'none') closeModal(); }, 10000);
</script>
@endif

{{-- CV Processing Banner --}}
@if($volunteer->cv_file_path && $volunteer->ai_analysis_status === 'processing')
<div class="bg-amber-50 border-b border-amber-200 py-3 px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div>
                <p class="font-semibold text-amber-900 text-sm">{{ __('AI is analyzing your CV') }}</p>
                <p class="text-xs text-amber-700">{{ __('Skills & experience will be auto-populated. Page refreshes automatically.') }}</p>
            </div>
        </div>
        <span class="text-xs font-semibold bg-amber-100 border border-amber-300 text-amber-800 px-3 py-1 rounded-full">~1 min</span>
    </div>
</div>
<script>setTimeout(() => location.reload(), 60000);</script>
@endif

<div class="max-w-7xl mx-auto px-4 lg:px-8 pb-14 pt-6">

    {{-- ══════════════════════════════════════════════════════
         HERO BANNER
    ══════════════════════════════════════════════════════ --}}
    <div class="dash-hero rounded-2xl mb-6">
        <div class="dash-hero-grid"></div>
        <div class="dash-hero-mesh"></div>
        <div class="relative px-7 py-8 lg:px-10">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-7">

                {{-- Identity --}}
                <div class="flex items-center gap-5">
                    <div class="dash-avatar-ring flex-shrink-0">
                        <div class="dash-avatar-inner">
                            @if(auth()->user()->profile_picture_url ?? false)
                                <img src="{{ auth()->user()->profile_picture_url }}" alt="" class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-[32px] font-extrabold text-white leading-none">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-white/55 text-xs font-semibold uppercase tracking-widest mb-1">{{ __('Welcome back') }}</p>
                        <h1 class="text-2xl lg:text-3xl font-extrabold text-white leading-tight tracking-tight">
                            {{ auth()->user()->name }}
                        </h1>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            @if($volunteer->field)
                            <span class="text-white/70 text-sm font-medium">{{ $volunteer->field }}</span>
                            @endif
                            @if($volunteer->experience_level)
                            <span class="dash-level-badge">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $volunteer->experience_level }}
                            </span>
                            @endif
                        </div>
                        @if($volunteer->reputation_score)
                        <div class="mt-3 max-w-[220px]">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-white/45 text-[10px] font-semibold uppercase tracking-wider">{{ __('Reputation') }}</span>
                                <span class="text-white/70 text-[10px] font-bold">{{ $volunteer->reputation_score }} / 1000</span>
                            </div>
                            <div class="dash-rep-track">
                                <div class="dash-rep-fill" style="width: {{ min(($volunteer->reputation_score / 1000) * 100, 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="dash-stat-card">
                        <div class="dash-stat-value">{{ $volunteer->reputation_score ?? 50 }}</div>
                        <div class="dash-stat-label">{{ __('Reputation') }}</div>
                    </div>
                    <div class="dash-stat-card">
                        <div class="dash-stat-value">{{ $stats['completed_tasks'] ?? 0 }}</div>
                        <div class="dash-stat-label">{{ __('Completed') }}</div>
                    </div>
                    <button type="button" onclick="openSkillsModal()" class="dash-stat-card cursor-pointer">
                        <div class="dash-stat-value">{{ $volunteer->skills?->count() ?? 0 }}</div>
                        <div class="dash-stat-label">{{ __('Skills') }}</div>
                    </button>
                    @if($volunteer->years_of_experience)
                    <div class="dash-stat-card">
                        <div class="dash-stat-value">{{ $volunteer->years_of_experience }}</div>
                        <div class="dash-stat-label">{{ __('Yrs Exp.') }}</div>
                    </div>
                    @endif
                    @php $totalPending = ($stats['pending_assignments'] ?? 0) + ($stats['team_invitations'] ?? 0); @endphp
                    @if($totalPending > 0)
                    <div class="dash-stat-card dash-stat-pending">
                        <div class="dash-stat-value">{{ $totalPending }}</div>
                        <div class="dash-stat-label" style="color: rgba(252,211,77,0.7)">{{ __('Pending') }}</div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         QUICK ACTIONS
    ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">

        <a href="{{ route('assignments.my') }}" class="dash-qa-card">
            <div class="dash-qa-icon bg-indigo-50 dark:bg-indigo-900/30">
                <svg class="w-6 h-6 text-indigo-700 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <span class="dash-qa-label">{{ __('My Tasks') }}</span>
        </a>

        <a href="{{ route('teams.my') }}" class="dash-qa-card">
            <div class="dash-qa-icon bg-violet-50 dark:bg-violet-900/30">
                <svg class="w-6 h-6 text-violet-600 dark:text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="dash-qa-label">{{ __('My Teams') }}</span>
        </a>

        <a href="{{ route('challenges.index') }}" class="dash-qa-card">
            <div class="dash-qa-icon bg-orange-50 dark:bg-orange-900/30">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <span class="dash-qa-label">{{ __('Challenges') }}</span>
        </a>

        <a href="{{ route('community.index') }}" class="dash-qa-card">
            <div class="dash-qa-icon bg-green-50 dark:bg-green-900/30">
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <span class="dash-qa-label">{{ __('Community') }}</span>
        </a>

        <a href="{{ route('certificates.index') }}" class="dash-qa-card">
            <div class="dash-qa-icon bg-amber-50 dark:bg-amber-900/30">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <span class="dash-qa-label">{{ __('Certificates') }}</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="dash-qa-card">
            <div class="dash-qa-icon bg-slate-50 dark:bg-slate-800">
                <svg class="w-6 h-6 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <span class="dash-qa-label">{{ __('Profile') }}</span>
        </a>

    </div>

    {{-- ══════════════════════════════════════════════════════
         ACTION REQUIRED: Pending Items
    ══════════════════════════════════════════════════════ --}}
    @php $hasPendingTeams = isset($teamInvitations) && $teamInvitations->count() > 0; $hasPendingTasks = $pendingAssignments->count() > 0; @endphp
    @if($hasPendingTeams || $hasPendingTasks)
    <div class="dash-action-alert mb-6">
        <div class="dash-action-alert-header">
            <div class="w-9 h-9 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-amber-900 text-sm">{{ __('Action Required') }}</p>
                <p class="text-xs text-amber-700">{{ __('You have items waiting for your response') }}</p>
            </div>
            <span class="text-xs font-bold text-amber-800 bg-amber-200 border border-amber-300 px-3 py-1 rounded-full">
                {{ ($stats['pending_assignments'] ?? 0) + ($stats['team_invitations'] ?? 0) }} {{ __('pending') }}
            </span>
        </div>
        <div class="p-4 space-y-3">
            {{-- Team Invitations --}}
            @if($hasPendingTeams)
            @foreach($teamInvitations->take(2) as $team)
            @php $myMembership = $team->members->where('volunteer_id', $volunteer->id)->first(); @endphp
            <div class="dash-action-item">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-bold text-gray-900 text-sm">{{ $team->name }}</p>
                                <span class="dash-badge dash-badge-violet">{{ __('Team Invite') }}</span>
                                @if($myMembership && $myMembership->role === 'leader')<span class="dash-badge dash-badge-amber">{{ __('Leader') }}</span>@endif
                            </div>
                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ Str::limit($team->challenge->title, 55) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Accept') }}
                            </button>
                        </form>
                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">{{ __('Decline') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            @if($teamInvitations->count() > 2)
            <a href="{{ route('teams.my') }}" class="block text-center text-xs font-bold text-violet-600 hover:text-violet-700 py-1">
                {{ __('+ :n more team invitation(s)', ['n' => $teamInvitations->count() - 2]) }} →
            </a>
            @endif
            @endif

            {{-- Task Assignments --}}
            @if($hasPendingTasks)
            @foreach($pendingAssignments as $assignment)
            <div class="dash-action-item">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-bold text-gray-900 text-sm">{{ $assignment->task->title }}</p>
                                <span class="dash-badge dash-badge-blue">{{ $assignment->match_score }}% {{ __('Match') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate mt-0.5">
                                {{ $assignment->task->estimated_hours }}h &bull; {{ Str::limit($assignment->task->challenge->title, 42) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button" onclick="showAcceptModal({{ $assignment->id }}, '{{ addslashes($assignment->task->title) }}')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Accept') }}
                        </button>
                        <button type="button" onclick="showDeclineModal({{ $assignment->id }}, '{{ addslashes($assignment->task->title) }}')"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">{{ __('Decline') }}</button>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MAIN TWO-COLUMN LAYOUT
    ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── LEFT COLUMN (2/3) ──────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- ACTIVE WORK --}}
            @if(isset($activeTasks) && $activeTasks->count() > 0)
            <div class="dash-section-card">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        {{ __('Active Work') }}
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">{{ $activeTasks->count() }}</span>
                    </div>
                    <a href="{{ route('assignments.my') }}" class="dash-section-link">
                        {{ __('All tasks') }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($activeTasks as $task)
                    @php $assignment = $task->assignments->first(); @endphp
                    <div class="dash-task-card">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug flex-1">{{ $task->title }}</h3>
                            <span class="dash-badge flex-shrink-0 {{ $assignment->invitation_status === 'in_progress' ? 'dash-badge-blue' : 'dash-badge-green' }}">
                                {{ ucfirst(str_replace('_', ' ', $assignment->invitation_status)) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            <span class="font-medium">{{ $task->challenge->title }}</span>
                        </p>
                        @if($task->estimated_hours)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs text-gray-400">{{ $task->estimated_hours }}h {{ __('estimated') }}</span>
                        </div>
                        @endif
                        <a href="{{ route('tasks.show', $task->id) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold mt-auto text-primary-600 dark:text-primary-400">
                            {{ __('Open task') }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            {{-- Empty state for active tasks --}}
            <div class="dash-section-card">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        {{ __('My Tasks') }}
                    </div>
                    <a href="{{ route('assignments.my') }}" class="dash-section-link">{{ __('View all') }} →</a>
                </div>
                <div class="dash-empty">
                    <div class="dash-empty-icon">
                        <svg class="w-8 h-8 text-primary-400 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ __('No active tasks yet') }}</p>
                    <p class="text-xs text-gray-500 mb-5 max-w-xs">{{ __('Browse open challenges and get matched to tasks that fit your skills.') }}</p>
                    <a href="{{ route('challenges.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm font-bold rounded-xl bg-aurora">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        {{ __('Browse Challenges') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- COMMUNITY CHALLENGES --}}
            @if(isset($communityChallenges) && $communityChallenges->count() > 0)
            <div class="dash-section-card">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-green-50 dark:bg-green-900/30">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        {{ __('Community Challenges') }}
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">{{ __('Live') }}</span>
                    </div>
                    <a href="{{ route('community.index') }}" class="dash-section-link">{{ __('View all') }} →</a>
                </div>
                <div>
                    @foreach($communityChallenges->take(5) as $challenge)
                    <a href="{{ route('community.challenge', $challenge->id) }}" class="dash-challenge-row group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50 dark:bg-green-900/30">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-indigo-700 transition-colors truncate">{{ $challenge->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ Str::limit($challenge->refined_brief ?? $challenge->original_description, 85) }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ── RIGHT COLUMN (1/3) ─────────────────────────── --}}
        <div class="space-y-5">

            {{-- PROFILE CARD --}}
            <div class="dash-profile-card">
                {{-- Gradient header --}}
                <div class="dash-profile-hero">
                    <div class="flex items-center justify-between mb-4 relative">
                        <div>
                            <p class="text-white/50 text-[10px] font-semibold uppercase tracking-widest">{{ __('Your Profile') }}</p>
                            <p class="text-white font-extrabold text-lg leading-tight mt-0.5">{{ auth()->user()->name }}</p>
                        </div>
                        @if($volunteer->cv_file_path)
                        <span class="dash-badge" style="background:rgba(255,255,255,0.15);color:white;border-color:rgba(255,255,255,0.25);font-size:10px;">
                            CV: {{ __(ucfirst($volunteer->ai_analysis_status)) }}
                        </span>
                        @else
                        <a href="{{ route('complete-profile') }}" style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.85);background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);padding:4px 10px;border-radius:8px;text-decoration:none;">
                            {{ __('Upload CV') }}
                        </a>
                        @endif
                    </div>
                    @if($volunteer->experience_level)
                    <div class="flex items-center gap-2">
                        <div class="dash-level-badge">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ $volunteer->experience_level }}
                        </div>
                        @if($volunteer->field)
                        <span style="font-size:11px;color:rgba(255,255,255,0.65);font-weight:500;">{{ $volunteer->field }}</span>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Stats row --}}
                <div class="dash-profile-stat-row">
                    <div class="dash-profile-stat">
                        <div class="dash-profile-stat-val">{{ $volunteer->reputation_score ?? 50 }}</div>
                        <div class="dash-profile-stat-lbl">{{ __('Rep.') }}</div>
                    </div>
                    <div class="dash-profile-stat">
                        <div class="dash-profile-stat-val">{{ $stats['completed_tasks'] ?? 0 }}</div>
                        <div class="dash-profile-stat-lbl">{{ __('Done') }}</div>
                    </div>
                    <button type="button" onclick="openSkillsModal()" class="dash-profile-stat cursor-pointer bg-transparent border-0 w-full">
                        <div class="dash-profile-stat-val text-primary-600 dark:text-primary-400">{{ $volunteer->skills?->count() ?? 0 }}</div>
                        <div class="dash-profile-stat-lbl">{{ __('Skills') }}</div>
                    </button>
                    @if($volunteer->years_of_experience)
                    <div class="dash-profile-stat">
                        <div class="dash-profile-stat-val">{{ $volunteer->years_of_experience }}</div>
                        <div class="dash-profile-stat-lbl">{{ __('Yrs') }}</div>
                    </div>
                    @endif
                </div>

                {{-- Profile details --}}
                <div class="p-5 space-y-3">
                    @if($volunteer->field)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-indigo-50 dark:bg-indigo-900/30">
                            <svg class="w-4 h-4 text-indigo-700 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">{{ __('Field') }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $volunteer->field }}</p>
                        </div>
                    </div>
                    @endif
                    @if($volunteer->years_of_experience)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-green-50 dark:bg-green-900/30">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">{{ __('Experience') }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $volunteer->years_of_experience }} {{ __('years') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Skills --}}
                @if($volunteer->skills && $volunteer->skills->count() > 0)
                <div class="px-5 pb-4 border-t border-gray-100 dark:border-gray-800 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">{{ __('Skills') }}</p>
                        <button type="button" onclick="openSkillsModal()" class="text-[10px] font-bold px-2 py-0.5 rounded-full cursor-pointer transition-all hover:scale-105 bg-primary-50 dark:bg-primary-500/15 text-primary-600 dark:text-primary-300 border border-primary-100 dark:border-primary-500/30">{{ $volunteer->skills->count() }}</button>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($volunteer->skills->take(10) as $skill)
                        <span class="dash-skill-chip
                            {{ $skill->proficiency_level === 'expert'       ? 'skill-expert' : '' }}
                            {{ $skill->proficiency_level === 'advanced'     ? 'skill-advanced' : '' }}
                            {{ $skill->proficiency_level === 'intermediate' ? 'skill-intermediate' : '' }}
                            {{ ($skill->proficiency_level ?? 'beginner') === 'beginner' ? 'skill-beginner' : '' }}">
                            {{ $skill->skill_name }}
                        </span>
                        @endforeach
                        @if($volunteer->skills->count() > 10)
                        <button type="button" onclick="openSkillsModal()" class="dash-skill-chip skill-beginner cursor-pointer border-dashed">
                            +{{ $volunteer->skills->count() - 10 }} {{ __('more') }}
                        </button>
                        @endif
                    </div>
                </div>
                @elseif(!$volunteer->cv_file_path)
                <div class="px-5 pb-5 border-t border-gray-100 dark:border-gray-800 pt-4 text-center">
                    <p class="text-xs text-gray-500 mb-3">{{ __('Upload your CV to auto-extract 30+ skills with AI.') }}</p>
                    <a href="{{ route('complete-profile') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 text-white text-xs font-bold rounded-xl w-full justify-center bg-aurora">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        {{ __('Upload CV') }}
                    </a>
                </div>
                @endif

                {{-- LinkedIn connection status --}}
                <div class="px-5 pb-3">
                    @if(auth()->user()->linkedin_id)
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" style="color:#0A66C2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <span class="text-xs font-bold" style="color:#1D4ED8;">{{ __('LinkedIn Verified') }}</span>
                        </div>
                        @if(auth()->user()->linkedin_profile_url)
                        <a href="{{ auth()->user()->linkedin_profile_url }}" target="_blank" rel="noopener noreferrer"
                           class="text-[10px] font-bold" style="color:#0A66C2;">
                            {{ __('View') }} →
                        </a>
                        @endif
                    </div>
                    @else
                    <a href="{{ route('linkedin.connect') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-bold transition-all hover:opacity-90"
                       style="background:#0A66C2;color:white;">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        {{ __('Connect LinkedIn') }}
                    </a>
                    @endif
                </div>

                {{-- Edit Profile Button --}}
                <div class="px-5 pb-5 pt-1">
                    <a href="{{ route('profile.edit') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:border-indigo-300 hover:bg-indigo-50 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('Edit Full Profile') }}
                    </a>
                </div>
            </div>

            {{-- ACHIEVEMENTS --}}
            <div class="dash-section-card">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 dark:bg-amber-900/30">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        {{ __('Achievements') }}
                    </div>
                </div>
                <div class="p-4 space-y-2.5">
                    <div class="dash-ach-card dash-ach-gold">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-100 dark:bg-amber-900/30">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-extrabold text-amber-900 dark:text-amber-300 text-base leading-tight">{{ $volunteer->reputation_score ?? 50 }}</p>
                            <p class="text-xs text-amber-700 dark:text-amber-500 font-medium">{{ __('Reputation Score') }}</p>
                        </div>
                    </div>
                    <div class="dash-ach-card dash-ach-green">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-100 dark:bg-emerald-900/30">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-extrabold text-emerald-900 dark:text-emerald-300 text-base leading-tight">{{ $stats['completed_tasks'] ?? 0 }}</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-500 font-medium">{{ __('Tasks Completed') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('certificates.index') }}" class="dash-ach-card dash-ach-violet no-underline">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-violet-100 dark:bg-violet-900/30">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-violet-800 dark:text-violet-300">{{ __('View Certificates') }}</p>
                            <p class="text-[10px] text-violet-500 dark:text-violet-400">{{ __('Your earned credentials') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-violet-400 dark:text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SKILLS MODAL
══════════════════════════════════════════════════════════ --}}
<div id="skillsModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSkillsModal()"></div>

    {{-- Panel --}}
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-aurora">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.18);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base">{{ __('Analyzed Skills') }}</h3>
                        <p class="text-white/65 text-xs">
                            {{ $volunteer->skills?->count() ?? 0 }} {{ __('skills extracted by AI') }}
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeSkillsModal()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-white/70 hover:text-white hover:bg-white/15 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 max-h-[60vh] overflow-y-auto space-y-5">

                @php
                    $grouped = $volunteer->skills?->groupBy('proficiency_level') ?? collect();
                    $order   = ['expert', 'advanced', 'intermediate', 'beginner'];
                    $levelMeta = [
                        'expert'       => ['label' => __('Expert'),       'chip' => 'bg-primary-50 dark:bg-primary-500/15 text-primary-600 dark:text-primary-300 border-primary-100 dark:border-primary-500/30',  'text' => 'text-primary-600 dark:text-primary-300',  'dot' => 'bg-primary-500'],
                        'advanced'     => ['label' => __('Advanced'),     'chip' => 'bg-indigo-50 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border-indigo-100 dark:border-indigo-500/30',     'text' => 'text-indigo-700 dark:text-indigo-300',    'dot' => 'bg-indigo-700'],
                        'intermediate' => ['label' => __('Intermediate'), 'chip' => 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 border-green-100 dark:border-green-500/30',        'text' => 'text-green-700 dark:text-green-300',      'dot' => 'bg-green-600'],
                        'beginner'     => ['label' => __('Beginner'),     'chip' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700',                    'text' => 'text-gray-500 dark:text-gray-400',        'dot' => 'bg-gray-400'],
                    ];
                @endphp

                @if($volunteer->skills && $volunteer->skills->count() > 0)
                    @foreach($order as $level)
                        @php $skills = $grouped->get($level, collect()); @endphp
                        @if($skills->count() > 0)
                        @php $meta = $levelMeta[$level]; @endphp
                        <div>
                            <div class="flex items-center gap-2 mb-2.5">
                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $meta['dot'] }}"></span>
                                <span class="text-xs font-bold uppercase tracking-wider {{ $meta['text'] }}">{{ $meta['label'] }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full border {{ $meta['chip'] }}">
                                    {{ $skills->count() }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold border {{ $meta['chip'] }}">
                                    {{ $skill->skill_name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach

                    {{-- Any uncategorized --}}
                    @php $uncategorized = $volunteer->skills?->filter(fn($s) => !in_array($s->proficiency_level, $order)) ?? collect(); @endphp
                    @if($uncategorized->count() > 0)
                    <div>
                        <div class="flex items-center gap-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-gray-300 flex-shrink-0"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">{{ __('Other') }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">{{ $uncategorized->count() }}</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($uncategorized as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                                {{ $skill->skill_name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                @else
                    <div class="text-center py-10">
                        <div class="w-14 h-14 rounded-2xl mx-auto mb-3 flex items-center justify-center bg-primary-50 dark:bg-primary-500/15">
                            <svg class="w-7 h-7 text-primary-600 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ __('No skills yet') }}</p>
                        <p class="text-xs text-gray-500 max-w-xs mx-auto">{{ __('Upload your CV and our AI will automatically extract your skills and proficiency levels.') }}</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between gap-3 bg-gray-50 dark:bg-gray-800/50">
                @if($volunteer->cv_file_path)
                <p class="text-xs text-gray-400">
                    <svg class="w-3.5 h-3.5 inline-block mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('CV analyzed — status: ') }}<span class="font-semibold">{{ ucfirst($volunteer->ai_analysis_status ?? 'completed') }}</span>
                </p>
                @else
                <p class="text-xs text-gray-400">{{ __('Upload a CV to analyze skills') }}</p>
                @endif
                <div class="flex gap-2">
                    <button type="button" onclick="closeSkillsModal()"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        {{ __('Close') }}
                    </button>
                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 text-sm font-bold text-white rounded-xl transition-colors bg-aurora">
                        {{ __('Edit Profile') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function openSkillsModal()  { document.getElementById('skillsModal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function closeSkillsModal() { document.getElementById('skillsModal').classList.add('hidden');    document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSkillsModal(); });
</script>

{{-- ══════════════════════════════════════════════════════════
     ACCEPT ASSIGNMENT MODAL
══════════════════════════════════════════════════════════ --}}
<div id="acceptAssignmentModal" class="hidden fixed inset-0 bg-gray-900/70 z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-0 w-full max-w-md px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 elevation-xl overflow-hidden animate-fade-in">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Accept Task Assignment') }}</h3>
                <p class="text-gray-500 text-sm mt-0.5">{{ __('Confirm your participation') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-gray-900 font-semibold text-sm" id="acceptTaskTitle"></p>
                        <p class="text-xs text-gray-500">{{ __('You will be assigned to this task') }}</p>
                    </div>
                </div>
                <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 mb-5">
                    <p class="text-sm text-emerald-800"><strong>{{ __('Note:') }}</strong> {{ __('By accepting, you commit to completing this task. You can only work on one task at a time.') }}</p>
                </div>
                <form id="acceptAssignmentForm" method="POST" action="">
                    @csrf
                    <div class="flex justify-end gap-3">
                        <x-ui.button type="button" onclick="closeAcceptModal()" variant="ghost" size="sm">{{ __('Cancel') }}</x-ui.button>
                        <button class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600" type="submit">{{ __('Accept Task') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     DECLINE ASSIGNMENT MODAL
══════════════════════════════════════════════════════════ --}}
<div id="declineAssignmentModal" class="hidden fixed inset-0 bg-gray-900/70 z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-0 w-full max-w-md px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 elevation-xl overflow-hidden animate-fade-in">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Decline Task Assignment') }}</h3>
                <p class="text-gray-500 text-sm mt-0.5">{{ __('This task will be offered to others') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-gray-900 font-semibold text-sm" id="declineTaskTitle"></p>
                        <p class="text-xs text-gray-500">{{ __('Are you sure you want to decline?') }}</p>
                    </div>
                </div>
                <form id="declineAssignmentForm" method="POST" action="">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Reason (optional)') }}</label>
                        <textarea name="reason" rows="4" class="w-full rounded-lg border-gray-300 text-sm p-3" placeholder="{{ __('Let us know why you are declining...') }}"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <x-ui.button type="button" onclick="closeDeclineModal()" variant="ghost" size="sm">{{ __('Cancel') }}</x-ui.button>
                        <x-ui.button type="submit" variant="danger" size="sm">{{ __('Decline Task') }}</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showAcceptModal(assignmentId, taskTitle) {
    document.getElementById('acceptTaskTitle').textContent = taskTitle;
    document.getElementById('acceptAssignmentForm').action = '{{ url("assignments") }}/' + assignmentId + '/accept';
    document.getElementById('acceptAssignmentModal').classList.remove('hidden');
}
function closeAcceptModal() { document.getElementById('acceptAssignmentModal').classList.add('hidden'); }
function showDeclineModal(assignmentId, taskTitle) {
    document.getElementById('declineTaskTitle').textContent = taskTitle;
    document.getElementById('declineAssignmentForm').action = '{{ url("assignments") }}/' + assignmentId + '/decline';
    document.getElementById('declineAssignmentModal').classList.remove('hidden');
}
function closeDeclineModal() { document.getElementById('declineAssignmentModal').classList.add('hidden'); }
document.getElementById('acceptAssignmentModal')?.addEventListener('click', function(e) { if (e.target === this) closeAcceptModal(); });
document.getElementById('declineAssignmentModal')?.addEventListener('click', function(e) { if (e.target === this) closeDeclineModal(); });
</script>
@endsection
