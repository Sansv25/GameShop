<x-layouts.user>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Password Settings</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Ubah password akun Anda</p>
        </div>

        <section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <x-form method="put" action="{{ route('settings.password.update') }}" class="mt-6 space-y-6">
            <x-input
                type="password"
                name="current_password"
                :label="__('Current password')"
                required
                autocomplete="current-password"
            />
            <x-input
                type="password"
                name="password"
                :label="__('New password')"
                required
                autocomplete="new-password"
            />
            <x-input
                type="password"
                name="password_confirmation"
                :label="__('Confirm Password')"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button class="w-full">{{ __('Save') }}</x-button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </x-form>
    </x-settings.layout>
</section>
</x-layouts.user>
