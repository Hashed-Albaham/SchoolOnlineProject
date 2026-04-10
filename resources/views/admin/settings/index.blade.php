<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    ⚙️ {{ __('site.system_settings') }}
                </h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.system_settings_desc') }}</p>
            </div>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-500/20 text-purple-400 text-sm font-medium border border-purple-500/30">
                🛡️ {{ __('site.super_admin_only') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            {{-- Settings Form --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mb-8">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        🎓 {{ __('site.eligibility_requirements') }}
                    </h3>
                    <p class="text-luxury-400 text-sm mt-1">{{ __('site.eligibility_requirements_desc') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6 space-y-6">
                    @csrf

                    {{-- GPA Settings --}}
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label for="min_gpa" class="block text-sm font-medium text-luxury-300 mb-2">
                                {{ __('site.min_gpa') }}
                            </label>
                            <input type="number" name="min_gpa" id="min_gpa" step="0.01" min="0" max="5"
                                value="{{ old('min_gpa', $settings['min_gpa'] ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ __('site.eg') }} 3.00">
                            <p class="text-luxury-500 text-xs mt-1">{{ __('site.leave_empty_no_requirement') }}</p>
                            <x-input-error :messages="$errors->get('min_gpa')" class="mt-2" />
                        </div>

                        <div>
                            <label for="min_gpa_scale" class="block text-sm font-medium text-luxury-300 mb-2">
                                {{ __('site.gpa_scale') }}
                            </label>
                            <select name="min_gpa_scale" id="min_gpa_scale"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                                <option value="4.0" {{ (old('min_gpa_scale', $settings['min_gpa_scale'] ?? '4.0') == '4.0') ? 'selected' : '' }}>4.0</option>
                                <option value="5.0" {{ (old('min_gpa_scale', $settings['min_gpa_scale'] ?? '') == '5.0') ? 'selected' : '' }}>5.0</option>
                            </select>
                            <x-input-error :messages="$errors->get('min_gpa_scale')" class="mt-2" />
                        </div>
                    </div>

                    {{-- STEP Score --}}
                    <div class="max-w-sm">
                        <label for="min_step_score" class="block text-sm font-medium text-luxury-300 mb-2">
                            {{ __('site.min_step_score') }}
                        </label>
                        <input type="number" name="min_step_score" id="min_step_score" min="0" max="100"
                            value="{{ old('min_step_score', $settings['min_step_score'] ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.eg') }} 65">
                        <p class="text-luxury-500 text-xs mt-1">{{ __('site.leave_empty_no_requirement') }}</p>
                        <x-input-error :messages="$errors->get('min_step_score')" class="mt-2" />
                    </div>

                    {{-- [FIN] إعدادات مالية --}}
                    <div class="pt-6 border-t border-white/5">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            💰 {{ __('site.fin_financial_settings') }}
                        </h3>
                        <div class="grid sm:grid-cols-3 gap-6">
                            <div>
                                <label for="platform_commission_rate" class="block text-sm font-medium text-luxury-300 mb-2">
                                    {{ __('site.fin_commission_rate') }} (%)
                                </label>
                                <input type="number" name="platform_commission_rate" id="platform_commission_rate" step="0.5" min="0" max="100"
                                    value="{{ old('platform_commission_rate', $settings['platform_commission_rate'] ?? 20) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                                <x-input-error :messages="$errors->get('platform_commission_rate')" class="mt-2" />
                            </div>

                            <div>
                                <label for="min_payout_amount" class="block text-sm font-medium text-luxury-300 mb-2">
                                    {{ __('site.fin_min_payout') }}
                                </label>
                                <input type="number" name="min_payout_amount" id="min_payout_amount" step="1" min="0"
                                    value="{{ old('min_payout_amount', $settings['min_payout_amount'] ?? 50) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                                <x-input-error :messages="$errors->get('min_payout_amount')" class="mt-2" />
                            </div>

                            <div>
                                <label for="currency_symbol" class="block text-sm font-medium text-luxury-300 mb-2">
                                    {{ __('site.fin_currency') }}
                                </label>
                                <input type="text" name="currency_symbol" id="currency_symbol" maxlength="5"
                                    value="{{ old('currency_symbol', $settings['currency_symbol'] ?? 'SAR') }}"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                                <x-input-error :messages="$errors->get('currency_symbol')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end pt-4 border-t border-white/5">
                        <button type="submit" class="btn-premium px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('site.save_settings') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Audit Log --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        📋 {{ __('site.settings_audit_log') }}
                    </h3>
                    <p class="text-luxury-400 text-sm mt-1">{{ __('site.settings_audit_log_desc') }}</p>
                </div>

                @if($history->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.setting_key') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.old_value') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.new_value') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.changed_by') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($history as $log)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-royal-500/20 text-royal-400 font-mono">{{ $log->key }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-red-400 text-sm font-mono">{{ $log->old_value ?? '—' }}</td>
                                        <td class="px-6 py-4 text-green-400 text-sm font-mono">{{ $log->new_value ?? '—' }}</td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">{{ $log->changedBy->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $log->created_at->format('Y/m/d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.no_audit_logs') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
