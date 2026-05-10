<x-layouts.auth :title="__('Log in')">
<div class="space-y-6">
    <x-auth-header :title="__('Selamat datang kembali')" :description="__('Masukkan email dan password Anda untuk melanjutkan')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" :action="route('login')" class="space-y-6">
        <x-input
            type="email"
            :label="__('Email')"
            name="email"
            required
            autofocus
            autocomplete="email"
        />

        <div class="relative">
            <x-input
                type="password"
                :label="__('Password')"
                name="password"
                required
                autocomplete="current-password"
            />

            @if (Route::has('password.request'))
                <x-link class="absolute right-0 top-0 text-sm" :href="route('password.request')">
                    {{ __('Lupa password?') }}
                </x-link>
            @endif
        </div>

        <x-checkbox name="remember" :label="__('Ingat saya')" />

        <x-button class="w-full btn-primary">{{ __('Login') }}</x-button>
    </x-form>

    <div class="my-6 flex items-center justify-between">
        <span class="w-1/5 border-b border-gray-700 lg:w-1/4"></span>
        <span class="text-center text-xs text-gray-500 uppercase tracking-widest">atau masuk dengan</span>
        <span class="w-1/5 border-b border-gray-700 lg:w-1/4"></span>
    </div>

    <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full gap-3 px-4 py-3 text-sm font-bold text-gray-200 transition-colors duration-300 border border-gray-700 rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 focus:ring-offset-gray-900 mb-6">
        <i class="fab fa-google text-red-500"></i>
        <span>Google</span>
    </a>

    @if (Route::has('register'))
      <p class="text-center text-sm text-gray-400">
          <span>{{ __('Belum punya akun?') }}</span>
          <x-link :href="route('register')">Daftar sekarang</x-link>
      </p>
    @endif
</div>
</x-layouts.auth>
