@extends('layouts.app')

@section('title', 'Success Stories — Verified Outcomes')

@section('content')
@php
$colorMap = [
    'indigo'  => ['bg'=>'bg-primary-500', 'light'=>'bg-primary-50',  'text'=>'text-primary-600', 'border'=>'border-primary-200', 'badge'=>'bg-primary-100 text-primary-700',  'metric'=>'text-primary-600'],
    'teal'    => ['bg'=>'bg-teal-500',    'light'=>'bg-teal-50',     'text'=>'text-teal-600',    'border'=>'border-teal-200',    'badge'=>'bg-teal-100 text-teal-700',    'metric'=>'text-teal-600'],
    'pink'    => ['bg'=>'bg-pink-500',    'light'=>'bg-pink-50',     'text'=>'text-pink-600',    'border'=>'border-pink-200',    'badge'=>'bg-pink-100 text-pink-700',    'metric'=>'text-pink-600'],
    'violet'  => ['bg'=>'bg-violet-500',  'light'=>'bg-violet-50',   'text'=>'text-violet-600',  'border'=>'border-violet-200',  'badge'=>'bg-violet-100 text-violet-700',  'metric'=>'text-violet-600'],
    'emerald' => ['bg'=>'bg-emerald-500', 'light'=>'bg-emerald-50',  'text'=>'text-emerald-600', 'border'=>'border-emerald-200', 'badge'=>'bg-emerald-100 text-emerald-700', 'metric'=>'text-emerald-600'],
    'amber'   => ['bg'=>'bg-amber-500',   'light'=>'bg-amber-50',    'text'=>'text-amber-600',   'border'=>'border-amber-200',   'badge'=>'bg-amber-100 text-amber-700',   'metric'=>'text-amber-600'],
    'cyan'    => ['bg'=>'bg-cyan-500',    'light'=>'bg-cyan-50',     'text'=>'text-cyan-600',    'border'=>'border-cyan-200',    'badge'=>'bg-cyan-100 text-cyan-700',    'metric'=>'text-cyan-600'],
];
@endphp

<style>
.story-card { transition: transform .2s ease, box-shadow .2s ease; }
.story-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(90,61,235,.10); }
</style>

<div class="min-h-screen bg-gray-50">

    {{-- Hero --}}
    <div class="relative overflow-hidden border-b border-gray-200" style="background:linear-gradient(135deg,#EDE9FD 0%,#F5F3FF 50%,#EEF2FF 100%);">
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-primary-200 text-primary-600 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 uppercase tracking-wider">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ __('Verified Outcomes Only') }}
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
                {{ __('Real Problems. Real Experts.') }}<br class="hidden sm:block"> {{ __('Measurable Results.') }}
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-10">
                {{ __('Every project archived here is backed by verified professional certificates, confirmed work hours, and independently measured business outcomes.') }}
            </p>
            {{-- Platform stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
                @foreach([['2,400+',__('Verified Experts')],['94%',__('Average Success Rate')],['$2.4M+',__('Client Savings')],['48h',__('Avg. First Response')]] as [$v,$l])
                <div class="bg-white border border-primary-100 rounded-2xl py-4 px-3 shadow-sm">
                    <div class="text-2xl font-extrabold text-primary-600">{{ $v }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $l }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stories grid --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($stories as $story)
            @php $fc = $colorMap[$story['color']] ?? $colorMap['indigo']; @endphp
            <a href="{{ route('success-stories.show', $story['slug']) }}" class="story-card block bg-white border border-gray-200 rounded-2xl overflow-hidden group">
                {{-- Top color band --}}
                <div class="{{ $fc['bg'] }} h-1.5 w-full"></div>
                <div class="p-6">
                    {{-- Company logo + field badge --}}
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-12 h-12 rounded-xl {{ $fc['light'] }} border {{ $fc['border'] }} flex items-center justify-center {{ $fc['text'] }} font-black text-sm">
                            {{ $story['company']['logo'] }}
                        </div>
                        <span class="inline-flex items-center gap-1 text-[0.72rem] font-semibold px-2.5 py-1 rounded-full {{ $fc['badge'] }}">{{ $story['field'] }}</span>
                    </div>
                    <h3 class="text-gray-900 font-bold text-lg leading-snug mb-1 group-hover:{{ $fc['text'] }} transition-colors">
                        {{ $story['pain']['title'] }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-1">{{ $story['company']['name'] }} · {{ $story['company']['country'] }}</p>
                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-5">{{ Str::limit($story['pain']['description'], 140) }}</p>

                    {{-- Impact metrics --}}
                    <div class="grid grid-cols-2 gap-2 mb-5">
                        @foreach(array_slice($story['impact'], 0, 4) as $metric)
                        @php $mc = $colorMap[$metric['color']] ?? $colorMap['indigo']; @endphp
                        <div class="bg-gray-50 rounded-xl px-3 py-2.5 border border-gray-100">
                            <div class="text-base font-extrabold {{ $mc['metric'] }}">{{ $metric['value'] }}</div>
                            <div class="text-xs text-gray-500 leading-tight mt-0.5">{{ $metric['label'] }}</div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between text-xs text-gray-400 pt-4 border-t border-gray-100">
                        <span>{{ $story['posted'] }}</span>
                        <span class="flex items-center gap-1 {{ $fc['text'] }} font-semibold">
                            {{ __('View Archive') }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Bottom CTA --}}
        <div class="mt-16 text-center bg-primary-500 rounded-2xl p-10">
            <div class="text-2xl font-extrabold text-white mb-3">{{ __('Your challenge could be next.') }}</div>
            <p class="text-white/80 mb-6 max-w-md mx-auto">{{ __('Post your business problem and get matched with verified experts in under 48 hours.') }}</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-primary-600 hover:bg-primary-50 font-semibold px-6 py-3 rounded-xl transition-colors">
                {{ __('Post a Challenge') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</div>
@endsection
