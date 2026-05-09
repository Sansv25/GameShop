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

    @if (Route::has('register'))
      <p class="text-center text-sm text-gray-400">
          <span>{{ __('Belum punya akun?') }}</span>
          <x-link :href="route('register')">Daftar sekarang</x-link>
      </p>
    @endif
</div>
</x-layouts.auth>
