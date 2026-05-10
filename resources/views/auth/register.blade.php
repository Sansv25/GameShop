<x-layouts.auth :title="__('Sign up')">
<div class="space-y-6">
    <x-auth-header :title="__('Buat akun GameShop')" :description="__('Daftar untuk memulai berbelanja game account premium')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" :action="route('register')" class="space-y-6">
        <!-- Name -->
        <x-input
            type="text"
            :label="__('Nama lengkap')"
            name="name"
            required
            autofocus
            autocomplete="name"
        />

        <!-- Email Address -->
        <x-input
            type="email"
            :label="__('Email')"
            name="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <x-input
            type="password"
            :label="__('Password')"
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

        <x-button class="w-full btn-primary">{{ __('Daftar') }}</x-button>
    </x-form>

    <div class="my-6 flex items-center justify-between">
        <span class="w-1/5 border-b border-gray-700 lg:w-1/4"></span>
        <span class="text-center text-xs text-gray-500 uppercase tracking-widest">atau daftar dengan</span>
        <span class="w-1/5 border-b border-gray-700 lg:w-1/4"></span>
    </div>

    <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full gap-3 px-4 py-3 text-sm font-bold text-gray-200 transition-colors duration-300 border border-gray-700 rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 focus:ring-offset-gray-900 mb-6">
        <i class="fab fa-google text-red-500"></i>
        <span>Google</span>
    </a>

    <div class="space-x-1 text-center text-sm text-gray-400">
        {{ __('Sudah punya akun?') }}
        <x-link :href="route('login')">Login</x-link>
    </div>
</div>
</x-layouts.auth>
