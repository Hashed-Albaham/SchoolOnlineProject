<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-luxury-900">
        <!-- Top Decorative Line -->
        <div class="fixed top-0 left-0 right-0 h-1 bg-gold-gradient"></div>

        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gold-gradient flex items-center justify-center shadow-glow group-hover:scale-110 transition-transform duration-300">
                        <span class="text-luxury-900 font-bold text-3xl">P</span>
                    </div>
                    <span class="text-3xl font-bold text-gradient">ProSkill</span>
                </a>
                <h2 class="mt-6 text-2xl font-bold text-white">{{ __('site.welcome_back') }}</h2>
                <p class="mt-2 text-luxury-400">{{ __('site.login_to_continue') }}</p>
            </div>

            <!-- Login Card -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

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
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username"
                                class="w-full ps-12 pe-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ __('site.placeholder_email') ?? 'example@email.com' }}">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="w-full ps-12 pe-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-white/20 bg-white/5 text-gold-500 focus:ring-gold-500/20 focus:ring-offset-0">
                            <span class="text-sm text-luxury-400">{{ __('site.remember_me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-gold-400 hover:text-gold-300 transition">
                                {{ __('site.forgot_password') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-premium w-full py-4 rounded-xl bg-gold-400 font-semibold text-lg">
                        {{ __('site.login_btn') }}
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

                <!-- Register Link -->
                <p class="text-center text-luxury-400">
                    {{ __('site.no_account') }}
                    <a href="{{ route('register') }}" class="text-gold-400 hover:text-gold-300 font-medium transition">
                        {{ __('site.register_now') }}
                    </a>
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-luxury-500 text-sm">
                <p>&copy; {{ date('Y') }} ProSkill. {{ __('site.rights_reserved') }}</p>
            </div>
        </div>
    </div>
</x-guest-layout>