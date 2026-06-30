@extends('layouts.app')

@section('title', ($story['pain']['title'] ?? 'Success Story') . ' — Project Archive')

@section('content')
@php
$colorPalette = [
    'indigo'  => ['bg'=>'#5A3DEB','light'=>'#EDE9FD','text'=>'#5A3DEB','border'=>'#D4CBFA','badge-bg'=>'#EDE9FD','badge-text'=>'#5A3DEB'],
    'violet'  => ['bg'=>'#7C3AED','light'=>'#F5F3FF','text'=>'#7C3AED','border'=>'#DDD6FE','badge-bg'=>'#F5F3FF','badge-text'=>'#7C3AED'],
    'emerald' => ['bg'=>'#059669','light'=>'#D1FAE5','text'=>'#059669','border'=>'#6EE7B7','badge-bg'=>'#ECFDF5','badge-text'=>'#059669'],
    'amber'   => ['bg'=>'#D97706','light'=>'#FEF3C7','text'=>'#D97706','border'=>'#FDE68A','badge-bg'=>'#FEF3C7','badge-text'=>'#D97706'],
    'cyan'    => ['bg'=>'#0891B2','light'=>'#CFFAFE','text'=>'#0891B2','border'=>'#A5F3FC','badge-bg'=>'#ECFEFF','badge-text'=>'#0891B2'],
    'teal'    => ['bg'=>'#0D9488','light'=>'#CCFBF1','text'=>'#0D9488','border'=>'#99F6E4','badge-bg'=>'#F0FDFA','badge-text'=>'#0D9488'],
    'pink'    => ['bg'=>'#DB2777','light'=>'#FCE7F3','text'=>'#DB2777','border'=>'#F9A8D4','badge-bg'=>'#FDF2F8','badge-text'=>'#DB2777'],
    'blue'    => ['bg'=>'#2563EB','light'=>'#DBEAFE','text'=>'#2563EB','border'=>'#93C5FD','badge-bg'=>'#EFF6FF','badge-text'=>'#2563EB'],
    'rose'    => ['bg'=>'#E11D48','light'=>'#FFE4E6','text'=>'#E11D48','border'=>'#FDA4AF','badge-bg'=>'#FFF1F2','badge-text'=>'#E11D48'],
];
$fc = $colorPalette[$story['color']] ?? $colorPalette['indigo'];

$timelineIcons = [
    'briefcase'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    'cpu'         => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>',
    'chart'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
    'users'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
    'check'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'shield'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
    'search'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
    'assign'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
    'wrench'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'upload'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>',
    'star'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    'certificate' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
    'trophy'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
];

$activityColors = [
    'upload'  => ['bg'=>'#DBEAFE','text'=>'#2563EB','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>'],
    'task'    => ['bg'=>'#D1FAE5','text'=>'#059669','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    'ai'      => ['bg'=>'#CFFAFE','text'=>'#0891B2','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>'],
    'review'  => ['bg'=>'#F5F3FF','text'=>'#7C3AED','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'],
    'comment' => ['bg'=>'#FEF3C7','text'=>'#D97706','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
    'approve' => ['bg'=>'#D1FAE5','text'=>'#059669','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
];
@endphp

<style>
/* ── Page scaffolding ── */
.ss-page { background:#F0F2F5; min-height:100vh; }
.ss-card { background:#FFFFFF; border:1px solid #E4E6EB; border-radius:12px; box-shadow:0 1px 2px rgba(0,0,0,0.10); }
.ss-label { font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#65676B; }

/* ── Hero ── */
.ss-hero { background:#FFFFFF; border-bottom:1px solid #E4E6EB; }

/* ── Section headers ── */
.ss-section-header { display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; }
.ss-section-num { width:2rem; height:2rem; border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; flex-shrink:0; }
.ss-section-title { font-size:1.0625rem; font-weight:700; color:#1C1E21; }
.ss-section-sub { font-size:.8rem; color:#65676B; margin-top:.1rem; }

/* ── Timeline ── */
.tl-wrap { position:relative; padding-left:2.5rem; }
.tl-wrap::before { content:''; position:absolute; left:.9rem; top:0; bottom:0; width:2px; background:linear-gradient(to bottom,#E4E6EB,#F0F2F5); }
.tl-item { position:relative; padding-bottom:1.5rem; }
.tl-item:last-child { padding-bottom:0; }
.tl-dot { position:absolute; left:-2.5rem; width:1.8rem; height:1.8rem; border-radius:.5rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:2px solid #FFFFFF; }
.tl-dot.done { background:#D1FAE5; border-color:#FFFFFF; }
.tl-dot.done svg { color:#059669; }

/* ── Activity feed ── */
.af-item { display:flex; gap:.875rem; align-items:flex-start; padding:.875rem 0; border-bottom:1px solid #F0F2F5; }
.af-item:last-child { border-bottom:none; }
.af-avatar { width:2.25rem; height:2.25rem; border-radius:.75rem; display:flex; align-items:center; justify-content:center; font-size:.65rem; font-weight:800; flex-shrink:0; color:#1C1E21; background:#E4E6EB; }

/* ── Expert cards ── */
.expert-card { transition:transform .2s,box-shadow .2s; }
.expert-card:hover { transform:translateY(-2px); box-shadow:0 4px 16px rgba(0,0,0,0.10); }
.star-filled { color:#F59E0B; }
.star-empty  { color:#E4E6EB; }

/* ── Impact metrics ── */
.impact-metric { text-align:center; }
.impact-value { font-size:2rem; font-weight:900; line-height:1; }

/* ── Certificate ── */
.cert-card { background:#FFFFFF; border:1px solid #E4E6EB; border-radius:12px; overflow:hidden; box-shadow:0 1px 2px rgba(0,0,0,0.10); }
.cert-verified { display:inline-flex; align-items:center; gap:.35rem; background:#ECFDF5; color:#059669; border:1px solid #6EE7B7; border-radius:999px; font-size:.7rem; font-weight:700; padding:.2rem .65rem; }

/* ── Reputation ── */
.rep-card { overflow:hidden; }
.rep-bar { height:6px; border-radius:999px; background:#E4E6EB; overflow:hidden; }
.rep-bar-fill { height:100%; border-radius:999px; transition:width 1.5s cubic-bezier(.4,0,.2,1); width:0; }
.rep-bar-fill.animated { width:var(--w); }

/* ── QR placeholder ── */
.qr-block { width:56px; height:56px; background:#F0F2F5; border-radius:.5rem; display:grid; grid-template-columns:repeat(5,1fr); gap:2px; padding:6px; }
.qr-cell { border-radius:1px; }

/* ── Sticky TOC ── */
.ss-toc { position:sticky; top:4.5rem; }
.toc-link { display:flex; align-items:center; gap:.5rem; padding:.4rem .75rem; border-radius:.5rem; font-size:.78rem; color:#65676B; transition:all .15s; border:1px solid transparent; }
.toc-link:hover { background:#F0F2F5; color:#1C1E21; border-color:#E4E6EB; }

@keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
.fade-up { opacity:0; animation:fadeUp .4s ease forwards; }
</style>

<div class="ss-page">

    {{-- ═══════════════════════════════ HERO ═══════════════════════════════ --}}
    <div class="ss-hero">
        {{-- Accent top bar --}}
        <div style="height:4px;background:{{ $fc['bg'] }};"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs mb-7" style="color:#65676B;">
                <a href="{{ route('home') }}" class="hover:underline" style="color:#65676B;">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('success-stories') }}" class="hover:underline" style="color:#65676B;">Success Stories</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span style="color:{{ $fc['text'] }};">{{ $story['field'] }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                {{-- Company logo --}}
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-black text-xl flex-shrink-0"
                     style="background:{{ $fc['light'] }};border:1px solid {{ $fc['border'] }};color:{{ $fc['text'] }};">
                    {{ $story['company']['logo'] }}
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full border"
                              style="background:{{ $fc['badge-bg'] }};color:{{ $fc['badge-text'] }};border-color:{{ $fc['border'] }};">
                            {{ $story['field'] }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 rounded-full"
                              style="background:#ECFDF5;color:#059669;border:1px solid #6EE7B7;">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified &amp; Archived
                        </span>
                        <span class="text-xs" style="color:#65676B;">Completed in {{ $story['duration'] }} · Posted {{ $story['posted'] }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold leading-snug mb-2" style="color:#1C1E21;">
                        {{ $story['pain']['title'] }}
                    </h1>
                    <p class="text-sm" style="color:#65676B;">
                        {{ $story['company']['name'] }} · {{ $story['company']['country'] }} · {{ $story['company']['industry'] }}
                    </p>
                </div>
            </div>

            {{-- Hero impact bar --}}
            <div class="mt-7 grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach(array_slice($story['impact'], 0, 4) as $m)
                @php $mc = $colorPalette[$m['color']] ?? $colorPalette['indigo']; @endphp
                <div class="rounded-xl px-4 py-3 text-center" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                    <div class="text-xl font-extrabold" style="color:{{ $mc['text'] }};">{{ $m['value'] }}</div>
                    <div class="text-xs mt-0.5" style="color:#65676B;">{{ $m['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════ MAIN CONTENT ═══════════════════════════ --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-10">

        {{-- ── §1 Company Information ── --}}
        <section id="s-company">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">01</div>
                <div>
                    <div class="ss-section-title">Company Information</div>
                    <div class="ss-section-sub">Client profile at time of challenge submission</div>
                </div>
            </div>
            <div class="ss-card p-6">
                <div class="flex items-start gap-5 mb-6">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center font-black text-lg flex-shrink-0"
                         style="background:{{ $fc['light'] }};border:1px solid {{ $fc['border'] }};color:{{ $fc['text'] }};">
                        {{ $story['company']['logo'] }}
                    </div>
                    <div>
                        <div class="text-lg font-bold" style="color:#1C1E21;">{{ $story['company']['name'] }}</div>
                        <div class="text-sm" style="color:#65676B;">{{ $story['company']['industry'] }} · {{ $story['company']['country'] }}</div>
                        <div class="flex items-center gap-1 mt-1">
                            @for($i=1;$i<=5;$i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= floor($story['company']['rating']) ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs ml-1" style="color:#65676B;">{{ $story['company']['rating'] }} ({{ $story['company']['reviews'] }} reviews)</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach([['Employees',$story['company']['employees']],['Country',$story['company']['country']],['Category',$story['company']['category']],['Platform Rating',number_format($story['company']['rating'],1).' ⭐']] as [$lbl,$val])
                    <div class="rounded-lg p-3" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                        <div class="ss-label mb-1">{{ $lbl }}</div>
                        <div class="text-sm font-semibold" style="color:#1C1E21;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ── §2 Initial Business Pain ── --}}
        <section id="s-pain">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">02</div>
                <div>
                    <div class="ss-section-title">Initial Business Pain</div>
                    <div class="ss-section-sub">Submitted exactly as the company posted it</div>
                </div>
            </div>
            <div class="ss-card p-6">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full"
                          style="background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5;">
                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#B91C1C;"></span>
                        Priority: {{ $story['pain']['priority'] }}
                    </span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background:#F0F2F5;color:#1C1E21;border:1px solid #E4E6EB;">Budget: {{ $story['pain']['budget'] }}</span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background:#F0F2F5;color:#1C1E21;border:1px solid #E4E6EB;">Posted: {{ $story['posted'] }}</span>
                </div>

                <h3 class="text-lg font-bold mb-3" style="color:#1C1E21;">{{ $story['pain']['title'] }}</h3>
                <p class="text-sm leading-relaxed mb-5" style="color:#1C1E21;">{{ $story['pain']['description'] }}</p>

                <div class="flex flex-wrap gap-2 mb-5">
                    @foreach($story['pain']['tags'] as $tag)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg"
                          style="background:{{ $fc['badge-bg'] }};color:{{ $fc['badge-text'] }};border:1px solid {{ $fc['border'] }};">{{ $tag }}</span>
                    @endforeach
                </div>

                <div class="pt-4" style="border-top:1px solid #E4E6EB;">
                    <div class="ss-label mb-2">Attached Files</div>
                    <div class="flex flex-col gap-2">
                        @foreach($story['pain']['files'] as $file)
                        <div class="flex items-center gap-3 rounded-lg px-3 py-2.5" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <svg class="w-4 h-4 flex-shrink-0" style="color:#65676B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm font-medium" style="color:#1C1E21;">{{ $file }}</span>
                            <span class="ml-auto text-xs" style="color:#9CA3AF;">Confidential</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ── §3 AI Initial Analysis ── --}}
        <section id="s-ai-analysis">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">03</div>
                <div>
                    <div class="ss-section-title">AI Initial Analysis</div>
                    <div class="ss-section-sub">Generated by Mindova AI within 2 minutes of submission</div>
                </div>
            </div>
            <div class="ss-card overflow-hidden">
                {{-- AI header --}}
                <div class="px-6 py-4 flex items-center gap-3" style="background:#ECFEFF;border-bottom:1px solid #A5F3FC;">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background:#CFFAFE;border:1px solid #A5F3FC;">
                        <svg class="w-4 h-4" style="color:#0891B2;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold" style="color:#0891B2;">Mindova AI — Challenge Brief Analysis</div>
                        <div class="text-xs" style="color:#65676B;">{{ $story['posted'] }} · Processing time: 1m 47s</div>
                    </div>
                    <div class="ml-auto flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                         style="background:#ECFDF5;color:#059669;border:1px solid #6EE7B7;">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#059669;"></span>
                        Confidence {{ $story['ai_analysis']['confidence'] }}%
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <div class="ss-label mb-2">Challenge Summary</div>
                        <p class="text-sm leading-relaxed" style="color:#1C1E21;">{{ $story['ai_analysis']['summary'] }}</p>
                    </div>

                    <div>
                        <div class="ss-label mb-2">Estimated Root Causes</div>
                        <div class="space-y-2">
                            @foreach($story['ai_analysis']['root_causes'] as $i => $cause)
                            <div class="flex items-start gap-3 rounded-lg px-4 py-3"
                                 style="background:#FEF3C7;border:1px solid #FDE68A;">
                                <span class="flex-shrink-0 w-5 h-5 rounded text-xs font-bold flex items-center justify-center mt-0.5"
                                      style="background:#FEF3C7;color:#D97706;border:1px solid #FDE68A;">{{ $i+1 }}</span>
                                <span class="text-sm" style="color:#1C1E21;">{{ $cause }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div class="rounded-xl p-4" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="ss-label mb-2">Required Expertise</div>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @foreach($story['ai_analysis']['expertise'] as $exp)
                                <span class="text-xs font-medium px-2 py-0.5 rounded-md"
                                      style="background:#EDE9FD;color:#5A3DEB;border:1px solid #D4CBFA;">{{ $exp }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="rounded-xl p-4" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="ss-label mb-2">Complexity Score</div>
                            <div class="text-3xl font-extrabold mt-1" style="color:#1C1E21;">{{ $story['ai_analysis']['complexity'] }}<span class="text-base" style="color:#65676B;">/10</span></div>
                            <div class="rep-bar mt-2">
                                <div class="rep-bar-fill js-bar" style="--w:{{ ($story['ai_analysis']['complexity']/10)*100 }}%;background:{{ $fc['bg'] }};"></div>
                            </div>
                        </div>
                        <div class="rounded-xl p-4" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="ss-label mb-2">Estimated Timeline</div>
                            <div class="text-xl font-extrabold mt-1" style="color:#1C1E21;">{{ $story['ai_analysis']['timeline'] }}</div>
                            <div class="text-xs mt-1" style="color:#65676B;">From posting to delivery</div>
                        </div>
                    </div>

                    <div class="rounded-xl p-4" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                        <div class="ss-label mb-1.5">Suggested Team Composition</div>
                        <div class="text-sm" style="color:#1C1E21;">{{ $story['ai_analysis']['team'] }}</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── §4 Selected Experts ── --}}
        @if(!empty($story['experts']))
        <section id="s-experts">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">04</div>
                <div>
                    <div class="ss-section-title">Selected Experts</div>
                    <div class="ss-section-sub">AI-matched and accepted within 2 hours</div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($story['experts'] as $expert)
                @php $ec = $colorPalette[$expert['color']] ?? $colorPalette['indigo']; @endphp
                <div class="expert-card ss-card p-5">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0"
                             style="background:{{ $ec['light'] }};border:1px solid {{ $ec['border'] }};color:{{ $ec['text'] }};">
                            {{ $expert['avatar'] }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold truncate" style="color:#1C1E21;">{{ $expert['name'] }}</div>
                            <div class="text-xs" style="color:#65676B;">{{ $expert['title'] }}</div>
                            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-md mt-1"
                                  style="background:{{ $ec['badge-bg'] }};color:{{ $ec['badge-text'] }};border:1px solid {{ $ec['border'] }};">{{ $expert['role'] }}</span>
                        </div>
                    </div>

                    @if(!($expert['is_ai'] ?? false))
                    <div class="flex items-center gap-0.5 mb-3">
                        @for($i=1;$i<=5;$i++)
                        <svg class="w-3.5 h-3.5 {{ $i<=$expert['stars'] ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-2 mb-4 text-center">
                        <div class="rounded-lg py-2" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="text-base font-extrabold" style="color:#1C1E21;">{{ $expert['challenges'] }}@if($expert['is_ai'] ?? false)+@endif</div>
                            <div class="text-xs" style="color:#65676B;">Challenges</div>
                        </div>
                        <div class="rounded-lg py-2" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="text-base font-extrabold" style="color:{{ $ec['text'] }};">{{ $expert['success'] }}%</div>
                            <div class="text-xs" style="color:#65676B;">Success Rate</div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        @foreach(array_slice($expert['skills'],0,3) as $skill)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 flex-shrink-0" style="color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-xs" style="color:#65676B;">{{ $skill }}</span>
                        </div>
                        @endforeach
                    </div>

                    @if(!($expert['is_ai'] ?? false))
                    <div class="mt-4 pt-3 flex items-center justify-between text-xs" style="border-top:1px solid #E4E6EB;">
                        <span style="color:#65676B;">Trust Score</span>
                        <span class="font-bold" style="color:{{ $ec['text'] }};">{{ $expert['trust'] }}%</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── §5 Timeline ── --}}
        @if(!empty($story['timeline']))
        <section id="s-timeline">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">05</div>
                <div>
                    <div class="ss-section-title">Challenge Timeline</div>
                    <div class="ss-section-sub">{{ count($story['timeline']) }} milestones · {{ $story['duration'] }} total</div>
                </div>
            </div>
            <div class="ss-card p-6">
                <div class="tl-wrap">
                    @foreach($story['timeline'] as $ev)
                    <div class="tl-item">
                        <div class="tl-dot done">
                            <svg class="w-3 h-3" style="color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $timelineIcons[$ev['icon']] ?? $timelineIcons['check'] !!}
                            </svg>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium" style="color:#1C1E21;">{{ $ev['event'] }}</span>
                            <span class="text-xs flex-shrink-0 ml-4" style="color:#65676B;">{{ $ev['time'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- ── §6 Team Workspace ── --}}
        @if(!empty($story['activity']))
        <section id="s-workspace">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">06</div>
                <div>
                    <div class="ss-section-title">Team Workspace</div>
                    <div class="ss-section-sub">Real project activity log</div>
                </div>
            </div>
            <div class="ss-card p-4">
                @foreach($story['activity'] as $act)
                @php $ac = $activityColors[$act['type']] ?? $activityColors['task']; @endphp
                <div class="af-item">
                    <div class="af-avatar" style="background:{{ $ac['bg'] }};color:{{ $ac['text'] }};">
                        {{ $act['avatar'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm" style="color:#1C1E21;">
                            <span class="font-semibold">{{ $act['actor'] }}</span>
                            <span style="color:#65676B;"> {{ $act['action'] }}</span>
                        </div>
                        <div class="text-xs mt-0.5" style="color:#9CA3AF;">{{ $act['time'] }}</div>
                    </div>
                    <div class="rounded-md p-1.5 flex-shrink-0" style="background:{{ $ac['bg'] }};">
                        <svg class="w-3.5 h-3.5" style="color:{{ $ac['text'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $ac['icon'] !!}
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── §7 Individual Contributions ── --}}
        @if(!empty($story['contributions']))
        <section id="s-contributions">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">07</div>
                <div>
                    <div class="ss-section-title">Individual Contributions</div>
                    <div class="ss-section-sub">Verified hours and deliverables per expert</div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach($story['contributions'] as $contrib)
                @php $cc = $colorPalette[$contrib['color']] ?? $colorPalette['indigo']; @endphp
                <div class="ss-card p-5">
                    <div class="flex items-center gap-3 mb-4 pb-4" style="border-bottom:1px solid #E4E6EB;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0"
                             style="background:{{ $cc['light'] }};border:1px solid {{ $cc['border'] }};color:{{ $cc['text'] }};">
                            {{ $contrib['avatar'] }}
                        </div>
                        <div>
                            <div class="font-bold text-sm" style="color:#1C1E21;">{{ $contrib['name'] }}</div>
                            <div class="text-xs" style="color:#65676B;">{{ $contrib['title'] }}</div>
                        </div>
                        <div class="ml-auto text-right">
                            <div class="text-lg font-extrabold" style="color:{{ $cc['text'] }};">{{ $contrib['score'] }}%</div>
                            <div class="text-xs" style="color:#65676B;">Contribution</div>
                        </div>
                    </div>

                    <div class="space-y-1.5 mb-4">
                        <div class="ss-label mb-1.5">Tasks Completed</div>
                        @foreach($contrib['tasks'] as $task)
                        <div class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" style="color:#059669;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-xs" style="color:#1C1E21;">{{ $task }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="rounded-lg py-2.5" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="text-sm font-extrabold" style="color:#1C1E21;">{{ $contrib['hours'] }}h</div>
                            <div class="text-xs mt-0.5" style="color:#65676B;">Hours</div>
                        </div>
                        <div class="rounded-lg py-2.5" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="text-sm font-extrabold" style="color:#1C1E21;">{{ $contrib['files'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#65676B;">Files</div>
                        </div>
                        <div class="rounded-lg py-2.5" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="text-sm font-extrabold" style="color:#059669;">{{ $contrib['accepted'] }}/{{ $contrib['ideas'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#65676B;">Ideas</div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs" style="color:#65676B;">Expert Approval</span>
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full"
                              style="background:#ECFDF5;color:#059669;border:1px solid #6EE7B7;">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ $contrib['verdict'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── §8 AI Summary ── --}}
        <section id="s-ai-summary">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">08</div>
                <div>
                    <div class="ss-section-title">AI Final Summary</div>
                    <div class="ss-section-sub">Pattern analysis and recommendation</div>
                </div>
            </div>
            <div class="ss-card overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between" style="background:#ECFEFF;border-bottom:1px solid #A5F3FC;">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" style="color:#0891B2;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        <span class="text-sm font-bold" style="color:#0891B2;">Mindova AI — Final Analysis Report</span>
                    </div>
                    <span class="text-xs" style="color:#65676B;">Confidence: {{ $story['ai_summary']['confidence'] }}%</span>
                </div>

                <div class="p-6 space-y-5">
                    <div class="grid sm:grid-cols-3 gap-4">
                        <div class="rounded-xl p-4 text-center" style="background:#FEF2F2;border:1px solid #FCA5A5;">
                            <div class="text-2xl font-extrabold" style="color:#B91C1C;">{{ $story['ai_summary']['problems'] }}</div>
                            <div class="text-xs mt-1" style="color:#65676B;">Problems Identified</div>
                        </div>
                        <div class="rounded-xl p-4 text-center" style="background:#FEF3C7;border:1px solid #FDE68A;">
                            <div class="text-2xl font-extrabold" style="color:#D97706;">{{ $story['ai_summary']['repeated_ideas'] }}</div>
                            <div class="text-xs mt-1" style="color:#65676B;">Repeated Patterns</div>
                        </div>
                        <div class="rounded-xl p-4 text-center" style="background:#ECFDF5;border:1px solid #6EE7B7;">
                            <div class="text-lg font-extrabold" style="color:#059669;">{{ $story['ai_summary']['cost_savings'] }}</div>
                            <div class="text-xs mt-1" style="color:#65676B;">Est. Monthly Savings</div>
                        </div>
                    </div>

                    <div>
                        <div class="ss-label mb-2">Final Recommendation</div>
                        <div class="rounded-xl p-4" style="background:#EDE9FD;border:1px solid #D4CBFA;">
                            <p class="text-sm leading-relaxed" style="color:#1C1E21;">{{ $story['ai_summary']['recommendation'] }}</p>
                        </div>
                    </div>

                    @if(!empty($story['ai_summary']['risks']))
                    <div>
                        <div class="ss-label mb-2">Risk Analysis</div>
                        <div class="space-y-2">
                            @foreach($story['ai_summary']['risks'] as $risk)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:#D97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span class="text-sm" style="color:#1C1E21;">{{ $risk }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div>
                        <div class="ss-label mb-2">Business Impact</div>
                        <p class="text-sm leading-relaxed" style="color:#1C1E21;">{{ $story['ai_summary']['business_impact'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── §9 Final Solution ── --}}
        <section id="s-solution">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">09</div>
                <div>
                    <div class="ss-section-title">Final Solution</div>
                    <div class="ss-section-sub">Delivered and approved by client</div>
                </div>
            </div>
            <div class="ss-card p-6">
                <div class="flex items-center gap-2 mb-5">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full"
                          style="background:#ECFDF5;color:#059669;border:1px solid #6EE7B7;">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Company Approved
                    </span>
                </div>

                <div class="mb-6">
                    <div class="ss-label mb-2">Executive Summary</div>
                    <p class="text-sm leading-relaxed" style="color:#1C1E21;">{{ $story['solution']['executive_summary'] }}</p>
                </div>

                <div class="mb-6">
                    <div class="ss-label mb-3">Technical Improvements Implemented</div>
                    <div class="space-y-2">
                        @foreach($story['solution']['improvements'] as $imp)
                        <div class="flex items-start gap-3 rounded-lg px-4 py-3" style="background:#ECFDF5;border:1px solid #6EE7B7;">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:#059669;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm" style="color:#1C1E21;">{{ $imp }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Before / After --}}
                <div class="ss-label mb-3">Before / After</div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-xl p-4" style="background:#FEF2F2;border:1px solid #FCA5A5;">
                        <div class="text-xs font-bold mb-3 flex items-center gap-1.5" style="color:#B91C1C;">
                            <span class="w-2 h-2 rounded-full" style="background:#B91C1C;"></span>BEFORE
                        </div>
                        @foreach($story['solution']['before'] as $k => $v)
                        <div class="mb-2">
                            <div class="text-xs" style="color:#65676B;">{{ $k }}</div>
                            <div class="text-sm font-semibold" style="color:#B91C1C;">{{ $v }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="rounded-xl p-4" style="background:#ECFDF5;border:1px solid #6EE7B7;">
                        <div class="text-xs font-bold mb-3 flex items-center gap-1.5" style="color:#059669;">
                            <span class="w-2 h-2 rounded-full animate-pulse" style="background:#059669;"></span>AFTER
                        </div>
                        @foreach($story['solution']['after'] as $k => $v)
                        <div class="mb-2">
                            <div class="text-xs" style="color:#65676B;">{{ $k }}</div>
                            <div class="text-sm font-semibold" style="color:#059669;">{{ $v }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ── §10 Business Impact ── --}}
        <section id="s-impact">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">10</div>
                <div>
                    <div class="ss-section-title">Business Impact</div>
                    <div class="ss-section-sub">Measured 30 days post-implementation</div>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($story['impact'] as $metric)
                @php $mc = $colorPalette[$metric['color']] ?? $colorPalette['indigo']; @endphp
                <div class="ss-card p-5 text-center hover:scale-[1.02] transition-transform cursor-default">
                    <div class="impact-value mb-1" style="color:{{ $mc['text'] }};">{{ $metric['value'] }}</div>
                    <div class="text-sm font-semibold mb-1" style="color:#1C1E21;">{{ $metric['label'] }}</div>
                    <div class="text-xs" style="color:#65676B;">{{ $metric['sub'] }}</div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── §11 Company Feedback ── --}}
        <section id="s-feedback">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">11</div>
                <div>
                    <div class="ss-section-title">Company Feedback</div>
                    <div class="ss-section-sub">Verified post-project review</div>
                </div>
            </div>
            <div class="ss-card p-6">
                <div class="flex items-start gap-2 mb-5">
                    <div class="flex gap-1">
                        @for($i=1;$i<=5;$i++)
                        <svg class="w-5 h-5 {{ $i<=$story['feedback']['rating'] ? 'star-filled' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-sm ml-2" style="color:#65676B;">· {{ $story['feedback']['date'] }}</span>
                    </div>
                </div>

                <blockquote class="text-sm leading-relaxed italic mb-6" style="color:#1C1E21;">
                    "{{ $story['feedback']['review'] }}"
                </blockquote>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold"
                             style="background:#E4E6EB;color:#1C1E21;">
                            {{ substr($story['feedback']['rep'],0,2) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold" style="color:#1C1E21;">{{ $story['feedback']['rep'] }}</div>
                            <div class="text-xs" style="color:#65676B;">{{ $story['feedback']['position'] }} · {{ $story['company']['name'] }}</div>
                        </div>
                    </div>
                    @if($story['feedback']['recommend'])
                    <div class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full"
                         style="background:#ECFDF5;color:#059669;border:1px solid #6EE7B7;">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Would Recommend
                    </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ── §12 Professional Certificates ── --}}
        @if(!empty($story['certificates']))
        <section id="s-certificates">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">12</div>
                <div>
                    <div class="ss-section-title">Professional Certificates</div>
                    <div class="ss-section-sub">Issued on-chain · Permanently verifiable</div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($story['certificates'] as $cert)
                @php $cc = $colorPalette[$cert['color']] ?? $colorPalette['indigo']; @endphp
                <div class="cert-card">
                    <div style="height:4px;background:{{ $cc['bg'] }};"></div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xs font-black tracking-widest uppercase" style="color:#65676B;">Mindova</div>
                            <span class="cert-verified">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verified
                            </span>
                        </div>

                        <div class="text-xs mb-1" style="color:#65676B;">This certifies that</div>
                        <div class="text-base font-extrabold mb-0.5" style="color:#1C1E21;">{{ $cert['name'] }}</div>
                        <div class="text-xs mb-4" style="color:#65676B;">{{ $cert['role'] }}</div>

                        <div class="text-xs mb-1" style="color:#65676B;">has successfully completed</div>
                        <div class="text-sm font-bold mb-4" style="color:{{ $cc['text'] }};">{{ Str::limit($story['pain']['title'], 50) }}</div>

                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="rounded-lg p-2 text-center" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                                <div class="text-base font-extrabold" style="color:#1C1E21;">{{ $cert['hours'] }}h</div>
                                <div class="text-xs" style="color:#65676B;">Verified Hours</div>
                            </div>
                            <div class="rounded-lg p-2 text-center" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                                <div class="text-base font-extrabold" style="color:{{ $cc['text'] }};">{{ $cert['score'] }}%</div>
                                <div class="text-xs" style="color:#65676B;">Quality Score</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-3" style="border-top:1px solid #E4E6EB;">
                            <div class="qr-block">
                                @php $pattern = [1,0,1,1,0,0,1,1,0,1,1,0,0,1,1,0,1,0,0,1,1,1,0,1,0]; @endphp
                                @foreach($pattern as $cell)
                                <div class="qr-cell" style="{{ $cell ? 'background:'.$cc['bg'].';' : '' }}"></div>
                                @endforeach
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-mono" style="color:#65676B;">{{ $cert['id'] }}</div>
                                <div class="text-xs mt-0.5" style="color:#65676B;">{{ $story['posted'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── §13 Reputation Updates ── --}}
        @if(!empty($story['reputation']))
        <section id="s-reputation">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">13</div>
                <div>
                    <div class="ss-section-title">Reputation Updates</div>
                    <div class="ss-section-sub">Stars and trust scores after project completion</div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach($story['reputation'] as $rep)
                @php $rc = $colorPalette[$rep['color']] ?? $colorPalette['indigo']; @endphp
                <div class="rep-card ss-card p-5">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm"
                             style="background:{{ $rc['light'] }};border:1px solid {{ $rc['border'] }};color:{{ $rc['text'] }};">
                            {{ $rep['avatar'] }}
                        </div>
                        <div>
                            <div class="font-bold text-sm" style="color:#1C1E21;">{{ $rep['name'] }}</div>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex items-center gap-1 text-sm font-extrabold px-2.5 py-1 rounded-full"
                                  style="background:{{ $rc['badge-bg'] }};color:{{ $rc['badge-text'] }};border:1px solid {{ $rc['border'] }};">
                                +{{ $rep['gained'] }} ⭐
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs mb-1.5" style="color:#65676B;">
                                <span>Reputation Stars</span>
                                <span class="font-bold" style="color:{{ $rc['text'] }};">{{ number_format($rep['stars_before']) }} → {{ number_format($rep['stars_after']) }}</span>
                            </div>
                            <div class="rep-bar">
                                <div class="rep-bar-fill js-bar" style="--w:{{ min(($rep['stars_after']/1200)*100,100) }}%;background:{{ $rc['bg'] }};"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl p-3" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                                <div class="ss-label mb-1">Trust Score</div>
                                <div class="text-sm font-bold">
                                    <span style="color:#65676B;">{{ $rep['trust_before'] }}%</span>
                                    <span class="mx-1" style="color:#9CA3AF;">→</span>
                                    <span style="color:{{ $rc['text'] }};">{{ $rep['trust_after'] }}%</span>
                                </div>
                            </div>
                            <div class="rounded-xl p-3" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                                <div class="ss-label mb-1">Challenges</div>
                                <div class="text-sm font-bold">
                                    <span style="color:#65676B;">{{ $rep['challenges'][0] }}</span>
                                    <span class="mx-1" style="color:#9CA3AF;">→</span>
                                    <span style="color:{{ $rc['text'] }};">{{ $rep['challenges'][1] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl p-3" style="background:#F7F8FA;border:1px solid #E4E6EB;">
                            <div class="ss-label mb-1">Verified Hours</div>
                            <div class="text-sm font-bold">
                                <span style="color:#65676B;">{{ $rep['hours'][0] }}h</span>
                                <span class="mx-1" style="color:#9CA3AF;">→</span>
                                <span style="color:{{ $rc['text'] }};">{{ $rep['hours'][1] }}h</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── §14 Related Stories ── --}}
        @if(!empty($related))
        <section id="s-related">
            <div class="ss-section-header">
                <div class="ss-section-num" style="background:{{ $fc['light'] }};color:{{ $fc['text'] }};">14</div>
                <div>
                    <div class="ss-section-title">Related Success Stories</div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach($related as $rel)
                @php $rc = $colorPalette[$rel['color']] ?? $colorPalette['indigo']; @endphp
                <a href="{{ route('success-stories.show', $rel['slug']) }}"
                   class="ss-card p-5 block hover:scale-[1.01] transition-transform group" style="text-decoration:none;">
                    <div class="h-0.5 w-8 rounded mb-4 group-hover:w-16 transition-all duration-300" style="background:{{ $rc['bg'] }};"></div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold" style="color:{{ $rc['text'] }};">{{ $rel['field'] }}</span>
                        <span style="color:#E4E6EB;">·</span>
                        <span class="text-xs" style="color:#65676B;">{{ $rel['duration'] }}</span>
                    </div>
                    <h4 class="text-sm font-bold mb-2 group-hover:underline" style="color:#1C1E21;">{{ $rel['pain']['title'] }}</h4>
                    <div class="text-xs" style="color:#65676B;">{{ $rel['company']['name'] }} · {{ $rel['company']['country'] }}</div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- CTA --}}
        <div class="ss-card p-8 text-center mt-6">
            <div class="text-xl font-extrabold mb-2" style="color:#1C1E21;">Ready to solve your next challenge?</div>
            <p class="text-sm mb-6 max-w-md mx-auto" style="color:#65676B;">Join {{ number_format(2400) }}+ verified experts or post your business problem and get a team in 48 hours.</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 font-semibold px-6 py-3 rounded-xl text-white transition-all hover:opacity-90"
                   style="background:{{ $fc['bg'] }};">
                    Post a Challenge
                </a>
                <a href="{{ route('success-stories') }}"
                   class="inline-flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition-colors"
                   style="background:#F0F2F5;color:#1C1E21;border:1px solid #E4E6EB;">
                    View All Stories
                </a>
            </div>
        </div>

    </div>
</div>

<script>
(function () {
    const bars = document.querySelectorAll('.js-bar');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('animated'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.4 });
    bars.forEach(b => obs.observe(b));
})();
</script>
@endsection
