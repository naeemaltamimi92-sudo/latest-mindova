@extends('layouts.app')

@section('title', $item->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('feedback.index') }}" class="text-sm text-slate-500 hover:text-primary-600 flex items-center gap-1 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        {{ __('Back to board') }}
    </a>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6 mb-6">
        <div class="flex items-start gap-4">
            <form action="{{ route('feedback.vote', $item) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" @unless(auth()->check()) disabled @endunless
                    class="w-16 h-16 rounded-xl border flex flex-col items-center justify-center transition-colors {{ $item->hasVoteFrom(auth()->user()) ? 'bg-primary-50 border-primary-300 text-primary-700' : 'border-slate-200 text-slate-500 hover:border-primary-200 hover:text-primary-600' }} disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l6 6a1 1 0 01-1.414 1.414L11 6.414V16a1 1 0 11-2 0V6.414L4.707 10.707a1 1 0 01-1.414-1.414l6-6A1 1 0 0110 3z" clip-rule="evenodd"/></svg>
                    <span class="text-base font-bold">{{ $item->votes_count }}</span>
                </button>
            </form>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
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
                    @if($item->category)
                    <span class="text-xs px-2 py-0.5 rounded-md bg-violet-50 text-violet-600 font-medium">{{ $item->category }}</span>
                    @endif
                    @if($item->duplicateOf)
                    <a href="{{ route('feedback.show', $item->duplicateOf) }}" class="text-xs px-2 py-0.5 rounded-md bg-slate-200 text-slate-700 font-medium">
                        {{ __('Duplicate of') }} #{{ $item->duplicateOf->id }}
                    </a>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-slate-900 mb-2">{{ $item->title }}</h1>
                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $item->description }}</p>
                <div class="flex items-center gap-3 mt-4 text-xs text-slate-400">
                    <span>{{ __('by') }} {{ $item->user->name ?? __('Unknown') }}</span>
                    <span>&bull;</span>
                    <span>{{ $item->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        @auth
            @if(auth()->id() === $item->user_id || auth()->user()->isAdmin())
            <div class="flex items-center gap-3 mt-5 pt-5 border-t border-slate-100">
                <a href="{{ route('feedback.edit', $item) }}" class="text-sm font-medium text-slate-500 hover:text-primary-600">{{ __('Edit') }}</a>
                <form action="{{ route('feedback.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('Delete this feedback item?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700">{{ __('Delete') }}</button>
                </form>
            </div>
            @endif
        @endauth
    </div>

    {{-- Comments --}}
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6">
        <h2 class="text-sm font-bold text-slate-900 mb-4">{{ __('Discussion') }} ({{ $item->comments->count() }})</h2>

        @auth
        <form action="{{ route('feedback.comments.store', $item) }}" method="POST" class="mb-6">
            @csrf
            <textarea name="body" rows="3" minlength="2" maxlength="2000" required
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 resize-none"
                      placeholder="{{ __('Add a comment...') }}"></textarea>
            <div class="flex justify-end mt-2">
                <x-ui.button type="submit" variant="primary" size="sm">{{ __('Post Comment') }}</x-ui.button>
            </div>
        </form>
        @else
        <p class="text-sm text-slate-500 mb-6">
            <a href="{{ route('login') }}" class="text-primary-600 font-medium">{{ __('Sign in') }}</a> {{ __('to join the discussion.') }}
        </p>
        @endauth

        <div class="space-y-4">
            @forelse($item->comments as $comment)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-900">{{ $comment->user->name ?? __('Unknown') }}</span>
                        <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-slate-700 mt-0.5 whitespace-pre-line">{{ $comment->body }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">{{ __('No comments yet.') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
