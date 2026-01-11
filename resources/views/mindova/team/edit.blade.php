@extends('mindova.layouts.app')

@section('title', __('Edit Team Member'))

@section('content')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up { animation: slideUp 0.5s ease-out forwards; }
    .animate-slide-up-delay-1 { animation: slideUp 0.5s ease-out 0.1s forwards; opacity: 0; }
    .animate-slide-up-delay-2 { animation: slideUp 0.5s ease-out 0.2s forwards; opacity: 0; }
    .animate-slide-up-delay-3 { animation: slideUp 0.5s ease-out 0.3s forwards; opacity: 0; }
</style>

<div class="max-w-6xl mx-auto" x-data="editMemberForm()">
    <!-- Premium Header with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-3xl p-8 mb-8 animate-slide-up">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('mindova.team.index') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div class="w-20 h-20 bg-gradient-to-br {{ $member->isOwner() ? 'from-amber-400 to-orange-500' : ($member->isAdmin() ? 'from-purple-500 to-indigo-500' : 'from-blue-500 to-cyan-500') }} rounded-2xl flex items-center justify-center font-bold text-white text-2xl shadow-xl ring-4 ring-white/20">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $member->name }}</h1>
                    <p class="text-white/60">{{ $member->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $member->isOwner() ? 'bg-amber-400 text-amber-900' : ($member->isAdmin() ? 'bg-purple-400 text-purple-900' : 'bg-blue-400 text-blue-900') }}">
                            {{ $member->role->name }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $member->is_active ? 'bg-emerald-400 text-emerald-900' : 'bg-red-400 text-red-900' }}">
                            {{ $member->is_active ? __('Active') : __('Inactive') }}
                        </span>
                        @if(!$member->password_changed)
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-400 text-yellow-900">
                            {{ __('Pending') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-3">
                @if($member->id !== $currentMember->id)
                    @if($member->is_active && ($currentMember->isOwner() || $currentMember->role->level > $member->role->level))
                    <form action="{{ route('mindova.team.deactivate', $member) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('{{ __('Are you sure you want to deactivate this member?') }}')"
                            class="px-4 py-2.5 bg-red-500/20 text-red-300 hover:bg-red-500/30 font-medium rounded-xl transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            {{ __('Deactivate') }}
                        </button>
                    </form>
                    @elseif(!$member->is_active)
                    <form action="{{ route('mindova.team.activate', $member) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30 font-medium rounded-xl transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('Activate') }}
                        </button>
                    </form>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Edit Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Edit Form Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-1">
                <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('Edit Member Details') }}
                    </h3>
                </div>

                <form action="{{ route('mindova.team.update', $member) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Full Name') }} *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" required
                                class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-lg @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                placeholder="{{ __('Enter full name') }}">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Email Field (Disabled) -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Email Address') }}</label>
                            <div class="relative">
                                <input type="email" id="email" value="{{ $member->email }}" disabled
                                    class="w-full px-4 py-3.5 pl-11 border border-slate-200 rounded-xl bg-slate-50 text-slate-500 cursor-not-allowed">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('Email address cannot be changed for security reasons.') }}
                            </p>
                        </div>

                        <!-- Role Selection -->
                        @if($currentMember->isOwner() || $currentMember->role->level > $member->role->level)
                        <div>
                            <label for="role_id" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Role') }} *</label>
                            <div class="space-y-3">
                                @foreach($roles as $role)
                                @if($currentMember->isOwner() || $role->level < $currentMember->role->level || $role->id === $member->role_id)
                                <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all
                                    {{ old('role_id', $member->role_id) == $role->id ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200 hover:border-slate-300' }}"
                                    :class="selectedRole == {{ $role->id }} ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="role_id" value="{{ $role->id }}"
                                        {{ old('role_id', $member->role_id) == $role->id ? 'checked' : '' }}
                                        {{ $member->isOwner() && $role->slug !== 'owner' && !$currentMember->isOwner() ? 'disabled' : '' }}
                                        x-model="selectedRole"
                                        class="sr-only">
                                    <div class="flex-1 flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                            {{ $role->slug === 'owner' ? 'bg-gradient-to-br from-amber-400 to-orange-500' :
                                               ($role->slug === 'admin' ? 'bg-gradient-to-br from-purple-500 to-indigo-500' :
                                               ($role->slug === 'accounting' ? 'bg-gradient-to-br from-emerald-400 to-teal-500' :
                                               ($role->slug === 'support' ? 'bg-gradient-to-br from-blue-400 to-cyan-500' : 'bg-gradient-to-br from-pink-400 to-rose-500'))) }}">
                                            @if($role->slug === 'owner')
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                            </svg>
                                            @elseif($role->slug === 'admin')
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            @elseif($role->slug === 'accounting')
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @elseif($role->slug === 'support')
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            @else
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold text-slate-800">{{ $role->name }}</div>
                                            <div class="text-sm text-slate-500 mt-0.5">{{ $role->description }}</div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                                            :class="selectedRole == {{ $role->id }} ? 'border-blue-500 bg-blue-500' : 'border-slate-300'">
                                            <svg x-show="selectedRole == {{ $role->id }}" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                                @endif
                                @endforeach
                            </div>
                            @error('role_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($member->isOwner())
                            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                <p class="text-sm text-amber-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('Warning: Changing the Owner role will transfer ownership to another member. This action cannot be undone.') }}
                                </p>
                            </div>
                            @endif
                        </div>
                        @else
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Role') }}</label>
                            <div class="p-4 border-2 border-slate-200 rounded-xl bg-slate-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br {{ $member->isOwner() ? 'from-amber-400 to-orange-500' : ($member->isAdmin() ? 'from-purple-500 to-indigo-500' : 'from-blue-400 to-cyan-500') }}">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-600">{{ $member->role->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $member->role->description }}</div>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" name="role_id" value="{{ $member->role_id }}">
                            <p class="mt-2 text-xs text-slate-500">{{ __('You do not have permission to change this member\'s role.') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 mt-6 border-t border-slate-200">
                        <a href="{{ route('mindova.team.index') }}" class="px-5 py-2.5 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Danger Zone -->
            @if($member->id !== $currentMember->id && ($currentMember->isOwner() || $currentMember->role->level > $member->role->level))
            <div class="bg-red-50 rounded-2xl border border-red-200 overflow-hidden animate-slide-up-delay-2">
                <div class="px-6 py-5 border-b border-red-200">
                    <h3 class="font-bold text-red-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Danger Zone') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-red-800">{{ __('Remove Member') }}</h4>
                            <p class="text-sm text-red-600 mt-1">{{ __('Permanently remove this member from the team. This action cannot be undone.') }}</p>
                        </div>
                        <form action="{{ route('mindova.team.destroy', $member) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to permanently remove this member? This action cannot be undone.') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ __('Remove') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Member Info Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-1">
                <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Member Information') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">{{ __('Invited On') }}</span>
                        <span class="font-medium text-slate-800">{{ $member->invited_at ? $member->invited_at->format('M j, Y') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">{{ __('Invited By') }}</span>
                        <span class="font-medium text-slate-800">{{ $member->invitedByMember?->name ?? __('System') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">{{ __('Activated On') }}</span>
                        <span class="font-medium {{ $member->activated_at ? 'text-slate-800' : 'text-amber-600' }}">
                            {{ $member->activated_at ? $member->activated_at->format('M j, Y') : __('Not yet') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-slate-500 text-sm">{{ __('Last Login') }}</span>
                        <span class="font-medium {{ $member->last_login_at ? 'text-slate-800' : 'text-slate-400' }}">
                            {{ $member->last_login_at ? $member->last_login_at->diffForHumans() : __('Never') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Permissions Preview -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-2">
                <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        {{ __('Role Permissions') }}
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $permissions = $member->role->permissions ?? [];
                        $permissionGroups = [
                            'team' => __('Team Management'),
                            'users' => __('User Management'),
                            'companies' => __('Company Management'),
                            'billing' => __('Billing'),
                            'support' => __('Support'),
                            'feedback' => __('Feedback'),
                            'audit' => __('Audit Logs'),
                        ];
                    @endphp
                    <div class="space-y-2">
                        @foreach($permissionGroups as $key => $label)
                        @php
                            $hasPermission = collect($permissions)->contains(fn($p) => str_starts_with($p, $key . '.'));
                        @endphp
                        <div class="flex items-center gap-3 py-2">
                            @if($hasPermission)
                            <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-slate-700">{{ $label }}</span>
                            @else
                            <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-slate-400">{{ $label }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            @if(!$member->password_changed && ($currentMember->isOwner() || $currentMember->role->level > $member->role->level))
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-3">
                <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ __('Quick Actions') }}
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <form action="{{ route('mindova.team.resend-invitation', $member) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-blue-50 text-blue-700 font-medium rounded-xl hover:bg-blue-100 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Resend Invitation') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function editMemberForm() {
    return {
        selectedRole: {{ old('role_id', $member->role_id) }}
    }
}
</script>
@endsection
