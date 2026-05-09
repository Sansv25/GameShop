<x-layouts.auth :title="__('Forgot password')">
 <div class="space-y-6">
    <x-auth-header :title="__('Lupa password?')" :description="__('Masukkan email Anda untuk menerima link reset password')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('password.email') }}" class="space-y-6">
        <!-- Email Address -->
        <x-input
            type="email"
            :label="__('Email')"
            name="email"
            required
            autofocus
        />

        <x-button class="w-full btn-primary">{{ __('Kirim link reset password') }}</x-button>
    </x-form>

    <div class="text-center text-sm text-gray-400">
        Atau, kembali ke
        <x-link :href="route('login')">login</x-link>
    </div>
</div>
</x-layouts.auth>
