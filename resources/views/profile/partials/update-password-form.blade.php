<section>
    <header>
        <h2 class="text-lg font-semibold text-white">
            {{ __('site.update_password') }}
        </h2>
        <p class="mt-1 text-sm text-luxury-400">
            {{ __('site.update_password_desc') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-luxury-300 mb-1">{{ __('site.current_password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-luxury-300 mb-1">{{ __('site.new_password') }}</label>
            <input id="update_password_password" name="password" type="password"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-luxury-300 mb-1">{{ __('site.confirm_password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold">
                {{ __('site.save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400">{{ __('site.saved') }}</p>
            @endif
        </div>
    </form>
</section>
