<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-luxury-900">
        <!-- Top Decorative Line -->
        <div class="fixed top-0 left-0 right-0 h-1 bg-gold-gradient"></div>

        <div class="w-full max-w-md">
            <!-- Language Switcher & Back -->
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-luxury-400 hover:text-gold-400 transition">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('site.back_to_home') }}
                </a>
                <x-language-switcher />
            </div>

            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gold-gradient flex items-center justify-center shadow-glow group-hover:scale-110 transition-transform duration-300">
                        <span class="text-luxury-900 font-bold text-3xl">P</span>
                    </div>
                    <span class="text-3xl font-bold text-gradient">ProSkill</span>
                </a>
                <h2 class="mt-6 text-2xl font-bold text-white">{{ __('site.create_new_account') }}</h2>
                <p class="mt-2 text-luxury-400">{{ __('site.join_us_desc') }}</p>
            </div>

            <!-- Register Card -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.full_name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-luxury-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="name" type="text" name="name" :value="old('name')" required autofocus
                                autocomplete="name"
                                class="w-full ps-12 pe-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ __('site.placeholder_name') }}">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.email') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-luxury-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required
                                autocomplete="username"
                                class="w-full ps-12 pe-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="example@email.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div>
                        <label
                            class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.account_type') }}</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="student" class="peer sr-only" checked>
                                <div
                                    class="p-4 rounded-xl bg-white/5 border border-white/10 peer-checked:border-gold-500 peer-checked:bg-gold-500/10 transition">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ __('site.student') }}</p>
                                            <p class="text-xs text-luxury-400">{{ __('site.student_desc') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="tutor" class="peer sr-only">
                                <div
                                    class="p-4 rounded-xl bg-white/5 border border-white/10 peer-checked:border-gold-500 peer-checked:bg-gold-500/10 transition">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-royal-500/20 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-royal-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ __('site.tutor') }}</p>
                                            <p class="text-xs text-luxury-400">{{ __('site.tutor_desc') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-luxury-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full ps-12 pe-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.confirm_password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-luxury-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full ps-12 pe-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- [REQ] Terms Agreement -->
                    <div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="agreed_to_terms" value="1"
                                {{ old('agreed_to_terms') ? 'checked' : '' }}
                                class="mt-1 w-5 h-5 rounded bg-white/5 border-white/20 text-gold-500 focus:ring-gold-500/20 focus:ring-offset-0">
                            <span class="text-sm text-luxury-400 group-hover:text-luxury-300 transition">
                                {{ __('site.agree_to') }}
                                <a href="{{ route('pages.terms') }}" target="_blank" class="text-gold-400 hover:text-gold-300 underline">{{ __('site.terms_conditions') }}</a>
                                {{ __('site.and') }}
                                <a href="{{ route('pages.privacy') }}" target="_blank" class="text-gold-400 hover:text-gold-300 underline">{{ __('site.privacy_policy') }}</a>
                            </span>
                        </label>
                        <x-input-error :messages="$errors->get('agreed_to_terms')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-premium w-full py-4 rounded-xl bg-gold-400 font-semibold text-lg">
                        {{ __('site.create_account_btn') }}
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-luxury-800 text-luxury-500">{{ __('site.or') }}</span>
                    </div>
                </div>

                <!-- Login Link -->
                <p class="text-center text-luxury-400">
                    {{ __('site.already_have_account') }}
                    <a href="{{ route('login') }}" class="text-gold-400 hover:text-gold-300 font-medium transition">
                        {{ __('site.login_now') }}
                    </a>
                </p>
            </div>

            <!-- Footer -->
            <p class="text-center text-luxury-500 text-sm mt-8">
                &copy; {{ date('Y') }} ProSkill. {{ __('site.rights_reserved') }}
            </p>
        </div>
    </div>

    {{-- [v8.0] Eligibility UX hint - THIS IS COSMETIC ONLY, real security is in Backend --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const submitBtn = document.querySelector('button[type="submit"]');
            const eligPassed = {{ session('elig_passed') ? 'true' : 'false' }};

            // Create warning element
            const warning = document.createElement('div');
            warning.id = 'elig-warning';
            warning.className = 'p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-sm hidden';
            warning.innerHTML = `
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="font-medium">{{ __('site.elig_warning_title') }}</p>
                        <p class="mt-1">{{ __('site.elig_warning_desc') }}</p>
                        <a href="{{ route('eligibility.show') }}" class="inline-flex items-center gap-1 mt-2 text-gold-400 hover:text-gold-300 font-medium transition">
                            {{ __('site.check_eligibility_btn') }} →
                        </a>
                    </div>
                </div>
            `;

            // Insert warning before submit button
            const termsSection = document.querySelector('input[name="agreed_to_terms"]').closest('div').parentElement;
            termsSection.parentElement.insertBefore(warning, termsSection.nextSibling);

            function handleRoleChange(role) {
                if (role === 'tutor' && !eligPassed) {
                    warning.classList.remove('hidden');
                } else {
                    warning.classList.add('hidden');
                }
            }

            roleRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    handleRoleChange(this.value);
                });
                if (radio.checked) handleRoleChange(radio.value);
            });
        });
    </script>
</x-guest-layout>