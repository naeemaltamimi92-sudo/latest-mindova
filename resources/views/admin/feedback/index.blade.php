@extends('layouts.admin')

@section('title', __('Feedback & Ideas'))
@section('page-title', __('Feedback & Ideas Board'))
@section('page-subtitle', __('Read, categorize, and act on user-submitted feedback'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6 bg-white p-4 rounded-2xl border border-slate-200/70 shadow-sm">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search title or description...') }}"
               class="flex-1 min-w-[200px] text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">

        <select name="type" class="text-sm border border-slate-200 rounded-xl px-3 py-2">
            <option value="">{{ __('All types') }}</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>

        <select name="status" class="text-sm border border-slate-200 rounded-xl px-3 py-2">
            <option value="">{{ __('All statuses') }}</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>

        <select name="sort_by" class="text-sm border border-slate-200 rounded-xl px-3 py-2">
            <option value="votes_count" {{ request('sort_by', 'votes_count') === 'votes_count' ? 'selected' : '' }}>{{ __('Most Voted') }}</option>
            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>{{ __('Newest') }}</option>
        </select>

        <x-ui.button type="submit" variant="primary" size="sm">{{ __('Filter') }}</x-ui.button>
        <a href="{{ route('admin.feedback.index') }}" class="text-sm text-slate-500 hover:text-primary-600">{{ __('Reset') }}</a>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <th class="px-4 py-3">{{ __('Votes') }}</th>
                    <th class="px-4 py-3">{{ __('Title') }}</th>
                    <th class="px-4 py-3">{{ __('Type') }}</th>
                    <th class="px-4 py-3">{{ __('Submitted By') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                    <th class="px-4 py-3">{{ __('Duplicate Of') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-bold text-slate-900 tabular-nums">{{ $item->votes_count }}</td>
                    <td class="px-4 py-3 max-w-xs">
                        <a href="{{ route('feedback.show', $item) }}" target="_blank" class="font-medium text-slate-900 hover:text-primary-600 line-clamp-1">{{ $item->title }}</a>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-medium">{{ ucfirst(str_replace('_', ' ', $item->type)) }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $item->user->name ?? __('Unknown') }}</td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.feedback.updateStatus', $item) }}" method="POST">
                            @csrf
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs font-medium border border-slate-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.feedback.markDuplicate', $item) }}" method="POST" class="flex items-center gap-1.5">
                            @csrf
                            <input type="number" name="duplicate_of_id" value="{{ $item->duplicate_of_id }}" placeholder="{{ __('ID') }}"
                                   class="w-16 text-xs border border-slate-200 rounded-lg px-2 py-1.5">
                            <button type="submit" class="text-xs font-medium text-primary-600 hover:text-primary-700">{{ __('Set') }}</button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('feedback.show', $item) }}" target="_blank" class="text-xs font-medium text-slate-500 hover:text-primary-600">{{ __('View') }} &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-500">{{ __('No feedback submissions match these filters.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection
