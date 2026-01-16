@extends('layouts.app')

@section('title', __('Notification Settings'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .float-anim { animation: floatAnim 6s ease-in-out infinite; }
    @keyframes floatAnim {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    .notification-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .notification-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1); }
    .toggle-switch { transition: all 0.3s ease; }
    .toggle-switch:checked { background-color: var(--color-primary-600); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 pb-12">
    <!-- Premium Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-violet-900 py-12 mb-8 mx-4 sm:mx-6 lg:mx-8 rounded-3xl shadow-2xl max-w-5xl lg:mx-auto slide-up">
        <!-- Animated Background Effects -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-400/20 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-violet-400/20 via-transparent to-transparent"></div>
        </div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-element absolute top-10 -left-20 w-60 h-60 bg-indigo-500/20 rounded-full blur-3xl float-anim"></div>
            <div class="floating-element absolute bottom-10 right-10 w-72 h-72 bg-violet-500/20 rounded-full blur-3xl float-anim" style="animation-delay: 2s;"></div>
        </div>

        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="settings-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#settings-grid)"/>
            </svg>
        </div>

        <div class="relative max-w-4xl mx-auto px-6 sm:px-8 text-center">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white mb-3 tracking-tight">
                {{ __('Notification Settings') }}
            </h1>
            <p class="text-white/70 text-lg max-w-xl mx-auto">
                {{ __('Manage how and when you receive notifications from Mindova') }}
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="/api/notifications/preferences" method="POST" x-data="{ saving: false }" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Email Notifications -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden slide-up" style="animation-delay: 0.1s">
                <div class="bg-gradient-to-r from-slate-50 to-indigo-50/30 px-8 py-6 border-b border-slate-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Email Notifications') }}</h2>
                            <p class="text-sm text-slate-600">{{ __("Choose what email notifications you'd like to receive") }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    @if(auth()->user()->isVolunteer())
                    <!-- Volunteer Notifications -->
                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_task_assigned" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Task Assignments') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __("Get notified when you're assigned to a new task") }}</p>
                        </div>
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_task_updated" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Task Updates') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified about changes to your assigned tasks') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_idea_scored" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Idea Feedback') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified when your ideas are scored by AI') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_idea_voted" value="1"
                                   class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Idea Votes') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified when someone votes on your ideas') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_reputation_milestone" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Reputation Milestones') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified when you reach reputation milestones') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->isCompany())
                    <!-- Company Notifications -->
                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_challenge_analyzed" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Challenge Analysis Complete') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified when AI finishes analyzing your challenge') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_task_completed" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Task Completions') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified when tasks in your challenges are completed') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_new_idea" value="1"
                                   checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('New Ideas') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Get notified when someone submits an idea for your challenge') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="email_challenge_progress" value="1"
                                   class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Weekly Progress Reports') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Receive weekly summaries of your challenge progress') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    @endif

                    <!-- Common Notifications -->
                    <div class="border-t border-slate-200 pt-6 mt-6 space-y-4">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-4">{{ __('General Notifications') }}</p>

                        <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                            <div class="flex items-center h-6 pt-0.5">
                                <input type="checkbox" name="email_system_updates" value="1"
                                       checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                            </div>
                            <div class="flex-1">
                                <label class="font-bold text-slate-900">{{ __('System Updates') }}</label>
                                <p class="text-sm text-slate-600 mt-1">{{ __('Important platform updates and announcements') }}</p>
                            </div>
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-indigo-50/20 rounded-2xl border border-slate-200">
                            <div class="flex items-center h-6 pt-0.5">
                                <input type="checkbox" name="email_marketing" value="1"
                                       class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 shadow-sm cursor-pointer">
                            </div>
                            <div class="flex-1">
                                <label class="font-bold text-slate-900">{{ __('Marketing & Tips') }}</label>
                                <p class="text-sm text-slate-600 mt-1">{{ __('Tips, best practices, and promotional content') }}</p>
                            </div>
                            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- In-App Notifications -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden slide-up" style="animation-delay: 0.2s">
                <div class="bg-gradient-to-r from-violet-50 to-purple-50/30 px-8 py-6 border-b border-slate-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('In-App Notifications') }}</h2>
                            <p class="text-sm text-slate-600">{{ __('Configure notifications you see in the platform') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-4">
                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-violet-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="inapp_all" value="1"
                                   checked class="w-5 h-5 text-violet-600 border-slate-300 rounded focus:ring-violet-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Enable all in-app notifications') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Show notifications in the platform interface') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="notification-card flex items-start gap-4 p-5 bg-gradient-to-r from-slate-50 to-violet-50/20 rounded-2xl border border-slate-200">
                        <div class="flex items-center h-6 pt-0.5">
                            <input type="checkbox" name="inapp_sound" value="1"
                                   class="w-5 h-5 text-violet-600 border-slate-300 rounded focus:ring-violet-500 shadow-sm cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label class="font-bold text-slate-900">{{ __('Play sound') }}</label>
                            <p class="text-sm text-slate-600 mt-1">{{ __('Play a sound when you receive a notification') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Frequency -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden slide-up" style="animation-delay: 0.3s">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50/30 px-8 py-6 border-b border-slate-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Email Frequency') }}</h2>
                            <p class="text-sm text-slate-600">{{ __('How often would you like to receive email notifications?') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-4">
                    <label class="notification-card flex items-center gap-4 p-5 bg-gradient-to-r from-slate-50 to-emerald-50/20 rounded-2xl border border-slate-200 cursor-pointer hover:border-emerald-300 transition-colors">
                        <input type="radio" name="email_frequency" value="realtime"
                               checked class="w-5 h-5 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                        <div class="flex-1">
                            <span class="font-bold text-slate-900">{{ __('Real-time') }}</span>
                            <span class="block text-sm text-slate-600 mt-1">{{ __('Get notified immediately as things happen') }}</span>
                        </div>
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </label>

                    <label class="notification-card flex items-center gap-4 p-5 bg-gradient-to-r from-slate-50 to-emerald-50/20 rounded-2xl border border-slate-200 cursor-pointer hover:border-emerald-300 transition-colors">
                        <input type="radio" name="email_frequency" value="daily"
                               class="w-5 h-5 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                        <div class="flex-1">
                            <span class="font-bold text-slate-900">{{ __('Daily digest') }}</span>
                            <span class="block text-sm text-slate-600 mt-1">{{ __('Receive a daily summary of all notifications') }}</span>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </label>

                    <label class="notification-card flex items-center gap-4 p-5 bg-gradient-to-r from-slate-50 to-emerald-50/20 rounded-2xl border border-slate-200 cursor-pointer hover:border-emerald-300 transition-colors">
                        <input type="radio" name="email_frequency" value="weekly"
                               class="w-5 h-5 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                        <div class="flex-1">
                            <span class="font-bold text-slate-900">{{ __('Weekly digest') }}</span>
                            <span class="block text-sm text-slate-600 mt-1">{{ __('Receive a weekly summary of all notifications') }}</span>
                        </div>
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 slide-up" style="animation-delay: 0.4s">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center bg-white border-2 border-slate-300 text-slate-700 font-bold text-lg px-8 py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl hover:border-slate-400">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center justify-center bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-lg px-8 py-4 rounded-xl transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl" :disabled="saving" @click="saving = true">
                    <span x-show="!saving" class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Save Preferences') }}
                    </span>
                    <span x-show="saving" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Saving...') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
