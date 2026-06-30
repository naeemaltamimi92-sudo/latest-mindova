@extends('layouts.app')

@section('title', __('Two-Factor Verification'))

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-md w-full animate-fade-in">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl elevation-lg px-6 py-8">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-aurora rounded-xl flex items-center justify-center glow-primary-sm mx-auto mb-4">
                    <x-icon name="shield" class="w-6 h-6 text-white" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Two-Factor Verification') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Enter the 6-digit code from your authenticator app, or one of your recovery codes.') }}</p>
            </div>

            <div id="two-factor-error" class="hidden mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2"></div>

            <form id="two-factor-form" class="space-y-4">
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Verification Code') }}</label>
                    <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" required autofocus
                           class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 text-center tracking-widest text-lg transition-premium-fast focus:border-primary-500 focus:ring-1 focus:ring-primary-200"
                           placeholder="123456" maxlength="20">
                </div>

                <x-ui.button type="submit" variant="primary" size="sm" fullWidth>
                    {{ __('Verify') }}
                </x-ui.button>
            </form>

            <div class="text-center pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">{{ __('Sign in with a different account') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('two-factor-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('two-factor-error');
    errorBox.classList.add('hidden');

    const code = document.getElementById('code').value.trim();

    try {
        const response = await fetch('{{ route('security.2fa.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ code }),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = '{{ route('dashboard') }}';
            return;
        }

        errorBox.textContent = data.message || '{{ __('Invalid verification code.') }}';
        errorBox.classList.remove('hidden');
    } catch (err) {
        errorBox.textContent = '{{ __('Something went wrong. Please try again.') }}';
        errorBox.classList.remove('hidden');
    }
});
</script>
@endsection
