<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-luxury-900">
        <div class="fixed top-0 left-0 right-0 h-1 bg-gold-gradient"></div>

        <div class="w-full max-w-lg">
            {{-- Back & Language --}}
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-luxury-400 hover:text-gold-400 transition">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('site.back_to_home') }}
                </a>
                <x-language-switcher />
            </div>

            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-14 h-14 rounded-2xl bg-gold-gradient flex items-center justify-center shadow-glow group-hover:scale-110 transition-transform duration-300">
                        <span class="text-luxury-900 font-bold text-3xl">P</span>
                    </div>
                    <span class="text-3xl font-bold text-gradient">ProSkill</span>
                </a>
                <h2 class="mt-6 text-2xl font-bold text-white">{{ __('site.eligibility_check_title') }}</h2>
                <p class="mt-2 text-luxury-400">{{ __('site.eligibility_check_desc') }}</p>
            </div>

            {{-- Flash Messages --}}
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('elig_failed'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium mb-1">{{ __('site.elig_not_met') }}</p>
                        <p>{{ __('site.elig_not_met_desc') }}</p>
                    </div>
                </div>
            @endif

            {{-- Eligibility Form --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form method="POST" action="{{ route('eligibility.check') }}" class="space-y-6">
                    @csrf

                    {{-- GPA --}}
                    <div>
                        <label for="gpa" class="block text-sm font-medium text-luxury-300 mb-2">
                            {{ __('site.gpa_label') }}
                        </label>
                        <input type="number" name="gpa" id="gpa" step="0.01" min="0" max="5" required
                            value="{{ old('gpa') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.eg') }} 3.75">
                        <x-input-error :messages="$errors->get('gpa')" class="mt-2" />
                    </div>

                    {{-- GPA Scale --}}
                    <div>
                        <label for="gpa_scale" class="block text-sm font-medium text-luxury-300 mb-2">
                            {{ __('site.gpa_scale') }}
                        </label>
                        <select name="gpa_scale" id="gpa_scale" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                            <option value="4.0" {{ old('gpa_scale') == '4.0' ? 'selected' : '' }}>{{ __('site.out_of') }} 4.0</option>
                            <option value="5.0" {{ old('gpa_scale') == '5.0' ? 'selected' : '' }}>{{ __('site.out_of') }} 5.0</option>
                        </select>
                        <x-input-error :messages="$errors->get('gpa_scale')" class="mt-2" />
                    </div>

                    {{-- STEP Score --}}
                    <div>
                        <label for="step_score" class="block text-sm font-medium text-luxury-300 mb-2">
                            {{ __('site.step_score_label') }}
                        </label>
                        <input type="number" name="step_score" id="step_score" min="0" max="100" required
                            value="{{ old('step_score') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.eg') }} 72">
                        <x-input-error :messages="$errors->get('step_score')" class="mt-2" />
                    </div>

                    {{-- Info Box --}}
                    <div class="p-4 rounded-xl bg-royal-500/10 border border-royal-500/20">
                        <p class="text-royal-400 text-sm leading-relaxed">
                            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('site.elig_info_note') }}
                        </p>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-premium w-full py-4 rounded-xl font-semibold text-lg">
                        {{ __('site.check_eligibility_btn') }}
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="mt-6 text-center">
                    <p class="text-luxury-400 text-sm">
                        {{ __('site.want_to_register_as_student') }}
                        <a href="{{ route('register') }}" class="text-gold-400 hover:text-gold-300 font-medium transition">
                            {{ __('site.register_now') }}
                        </a>
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-luxury-500 text-sm mt-8">
                &copy; {{ date('Y') }} ProSkill. {{ __('site.rights_reserved') }}
            </p>
        </div>
    </div>
</x-guest-layout>
