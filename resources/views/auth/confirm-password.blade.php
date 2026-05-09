<x-layouts.auth :title="__('Konfirmasi password')">
<div class="space-y-6">
    <x-auth-header
        :title="__('Konfirmasi password')"
        :description="__('Ini adalah area aman aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('confirmation.store') }}" class="space-y-6">
        <!-- Password -->
        <x-input
            type="password"
            :label="__('Password')"
            name="password"
            required
            autocomplete="new-password"
        />

        <x-button class="w-full btn-primary">{{ __('Konfirmasi') }}</x-button>
    </x-form>
</div>
</x-layouts.auth>
