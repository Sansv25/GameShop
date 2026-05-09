<x-layouts.auth :title="__('Verifikasi email')">
<div class="mt-4 space-y-6">
    <x-text class="text-center">
        {{ __('Silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.') }}
    </x-text>

    @if (session('status') == 'verification-link-sent')
        <x-text class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
        </x-text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <x-form method="post" action="{{ route('verification.store') }}">
            <x-button class="w-full btn-primary">
                {{ __('Kirim ulang email verifikasi') }}
            </x-button>
        </x-form>
        <x-form method="post" action="{{ route('logout') }}">
            <x-button variant="link">{{ __('Logout') }}</x-button>
        </x-form>
    </div>
</div>
</x-layouts.auth>
