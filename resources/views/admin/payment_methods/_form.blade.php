{{-- Shared form fields for create/edit payment method --}}
<div class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name AR --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.name_ar') }} <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $paymentMethod->name ?? '') }}" required
                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white placeholder-luxury-500 focus:outline-none focus:border-gold-500/50"
                placeholder="{{ __('site.name_ar') }}">
            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Name EN --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.name_en') }}</label>
            <input type="text" name="name_en" value="{{ old('name_en', $paymentMethod->name_en ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white placeholder-luxury-500 focus:outline-none focus:border-gold-500/50"
                placeholder="Bank Transfer">
            @error('name_en') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Type --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.payment_type') }} <span class="text-red-400">*</span></label>
            <select name="type" required class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50 [&>option]:bg-luxury-800">
                <option value="bank_transfer" {{ old('type', $paymentMethod->type ?? '') === 'bank_transfer' ? 'selected' : '' }}>{{ __('site.bank_transfer') }}</option>
                <option value="wallet"        {{ old('type', $paymentMethod->type ?? '') === 'wallet'        ? 'selected' : '' }}>{{ __('site.wallet') }}</option>
                <option value="crypto"        {{ old('type', $paymentMethod->type ?? '') === 'crypto'        ? 'selected' : '' }}>{{ __('site.crypto') }}</option>
                <option value="cash"          {{ old('type', $paymentMethod->type ?? '') === 'cash'          ? 'selected' : '' }}>{{ __('site.cash') }}</option>
                <option value="other"         {{ old('type', $paymentMethod->type ?? '') === 'other'         ? 'selected' : '' }}>{{ __('site.other') }}</option>
            </select>
            @error('type') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Icon --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.icon_emoji') }}</label>
            <input type="text" name="icon" value="{{ old('icon', $paymentMethod->icon ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white text-xl text-center focus:outline-none focus:border-gold-500/50"
                placeholder="💳" maxlength="5">
            @error('icon') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Sort order --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order ?? 0) }}" min="0"
                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
            @error('sort_order') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Account Number --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.account_number') }}</label>
            <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white font-mono focus:outline-none focus:border-gold-500/50"
                placeholder="SA0000000000000000000000">
            @error('account_number') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Account Name --}}
        <div>
            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.account_name') }}</label>
            <input type="text" name="account_name" value="{{ old('account_name', $paymentMethod->account_name ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50"
                placeholder="{{ __('site.account_name') }}">
            @error('account_name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Instructions AR --}}
    <div>
        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.instructions_ar') }} <span class="text-red-400">*</span></label>
        <textarea name="instructions_ar" rows="4" required
            class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white placeholder-luxury-500 focus:outline-none focus:border-gold-500/50"
            placeholder="{{ __('site.instructions_ar') }}...">{{ old('instructions_ar', $paymentMethod->instructions_ar ?? '') }}</textarea>
        @error('instructions_ar') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Instructions EN --}}
    <div>
        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.instructions_en') }}</label>
        <textarea name="instructions_en" rows="4"
            class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white placeholder-luxury-500 focus:outline-none focus:border-gold-500/50"
            placeholder="Transfer the amount to the account below then send the receipt...">{{ old('instructions_en', $paymentMethod->instructions_en ?? '') }}</textarea>
        @error('instructions_en') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Is Active --}}
    <div class="flex items-center gap-3">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            {{ old('is_active', $paymentMethod->is_active ?? true) ? 'checked' : '' }}
            class="w-5 h-5 rounded-lg bg-white/10 border-white/20 text-gold-500 focus:ring-gold-500">
        <label for="is_active" class="text-white font-medium cursor-pointer">{{ __('site.active_payment_method') }}</label>
    </div>
</div>
