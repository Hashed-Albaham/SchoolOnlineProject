<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-red-400">
            {{ __('site.delete_account') }}
        </h2>
        <p class="mt-1 text-sm text-luxury-400">
            {{ __('site.delete_account_desc') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 rounded-xl bg-red-500/20 text-red-400 font-semibold border border-red-500/30 hover:bg-red-500/30 transition">
        {{ __('site.delete_account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-white">
                {{ __('site.delete_confirm_title') }}
            </h2>

            <p class="mt-2 text-sm text-luxury-400">
                {{ __('site.delete_confirm_desc') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-medium text-luxury-300 mb-1 sr-only">{{ __('site.password') }}</label>
                <input id="password" name="password" type="password"
                    class="w-full sm:w-3/4 px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-red-500/50 focus:ring-2 focus:ring-red-500/20 transition"
                    placeholder="{{ __('site.password') }}">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 rounded-xl bg-white/5 text-luxury-300 font-semibold border border-white/10 hover:bg-white/10 transition">
                    {{ __('site.cancel') }}
                </button>

                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                    {{ __('site.delete_account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
