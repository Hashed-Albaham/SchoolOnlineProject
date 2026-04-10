<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.create_user') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.create_user_desc') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.user_info') }}</h3>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-6">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.placeholder_name') }}">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="user@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.role') }}</label>
                        <select name="role" id="role" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>{{ __('site.student') }}</option>
                            <option value="tutor" {{ old('role') == 'tutor' ? 'selected' : '' }}>{{ __('site.tutor') }}</option>
                            @if(auth()->user()->isSuperAdmin())
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('site.admin') }}</option>
                            @endif
                        </select>
                        @if(!auth()->user()->isSuperAdmin())
                            <p class="text-luxury-500 text-xs mt-1">{{ __('site.admin_role_super_only') }}</p>
                        @endif
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.password') }}</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="••••••••">
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end pt-4 border-t border-white/5">
                        <button type="submit" class="btn-premium px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('site.create_user_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
