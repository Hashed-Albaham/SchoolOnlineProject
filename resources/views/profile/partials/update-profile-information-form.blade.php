<section>
    <header>
        <h2 class="text-lg font-semibold text-white">
            {{ __('site.profile_info') }}
        </h2>
        <p class="mt-1 text-sm text-luxury-400">
            {{ __('site.profile_info_desc') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-luxury-300 mb-1">{{ __('site.full_name') }}</label>
            <input id="name" name="name" type="text"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-luxury-300 mb-1">{{ __('site.email') }}</label>
            <input id="email" name="email" type="email"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-yellow-400">
                        {{ __('site.email_unverified') }}
                        <button form="send-verification" class="underline text-gold-400 hover:text-gold-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500">
                            {{ __('site.resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('site.verification_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold">
                {{ __('site.save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400">{{ __('site.saved') }}</p>
            @endif
        </div>
    </form>
</section>
