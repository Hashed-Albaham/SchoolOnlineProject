<x-guest-layout>
    <div class="min-h-screen bg-luxury-900 flex flex-col items-center justify-center p-4">

        <!-- Verification Card -->
        <div
            class="w-full max-w-md bg-luxury-800/50 backdrop-blur-xl border border-gold-500/30 rounded-2xl p-8 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-royal-500/10 rounded-full blur-3xl -ml-16 -mb-16"></div>

            <div class="text-center relative z-10">
                <!-- Verified Icon -->
                <div
                    class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-green-500/30 shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-white mb-2">{{ __('site.certificate_verified') }}</h1>
                <p class="text-luxury-400 text-sm mb-6">{{ __('site.certificate_valid_msg') }}</p>

                <!-- Certificate Details -->
                <div class="space-y-4 text-right bg-white/5 rounded-xl p-4 border border-white/10">
                    <div class="flex justify-between items-center border-b border-white/5 pb-2">
                        <span class="text-luxury-400 text-xs">{{ __('site.student_name') }}</span>
                        <span class="text-white font-medium">{{ $certificate->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-white/5 pb-2">
                        <span class="text-luxury-400 text-xs">{{ __('site.course_name') }}</span>
                        <span class="text-white font-medium">{{ $certificate->course->title }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-white/5 pb-2">
                        <span class="text-luxury-400 text-xs">{{ __('site.instructor') }}</span>
                        <span
                            class="text-white font-medium">{{ $certificate->course->tutor->name ?? __('site.platform_instructor') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-white/5 pb-2">
                        <span class="text-luxury-400 text-xs">{{ __('site.issue_date') }}</span>
                        <span class="text-white font-medium">{{ $certificate->issued_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-luxury-400 text-xs">{{ __('site.certificate_code') }}</span>
                        <span class="text-gold-400 font-mono text-sm">{{ $certificate->certificate_code }}</span>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('home') }}" class="text-gold-400 hover:text-gold-300 text-sm transition">
                        {{ __('site.back_to_home') }}
                    </a>
                </div>
            </div>
        </div>

        <p class="mt-8 text-luxury-500 text-xs">
            &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('site.rights_reserved') }}
        </p>
    </div>
</x-guest-layout>