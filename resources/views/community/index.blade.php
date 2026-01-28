@extends('layouts.app')

@section('title', __('Community Challenges'))

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 pb-12 pt-6" id="challenges">
    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Community Challenges') }}</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ __('Join the discussion and help solve real-world problems') }}</p>
        </div>
        @if(auth()->check() && auth()->user()->isVolunteer())
        <x-ui.button type="button" onclick="openChallengeModal()" size="sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Submit a Challenge') }}
        </x-ui.button>
        @endif
    </div>

    <!-- Field Filter Banner -->
    @if(isset($userField) && $userField)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-0.5">
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Filtered by Your Expertise') }}</h3>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-white text-blue-700 rounded-lg text-xs font-semibold border border-blue-200">
                        {{ $userField }}
                    </span>
                </div>
                <p class="text-xs text-gray-600">{{ __('Showing level 1-2 challenges in your field. These are perfect for learning and community contribution.') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Status Filter Tabs --}}
    <div class="mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-1.5">
            <div class="flex flex-wrap gap-1">
                {{-- Active Tab --}}
                <a href="{{ route('community.index', ['filter' => 'active']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg font-semibold text-sm
                          {{ ($filter ?? 'active') === 'active'
                              ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                              : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-transparent' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>{{ __('Active') }}</span>
                </a>

                {{-- Completed Tab --}}
                <a href="{{ route('community.index', ['filter' => 'completed']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg font-semibold text-sm
                          {{ ($filter ?? 'active') === 'completed'
                              ? 'bg-blue-50 text-blue-700 border border-blue-200'
                              : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-transparent' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ __('Completed') }}</span>
                </a>

                {{-- All Tab --}}
                <a href="{{ route('community.index', ['filter' => 'all']) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg font-semibold text-sm
                          {{ ($filter ?? 'active') === 'all'
                              ? 'bg-violet-50 text-violet-700 border border-violet-200'
                              : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-transparent' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>{{ __('All') }}</span>
                </a>
            </div>
        </div>
    </div>

    @if($challenges->count() > 0)
    <!-- Challenges Grid -->
    <div class="space-y-4">
        @foreach($challenges as $index => $challenge)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="p-5">
                <div class="flex flex-col xl:flex-row xl:items-start gap-4">
                    <!-- Main Content -->
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex flex-wrap items-start gap-2 mb-3">
                            <a href="{{ route('community.challenge', $challenge) }}" class="text-lg font-bold text-gray-900 hover:text-primary-600 leading-tight">
                                {{ $challenge->title }}
                            </a>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-semibold border
                                    {{ $challenge->score <= 2 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-primary-50 text-primary-700 border-primary-200' }}">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ __('Score:') }} {{ $challenge->score }}/10
                                </span>
                                @if($challenge->field)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg text-xs font-semibold">
                                    {{ $challenge->field }}
                                </span>
                                @endif
                                @if($challenge->status === 'completed' || $challenge->status === 'closed')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $challenge->hasCorrectAnswer() ? __('Solved') : __('Completed') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Author Info -->
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <div class="flex items-center gap-2">
                                @if($challenge->isVolunteerSubmitted())
                                <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center text-violet-700 font-bold text-xs">
                                    {{ strtoupper(substr($challenge->volunteer->user->name ?? 'V', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $challenge->volunteer->user->name ?? 'Volunteer' }}</p>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-violet-50 text-violet-700 rounded text-[10px] font-bold border border-violet-200">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('Contributor') }}
                                    </span>
                                </div>
                                @else
                                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-primary-700 font-bold text-xs">
                                    {{ strtoupper(substr($challenge->company->company_name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $challenge->company->company_name ?? 'Company' }}</p>
                                    <p class="text-[10px] text-gray-500">{{ __('Organization') }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $challenge->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-2">
                            {{ $challenge->original_description }}
                        </p>

                        <!-- Stats Row -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span>{{ $challenge->ideas_count ?? 0 }} {{ trans_choice('{0} ideas|{1} idea|[2,*] ideas', $challenge->ideas_count ?? 0) }}</span>
                            </span>

                            @php $qualityCount = $challenge->ideas()->where('ai_quality_score', '>=', 70)->count(); @endphp
                            @if($qualityCount > 0)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium border border-emerald-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>{{ __(':count quality insights', ['count' => $qualityCount]) }}</span>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Column -->
                    <div class="xl:w-44 flex flex-col gap-2">
                        <a href="{{ route('community.challenge', $challenge) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary-500 text-white text-sm font-semibold rounded-lg hover:bg-primary-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            {{ __('Join Discussion') }}
                        </a>

                        <!-- Preview Stats -->
                        <div class="bg-gray-50 rounded-lg p-3 text-center border border-gray-200">
                            <p class="text-xs text-gray-500 font-medium mb-1.5">{{ __('Engagement') }}</p>
                            <div class="flex justify-center gap-0.5">
                                @for($i = 0; $i < 5; $i++)                                <div class="w-1.5 h-6 rounded-full {{ ($challenge->ideas_count ?? 0) > ($i * 2) ? 'bg-primary-500' : 'bg-gray-200' }}"></div>
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
    <div class="mt-8 flex justify-center">
        {{ $challenges->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-gray-900 mb-1">
            @if(($filter ?? 'active') === 'completed')
                {{ __('No Completed Challenges Yet') }}
            @elseif(($filter ?? 'active') === 'all')
                {{ __('No Challenges Found') }}
            @else
                {{ __('No Active Challenges Yet') }}
            @endif
        </h2>
        <p class="text-gray-500 text-sm mb-6 max-w-md mx-auto">
            @if(($filter ?? 'active') === 'completed')
                {{ __('Challenges that have been resolved will appear here.') }}
            @elseif(isset($userField) && $userField)
                {{ __('No challenges found in your field (:field). Check back later or submit your own!', ['field' => $userField]) }}
            @else
                {{ __('Check back later for challenges that need community input and discussion.') }}
            @endif
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            @if(auth()->check() && auth()->user()->isVolunteer())
            <x-ui.button type="button" onclick="openChallengeModal()" size="sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Submit a Challenge') }}
            </x-ui.button>
            @endif
            <x-ui.button as="a" href="{{ route('dashboard') }}" variant="outline" size="sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Back to Dashboard') }}
            </x-ui.button>
        </div>
    </div>
    @endif
</div>

<!-- How It Works Section -->
<section class="py-12 bg-gray-50 mt-12">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-1.5 bg-violet-50 text-violet-700 font-semibold px-3 py-1.5 rounded-lg text-xs mb-3 border border-violet-200">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                {{ __('How It Works') }}
            </span>
            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('About Community Challenges') }}</h2>
            <p class="text-gray-600 text-sm max-w-xl mx-auto">{{ __('Learn how to make the most of the community discussion platform') }}</p>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <!-- Step 1 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-primary-700">1</span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('Educational Focus') }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('Level 1-2 challenges are perfect for learning and skill development. They\'re designed to be accessible while still providing meaningful problem-solving opportunities.') }}
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-primary-700">2</span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('High-Quality Comments') }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('Comments scored 7+ by AI boost your reputation and get noticed by challenge owners. Focus on providing thoughtful, actionable insights.') }}
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-primary-700">3</span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('Field Matching') }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('Challenges are matched to your field of expertise, ensuring you can provide valuable, relevant contributions to discussions.') }}
                </p>
            </div>
        </div>
    </div>
</section>

@if(auth()->check() && auth()->user()->isVolunteer())
<!-- Submit Challenge Modal -->
<div id="challengeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 z-40 bg-gray-900/70" onclick="closeChallengeModal()"></div>

    <!-- Modal container -->
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <!-- Modal panel -->
        <div class="relative bg-white rounded-xl border border-gray-200 shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <form id="challengeForm" enctype="multipart/form-data">
                @csrf
                <!-- Header -->
                <div class="bg-primary-500 px-6 py-4 border-b border-primary-400/30">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-white" id="modal-title">
                                {{ __('Submit a Community Challenge') }}
                            </h3>
                            <p class="text-white/80 text-xs mt-0.5">{{ __('Share a problem for the community to solve together') }}</p>
                        </div>
                        <button type="button" onclick="closeChallengeModal()" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="challenge_title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            {{ __('Challenge Title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="challenge_title" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm"
                            placeholder="{{ __('Enter a clear, descriptive title...') }}">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="challenge_description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            {{ __('Challenge Description') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="challenge_description" rows="5" required minlength="50"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm"
                            placeholder="{{ __('Describe your challenge in detail... (minimum 50 characters)') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Minimum 50 characters required') }}</p>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label for="challenge_attachments" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            {{ __('Attachments') }} <span class="text-gray-400 font-normal">({{ __('optional') }})</span>
                        </label>
                        <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-300 cursor-pointer bg-gray-50" onclick="document.getElementById('challenge_attachments').click()">
                            <input type="file" name="attachments[]" id="challenge_attachments" multiple
                                class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm font-medium">{{ __('Click to upload') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ __('PDF, DOC, Images up to 10MB') }}</p>
                        </div>
                        <div id="fileList" class="mt-3 space-y-2"></div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-900 text-sm mb-1">{{ __('How scoring works:') }}</p>
                                <ul class="space-y-1 text-xs text-blue-800">
                                    <li>• {{ __('Your challenge will be analyzed by AI and scored 1-10') }}</li>
                                    <li>• {{ __('Score 1-2: Posted for community discussion') }}</li>
                                    <li>• {{ __('Score 3-10: Full challenge execution with tasks') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-2 border-t border-gray-100">
                    <x-ui.button type="button" onclick="closeChallengeModal()" variant="ghost" size="sm">
                        {{ __('Cancel') }}
                    </x-ui.button>
                    <x-ui.button as="submit" id="submitBtn" size="sm">
                        <span id="submitText">{{ __('Submit Challenge') }}</span>
                        <span id="submitLoading" class="hidden">
                            <svg class="animate-spin h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24">
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

document.querySelectorAll('#openChallengeModalBtn, #openChallengeModalBtnEmpty').forEach(function(btn) {
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openChallengeModal();
        });
    }
});

var attachmentsInput = document.getElementById('challenge_attachments');
if (attachmentsInput) {
    attachmentsInput.addEventListener('change', function(e) {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';

        Array.from(e.target.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2';
            fileItem.innerHTML = `
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeChallengeModal();
    }
});
</script>
@endif
@endsection
