<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.privacy_policy') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8 prose prose-invert max-w-none">

                <p class="text-luxury-300 text-sm mb-6">{{ __('site.last_updated') }}: 2026-03-04</p>

                <h3 class="text-gold-400">1. {{ __('site.pp_intro_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.pp_intro_text') }}</p>

                <h3 class="text-gold-400 mt-6">2. {{ __('site.pp_data_collected_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.pp_data_name_email') }}</li>
                    <li>{{ __('site.pp_data_courses') }}</li>
                    <li>{{ __('site.pp_data_messages') }}</li>
                    <li>{{ __('site.pp_data_payment') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">3. {{ __('site.pp_usage_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.pp_usage_service') }}</li>
                    <li>{{ __('site.pp_usage_improve') }}</li>
                    <li>{{ __('site.pp_usage_communicate') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">4. {{ __('site.pp_sharing_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.pp_sharing_text') }}</p>

                <h3 class="text-gold-400 mt-6">5. {{ __('site.pp_security_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.pp_security_text') }}</p>

                <h3 class="text-gold-400 mt-6">6. {{ __('site.pp_rights_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.pp_rights_access') }}</li>
                    <li>{{ __('site.pp_rights_correct') }}</li>
                    <li>{{ __('site.pp_rights_delete') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">7. {{ __('site.pp_contact_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.pp_contact_text') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
