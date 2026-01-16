@extends('layouts.app')

@section('title', __('Submit Idea'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .float { animation: float 3s ease-in-out infinite; }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .pulse-glow { animation: pulseGlow 2s ease-in-out infinite; }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
        50% { box-shadow: 0 0 40px rgba(139, 92, 246, 0.6); }
    }
    .char-counter {
        transition: all 0.3s ease;
    }
    .char-counter.warning { color: var(--color-warning); }
    .char-counter.valid { color: var(--color-success); }
    .char-counter.error { color: var(--color-danger); }
    .textarea-glow:focus {
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-violet-50/30">
    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700 py-12 mb-12 rounded-3xl max-w-5xl mx-auto shadow-2xl">
        <!-- Animated Background Effects -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-violet-400/20 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-purple-400/20 via-transparent to-transparent"></div>
            <div class="floating-element absolute top-10 -left-20 w-80 h-80 bg-violet-400/20 rounded-full blur-3xl float"></div>
            <div class="floating-element absolute bottom-10 right-10 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl float" style="animation-delay: 2s;"></div>
        </div>

        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="idea-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#idea-grid)"/>
            </svg>
        </div>

        <div class="relative max-w-4xl mx-auto px-6 sm:px-8 text-center slide-up">
            <!-- Status Badge -->
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full px-5 py-2.5 mb-6 shadow-lg">
                <div class="relative">
                    <div class="w-2.5 h-2.5 bg-amber-400 rounded-full animate-pulse"></div>
                    <div class="absolute inset-0 w-2.5 h-2.5 bg-amber-400 rounded-full animate-ping"></div>
                </div>
                <span class="text-sm font-semibold text-white/90">{{ __('Community Discussion') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 leading-tight tracking-tight">
                {{ __('Share Your') }}
                <span class="bg-gradient-to-r from-amber-300 via-yellow-200 to-orange-300 bg-clip-text text-transparent">{{ __('Idea') }}</span>
            </h1>
            <p class="text-lg text-white/80 font-medium leading-relaxed max-w-2xl mx-auto">
                {{ __('Contribute your creative solution to help solve this community challenge') }}
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <!-- Breadcrumb -->
        <div class="mb-8 slide-up" style="animation-delay: 0.1s">
            <a href="{{ route('challenges.show', $challenge->id) }}" class="inline-flex items-center gap-2 text-violet-600 hover:text-violet-700 font-semibold transition-colors group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('Back to Challenge') }}
            </a>
        </div>

        <!-- Challenge Context Card -->
        <div class="bg-gradient-to-r from-violet-50 via-purple-50 to-indigo-50 border-2 border-violet-200 rounded-3xl p-8 mb-8 slide-up" style="animation-delay: 0.15s">
            <div class="flex items-start gap-5">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg pulse-glow">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-violet-100 text-violet-700 rounded-lg text-xs font-bold border border-violet-200">
                            {{ __('Challenge') }}
                        </span>
                        @if($challenge->score)
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold border border-amber-200">
                            {{ __('Score') }}: {{ $challenge->score }}/10
                        </span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 mb-3">{{ $challenge->title }}</h2>
                    <p class="text-slate-700 leading-relaxed">{{ Str::limit($challenge->refined_brief ?? $challenge->original_description, 250) }}</p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.2s">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ __('Submit Your Idea') }}</h2>
                        <p class="text-violet-200 text-sm">{{ __('Your idea will be evaluated by AI and the community') }}</p>
                    </div>
                </div>
            </div>

            <form action="/api/challenges/{{ $challenge->id }}/ideas" method="POST" x-data="ideaForm()" class="p-8 md:p-12 space-y-10">
                @csrf

                <!-- Idea Title -->
                <div class="space-y-4">
                    <label class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">1</span>
                        <div>
                            <span class="block text-lg font-black text-slate-900">{{ __('Idea Title') }}</span>
                            <span class="text-sm text-slate-500">{{ __('Give your idea a clear, catchy name') }}</span>
                        </div>
                    </label>
                    <input type="text" name="title" required
                           x-model="title"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all text-lg text-slate-900 placeholder-slate-400 shadow-sm"
                           placeholder="{{ __('e.g., Smart Lighting with Motion Sensors') }}"
                           maxlength="255">
                    <p class="text-sm text-slate-500 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Maximum 255 characters') }}
                    </p>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                </div>

                <!-- Idea Description -->
                <div class="space-y-4">
                    <label class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">2</span>
                        <div>
                            <span class="block text-lg font-black text-slate-900">{{ __('Description') }}</span>
                            <span class="text-sm text-slate-500">{{ __('Explain your idea in detail - the more context, the better') }}</span>
                        </div>
                    </label>
                    <div class="relative">
                        <textarea name="description" required rows="12"
                                  x-model="description"
                                  @input="updateCharCount"
                                  class="textarea-glow w-full px-6 py-5 border-2 border-violet-200 bg-gradient-to-br from-white to-violet-50/30 rounded-2xl focus:border-violet-500 transition-all text-slate-900 placeholder-slate-400 resize-none shadow-sm leading-relaxed"
                                  placeholder="{{ __('Describe your idea in detail...

Include:
- What is your proposed solution?
- How would it solve the challenge?
- What makes it innovative or effective?
- What are the potential benefits?') }}"
                                  minlength="100" maxlength="2000"></textarea>
                        <div class="absolute bottom-4 right-4 text-sm font-bold bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl border border-slate-200">
                            <span class="char-counter" :class="{ 'warning': charCount >= 50 && charCount < 100, 'valid': charCount >= 100, 'error': charCount < 50 }" x-text="charCount + ' / 2000'"></span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Minimum 100 characters, maximum 2000 characters') }}
                        </p>
                        <p class="text-sm font-semibold text-red-600" x-show="charCount < 100 && charCount > 0" x-transition>
                            {{ __('Need') }} <span x-text="100 - charCount"></span> {{ __('more characters') }}
                        </p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                </div>

                <!-- How It Works Info Card -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-8">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-amber-900 mb-4">{{ __('What happens after you submit?') }}</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="flex items-start gap-3 bg-white/60 rounded-xl p-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-sm">{{ __('AI Evaluation') }}</h4>
                                        <p class="text-xs text-amber-700">{{ __('Analyzed for feasibility, innovation & impact') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/60 rounded-xl p-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-sm">{{ __('AI Score') }}</h4>
                                        <p class="text-xs text-amber-700">{{ __('Receive a score from 0-100') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/60 rounded-xl p-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-sm">{{ __('Community Votes') }}</h4>
                                        <p class="text-xs text-amber-700">{{ __('Members can upvote your idea') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/60 rounded-xl p-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-sm">{{ __('Final Score') }}</h4>
                                        <p class="text-xs text-amber-700">{{ __('40% AI + 60% community votes') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-slate-200">
                    <a href="{{ route('challenges.show', $challenge->id) }}" class="group inline-flex items-center justify-center bg-white border-2 border-slate-200 text-slate-700 font-bold px-8 py-4 rounded-xl transition-all hover:border-slate-300 hover:bg-slate-50 shadow-sm">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                            :disabled="charCount < 100"
                            :class="charCount < 100 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-105 hover:shadow-2xl'"
                            class="group inline-flex items-center justify-center bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold px-12 py-4 rounded-xl transition-all transform shadow-xl">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        {{ __('Submit Idea') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ideaForm() {
    return {
        title: '',
        description: '',
        charCount: 0,
        updateCharCount() {
            this.charCount = this.description.length;
        }
    }
}
</script>
@endsection
