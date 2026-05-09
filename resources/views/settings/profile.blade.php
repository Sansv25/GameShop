<x-layouts.user>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Kelola informasi profil Anda</p>
        </div>

        <section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile Information')" :subheading="__('Manage your personal information and theme preference')">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Form Side -->
            <div>
                <x-form method="put" action="{{ route('settings.profile.update') }}" class="space-y-6">
                    <x-input type="text" :label="__('Full Name')" :value="$user->name" name="name" required autofocus autocomplete="name" />

                    <div>
                        <x-input type="email" :label="__('Email Address')" :value="$user->email" name="email" required autocomplete="email" />

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <p class="text-xs text-amber-700 font-medium mb-2">Your email address is unverified.</p>
                                <x-button variant="link" :formaction="route('verification.store')" name="_method" value="post" class="!p-0 text-blue-600 hover:text-blue-700">
                                    {{ __('Resend verification email') }}
                                </x-button>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <x-button class="bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 px-8">
                            {{ __('Update Profile') }}
                        </x-button>

                        <x-action-message class="text-blue-400 font-medium" on="profile-updated">
                            {{ __('Saved successfully.') }}
                        </x-action-message>
                    </div>
                </x-form>
            </div>

            <!-- Theme Side -->
            <div class="bg-slate-50 dark:bg-slate-900 shadow-inner rounded-[2rem] p-8 border border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-800 dark:text-white mb-1">Display Theme</h3>
                <p class="text-xs text-slate-500 mb-6 tracking-tight">Personalize how GameShop looks for you.</p>

                <div class="grid grid-cols-1 gap-4">
                    <button type="button" value="light" onclick="setAppearance(this.value)" class="flex items-center justify-between p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                <x-phosphor-sun-fill class="w-5 h-5" />
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-200">Light Mode</span>
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 group-hover:border-blue-500 flex items-center justify-center transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 scale-50 group-hover:scale-100 transition-all"></div>
                        </div>
                    </button>

                    <button type="button" value="dark" onclick="setAppearance(this.value)" class="flex items-center justify-between p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-blue-900/30 flex items-center justify-center text-slate-600 dark:text-blue-400">
                                <x-phosphor-moon-fill class="w-5 h-5" />
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-200">Dark Mode</span>
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 group-hover:border-blue-500 flex items-center justify-center transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 scale-50 group-hover:scale-100 transition-all"></div>
                        </div>
                    </button>

                    <button type="button" value="system" onclick="setAppearance(this.value)" class="flex items-center justify-between p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600">
                                <x-phosphor-monitor-fill class="w-5 h-5" />
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-200">System Sync</span>
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 group-hover:border-blue-500 flex items-center justify-center transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 scale-50 group-hover:scale-100 transition-all"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </x-settings.layout>

        <section class="mt-10 space-y-6">
            <div class="relative mb-5">
                <x-heading>{{ __('Delete account') }}</x-heading>
                <x-subheading>{{ __('Delete your account and all of its resources') }}</x-subheading>
            </div>

            <x-button type="button" variant="danger" x-init="" x-on:click="$dispatch('modal:open', 'confirm_user_deletion')">
                {{ __('Delete account') }}
            </x-button>

            <x-modal id="confirm_user_deletion" :open="$errors->has('password')">
                <x-form method="delete" action="{{ route('settings.profile.destroy') }}" class="space-y-6">
                    <div>
                        <x-heading size="lg">{{ __('Are you sure you want to delete your account?') }}</x-heading>
                        <x-subheading>
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </x-subheading>
                    </div>

                    <x-input type="password" :label="__('Password')" name="password" />

                    <div class="flex justify-end space-x-2">
                        <x-button variant="secondary" form="confirm_user_deletion_close">{{ __('Cancel') }}</x-button>
                        <x-button variant="danger">{{ __('Delete account') }}</x-button>
                    </div>
                </x-form>
            </x-modal>
        </section>
</section>
</x-layouts.user>
