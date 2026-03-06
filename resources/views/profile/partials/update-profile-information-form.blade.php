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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Header/Avatar Selection -->
        <div class="flex items-center gap-6 mb-8">
            <div class="relative group">
                <x-avatar :user="$user" sizeClasses="w-24 h-24" iconClasses="w-10 h-10" />
                
                <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('avatar').click()">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="flex-1">
                <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.avatar_upload_hint', ['default' => 'JPG, PNG. Max 2MB.']) ?? 'JPG, PNG. Max 2MB.' }}</p>
                
                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-luxury-300 mb-1">{{ __('site.full_name') }}</label>
            <input id="name" name="name" type="text"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <script>
            function previewAvatar(input) {
                if (input.files && input.files[0]) {
                    // Update form indicator or auto-submit if desired
                    // For now, it will just rely on the form submit
                    const file = input.files[0];
                    if(file.size > 2 * 1024 * 1024) {
                        alert("File is too large. Max 2MB.");
                        input.value = "";
                    }
                }
            }
        </script>

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
