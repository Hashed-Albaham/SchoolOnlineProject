<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.edit_user') }}: {{ $user->name }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.edit_user_desc') }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-luxury-300 text-sm hover:bg-luxury-700 transition">
                ← {{ __('site.back_to_users') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')

                    <div class="space-y-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.email') }}</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.role') }}</label>
                            <select name="role" class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                                <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>{{ __('site.student') }}</option>
                                <option value="tutor" {{ old('role', $user->role) == 'tutor' ? 'selected' : '' }}>{{ __('site.tutor') }}</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('site.admin') }}</option>
                            </select>
                            @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password (optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.new_password') }} ({{ __('site.optional') }})</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                            @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.confirm_password') }}</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-luxury-900 font-semibold hover:from-gold-400 hover:to-gold-500 transition shadow-lg">
                                {{ __('site.save_changes') }}
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="text-luxury-400 hover:text-white transition">{{ __('site.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
