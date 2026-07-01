@extends('layouts.app')

@section('title', __('Feedback & Ideas Board'))

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('Feedback & Ideas Board') }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ __('Tell us what\'s missing, what\'s broken, or what you\'d love to see next.') }}</p>
        </div>
        @auth
        <x-ui.button as="a" href="{{ route('feedback.create') }}" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Submit Feedback') }}
        </x-ui.button>
        @else
        <x-ui.button as="a" href="{{ route('login') }}" variant="primary">{{ __('Sign in to submit') }}</x-ui.button>
        @endauth
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6 bg-white p-4 rounded-2xl border border-slate-200/70 shadow-sm">
        <select name="type" onchange="this.form.submit()" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">
            <option value="">{{ __('All types') }}</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ $currentType === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">
            <option value="">{{ __('All statuses') }}</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ $currentStatus === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>

        <div class="flex items-center gap-1 ml-auto bg-slate-100 rounded-xl p-1">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
               class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $currentSort !== 'votes' ? 'bg-white shadow-sm text-primary-600' : 'text-slate-500' }}">
                {{ __('Newest') }}
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'votes']) }}"
               class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $currentSort === 'votes' ? 'bg-white shadow-sm text-primary-600' : 'text-slate-500' }}">
                {{ __('Most Voted') }}
            </a>
        </div>
    </form>

    {{-- List --}}
    <div class="space-y-3">
        @forelse($items as $item)
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm hover:shadow-md transition-shadow p-4 flex items-start gap-4">
            <form action="{{ route('feedback.vote', $item) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" @unless(auth()->check()) disabled @endunless
                    class="w-14 h-14 rounded-xl border flex flex-col items-center justify-center transition-colors {{ $item->hasVoteFrom(auth()->user()) ? 'bg-primary-50 border-primary-300 text-primary-700' : 'border-slate-200 text-slate-500 hover:border-primary-200 hover:text-primary-600' }} disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l6 6a1 1 0 01-1.414 1.414L11 6.414V16a1 1 0 11-2 0V6.414L4.707 10.707a1 1 0 01-1.414-1.414l6-6A1 1 0 0110 3z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-bold">{{ $item->votes_count }}</span>
                </button>
            </form>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <a href="{{ route('feedback.show', $item) }}" class="font-semibold text-slate-900 hover:text-primary-600">{{ $item->title }}</a>
                    <span class="text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-medium">{{ ucfirst(str_replace('_', ' ', $item->type)) }}</span>
                    <span @class([
                        'text-xs px-2 py-0.5 rounded-md font-medium',
                        'bg-slate-100 text-slate-600' => $item->status === 'open',
                        'bg-amber-100 text-amber-700' => $item->status === 'under_review',
                        'bg-blue-100 text-blue-700' => $item->status === 'planned',
                        'bg-indigo-100 text-indigo-700' => $item->status === 'in_progress',
                        'bg-emerald-100 text-emerald-700' => $item->status === 'done',
                        'bg-red-100 text-red-700' => $item->status === 'declined',
                    ])>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                </div>
                <p class="text-sm text-slate-500 line-clamp-2">{{ $item->description }}</p>
                <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                    <span>{{ __('by') }} {{ $item->user->name ?? __('Unknown') }}</span>
                    <span>&bull;</span>
                    <span>{{ $item->created_at->diffForHumans() }}</span>
                    <span>&bull;</span>
                    <span>{{ $item->comments_count }} {{ __('comments') }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200/70 p-12 text-center">
            <p class="text-slate-500">{{ __('No feedback yet — be the first to share an idea.') }}</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection
