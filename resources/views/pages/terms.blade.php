<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.terms_of_service') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8 prose prose-invert max-w-none">

                <p class="text-luxury-300 text-sm mb-6">{{ __('site.last_updated') }}: 2026-03-04</p>

                <h3 class="text-gold-400">1. {{ __('site.tos_acceptance_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.tos_acceptance_text') }}</p>

                <h3 class="text-gold-400 mt-6">2. {{ __('site.tos_account_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.tos_account_accurate') }}</li>
                    <li>{{ __('site.tos_account_secure') }}</li>
                    <li>{{ __('site.tos_account_responsible') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">3. {{ __('site.tos_tutor_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.tos_tutor_original') }}</li>
                    <li>{{ __('site.tos_tutor_quality') }}</li>
                    <li>{{ __('site.tos_tutor_respond') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">4. {{ __('site.tos_student_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.tos_student_personal') }}</li>
                    <li>{{ __('site.tos_student_no_share') }}</li>
                    <li>{{ __('site.tos_student_respect') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">5. {{ __('site.tos_payment_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.tos_payment_text') }}</p>

                <h3 class="text-gold-400 mt-6">6. {{ __('site.tos_prohibited_title') }}</h3>
                <ul class="text-luxury-300 space-y-1">
                    <li>{{ __('site.tos_prohibited_abuse') }}</li>
                    <li>{{ __('site.tos_prohibited_spam') }}</li>
                    <li>{{ __('site.tos_prohibited_hack') }}</li>
                </ul>

                <h3 class="text-gold-400 mt-6">7. {{ __('site.tos_termination_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.tos_termination_text') }}</p>

                <h3 class="text-gold-400 mt-6">8. {{ __('site.tos_contact_title') }}</h3>
                <p class="text-luxury-300 leading-relaxed">{{ __('site.tos_contact_text') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
