<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.my_earnings') }}</h2>
        <p class="text-luxury-400 text-sm mt-1">{{ __('site.my_earnings_desc') }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.total_earnings') }}</p>
                    <p class="text-2xl font-bold text-gold-400 mt-1">${{ number_format($totalEarnings, 2) }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.total_paid_out') }}</p>
                    <p class="text-2xl font-bold text-green-400 mt-1">${{ number_format($totalPaidOut, 2) }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.pending_payouts') }}</p>
                    <p class="text-2xl font-bold text-yellow-400 mt-1">${{ number_format($pendingAmount, 2) }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.available_balance') }}</p>
                    <p class="text-2xl font-bold text-white mt-1">${{ number_format($availableBalance, 2) }}</p>
                </div>
            </div>

            <!-- Request Payout Form -->
            @if($availableBalance > 0 && $paymentMethods->count() > 0)
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-gold-500/20 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-bold text-gold-400 mb-4">{{ __('site.request_payout') }}</h3>
                <form action="{{ route('tutor.payouts.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm text-luxury-300 mb-1">{{ __('site.amount') }} ($)</label>
                        <input type="number" name="amount" min="1" max="{{ $availableBalance }}" step="0.01" required
                            class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50"
                            placeholder="{{ number_format($availableBalance, 2) }}">
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-sm text-luxury-300 mb-1">{{ __('site.select_payment_method') }}</label>
                        <select name="payment_method_id" required
                            class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50 [&>option]:bg-luxury-800">
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->icon }} {{ $method->localized_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm text-luxury-300 mb-1">{{ __('site.notes') }} ({{ __('site.optional') }})</label>
                        <input type="text" name="tutor_notes" maxlength="500"
                            class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50"
                            placeholder="{{ __('site.payout_notes_placeholder') }}">
                    </div>
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-luxury-900 font-semibold hover:from-gold-400 hover:to-gold-500 transition shadow-lg whitespace-nowrap">
                        {{ __('site.submit_request') }}
                    </button>
                </form>
            </div>
            @endif

            <!-- Payout History -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.payout_history') }}</h3>
                </div>

                @if($payoutRequests->isEmpty())
                    <div class="p-12 text-center text-luxury-400">
                        <p>{{ __('site.no_payout_requests') }}</p>
                    </div>
                @else
                    <div class="divide-y divide-white/5">
                        @foreach($payoutRequests as $payout)
                            <div class="p-5 flex items-center gap-4 hover:bg-white/5 transition">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl
                                    {{ $payout->isPaid() ? 'bg-green-500/20' : ($payout->isPending() ? 'bg-yellow-500/20' : ($payout->isApproved() ? 'bg-blue-500/20' : 'bg-red-500/20')) }}">
                                    {{ $payout->isPaid() ? '✅' : ($payout->isPending() ? '⏳' : ($payout->isApproved() ? '👍' : '❌')) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-white font-bold">${{ number_format($payout->amount, 2) }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            {{ $payout->isPaid() ? 'bg-green-500/20 text-green-400' : ($payout->isPending() ? 'bg-yellow-500/20 text-yellow-400' : ($payout->isApproved() ? 'bg-blue-500/20 text-blue-400' : 'bg-red-500/20 text-red-400')) }}">
                                            @switch($payout->status)
                                                @case('pending') {{ __('site.pending') }} @break
                                                @case('approved') {{ __('site.approved') }} @break
                                                @case('rejected') {{ __('site.rejected') }} @break
                                                @case('paid') {{ __('site.paid') }} @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="flex gap-4 text-xs text-luxury-500 mt-1">
                                        @if($payout->paymentMethod)
                                            <span>{{ $payout->paymentMethod->icon }} {{ $payout->paymentMethod->localized_name }}</span>
                                        @endif
                                        <span>{{ $payout->created_at->format('Y/m/d H:i') }}</span>
                                    </div>
                                    @if($payout->admin_notes)
                                        <p class="text-red-400 text-xs mt-1">{{ __('site.admin_note') }}: {{ $payout->admin_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
