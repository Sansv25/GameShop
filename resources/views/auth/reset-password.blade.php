<x-layouts.auth :title="__('Reset password')">
<div class="space-y-6">
    <x-auth-header :title="__('Reset password')" :description="__('Masukkan password baru Anda di bawah ini')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('password.store', $request->token) }}" class="space-y-6">
        <!-- Email Address -->
        <x-input
            type="email"
            :label="__('Email')"
            name="email"
            :value="$request->email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <x-input
            type="password"
            :label="__('Password baru')"
            name="password"
            required
            autocomplete="new-password"
        />

        <!-- Confirm Password -->
        <x-input
            type="password"
            :label="__('Konfirmasi password')"
            name="password_confirmation"
            required
            autocomplete="new-password"
        />

        <x-button class="w-full btn-primary">{{ __('Reset password') }}</x-button>
    </x-form>
</div>
</x-layouts.auth>
