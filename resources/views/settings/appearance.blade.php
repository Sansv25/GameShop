<x-layouts.user>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appearance Settings</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Sesuaikan tampilan aplikasi</p>
        </div>

        <div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <fieldset>
            <legend class="sr-only">Appearance</legend>
            <x-button.group>
                <x-button type="button" variant="secondary" before="phosphor-sun-fill" value="light" onclick="setAppearance(this.value)">{{ __('Light') }}</x-button>
                <x-button type="button" variant="secondary" before="phosphor-moon-fill" value="dark" onclick="setAppearance(this.value)">{{ __('Dark') }}</x-button>
                <x-button type="button" variant="secondary" before="phosphor-monitor-fill" value="system" onclick="setAppearance(this.value)">{{ __('System') }}</x-button>
            </x-button.group>
        </fieldset>
    </x-settings.layout>
</div>
</x-layouts.user>
