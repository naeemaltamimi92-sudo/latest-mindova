@extends('layouts.app')

@section('title', 'Hire Requests')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hire Requests</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Companies who want to work with you</p>
    </div>

    @if(session('success'))<div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-lg text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>@endif
    @if(session('info'))<div class="mb-4 p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-lg text-sm text-blue-700 dark:text-blue-300">{{ session('info') }}</div>@endif

    @if($hireRequests->isEmpty())
    <div class="text-center py-16 text-gray-400 dark:text-gray-500">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <p class="text-lg font-medium">No hire requests yet</p>
        <p class="text-sm mt-1">Companies will send you requests when they're interested in your profile.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($hireRequests as $req)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                {{ $req->status === 'pending' ? 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300' : '' }}
                                {{ $req->status === 'converted' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : '' }}
                                {{ $req->status === 'declined' ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' : '' }}
                                {{ $req->status === 'withdrawn' ? 'bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400' : '' }}">
                                {{ ucfirst($req->status) }}
                            </span>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $req->typeLabel() }}</span>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $req->position_title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            from {{ $req->company->company?->company_name ?? $req->company->name ?? 'Company' }}
                            · {{ $req->created_at->diffForHumans() }}
                        </p>
                        @if($req->salary_range)
                        <p class="text-sm font-semibold text-violet-600 dark:text-violet-400 mt-1">{{ $req->salary_range }}</p>
                        @endif
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 leading-relaxed">{{ Str::limit($req->message, 200) }}</p>
                    </div>
                    @if($req->status === 'pending')
                    <div class="flex gap-2 shrink-0">
                        <form method="POST" action="{{ route('hire-requests.accept', $req) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-colors">
                                Accept
                            </button>
                        </form>
                        <form method="POST" action="{{ route('hire-requests.decline', $req) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition-colors">
                                Decline
                            </button>
                        </form>
                    </div>
                    @elseif($req->status === 'converted' && $req->hiringRecord)
                    <div class="shrink-0 text-center p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg">
                        <div class="text-xs font-mono text-emerald-600 dark:text-emerald-400">{{ $req->hiringRecord->hiring_verification_id }}</div>
                        <div class="text-[10px] text-emerald-500 dark:text-emerald-500 mt-1">Verified Record</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $hireRequests->links() }}</div>
    @endif
</div>
@endsection
