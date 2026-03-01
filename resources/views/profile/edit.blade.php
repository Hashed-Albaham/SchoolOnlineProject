<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.profile_settings') }}</h2>
        <p class="text-luxury-400 text-sm mt-1">{{ __('site.profile_settings_desc') }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Update Profile Information -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Update Password -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete Account -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
