<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.payout_management') }}</h2>
        <p class="text-luxury-400 text-sm mt-1">{{ __('site.payout_management_desc') }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
                <div class="bg-luxury-800/50 border border-white/5 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.total_requests') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-yellow-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.pending') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-blue-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['approved'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.approved') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-green-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-green-400">{{ $stats['paid'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.paid') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-gold-500/20 rounded-2xl p-5 text-center">
                    <p class="text-2xl font-bold text-gold-400">${{ number_format($stats['total_paid_amount'], 2) }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.total_paid_out') }}</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="flex gap-2 mb-6">
                <a href="{{ route('admin.payouts.index') }}"
                    class="px-4 py-2 rounded-lg text-sm {{ !request('status') ? 'bg-gold-500/20 text-gold-400' : 'bg-white/5 text-luxury-400 hover:bg-white/10' }} transition">
                    {{ __('site.all') }}
                </a>
                @foreach(['pending', 'approved', 'paid', 'rejected'] as $s)
                    <a href="{{ route('admin.payouts.index', ['status' => $s]) }}"
                        class="px-4 py-2 rounded-lg text-sm {{ request('status') === $s ? 'bg-gold-500/20 text-gold-400' : 'bg-white/5 text-luxury-400 hover:bg-white/10' }} transition">
                        {{ __('site.' . $s) }}
                    </a>
                @endforeach
            </div>

            <!-- Requests Table -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($payoutRequests->isEmpty())
                    <div class="p-12 text-center text-luxury-400">{{ __('site.no_payout_requests') }}</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-right text-luxury-400 text-sm font-medium p-4">{{ __('site.tutor') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.amount') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.payment_type') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.status') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.fin_transaction_details') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.date') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($payoutRequests as $payout)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($payout->tutor->name ?? 'T', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-white text-sm font-medium">{{ $payout->tutor->name ?? '-' }}</p>
                                                    <p class="text-luxury-500 text-xs">{{ $payout->tutor->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="text-gold-400 font-bold">${{ number_format($payout->amount, 2) }}</span>
                                        </td>
                                        <td class="p-4 text-center text-luxury-300 text-sm">
                                            @if($payout->paymentMethod)
                                                {{ $payout->paymentMethod->icon }} {{ $payout->paymentMethod->localized_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="text-xs px-2.5 py-1 rounded-full
                                                {{ $payout->isPaid() ? 'bg-green-500/20 text-green-400' : ($payout->isPending() ? 'bg-yellow-500/20 text-yellow-400' : ($payout->isApproved() ? 'bg-blue-500/20 text-blue-400' : 'bg-red-500/20 text-red-400')) }}">
                                                @switch($payout->status)
                                                    @case('pending') {{ __('site.pending') }} @break
                                                    @case('approved') {{ __('site.approved') }} @break
                                                    @case('rejected') {{ __('site.rejected') }} @break
                                                    @case('paid') {{ __('site.paid') }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="p-4 text-center text-luxury-400 text-sm">
                                            @php $tx = $payout->transactions()->where('type', 'payout')->first(); @endphp
                                            @if($tx)
                                                <a href="{{ route('admin.transactions.show', $tx) }}" class="text-blue-400 hover:text-blue-300">
                                                    {{ $tx->reference_number }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-4 text-center text-luxury-400 text-sm">
                                            {{ $payout->created_at->format('Y/m/d') }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                @if($payout->isPending())
                                                    <form action="{{ route('admin.payouts.approve', $payout) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-500/20 text-green-400 text-xs font-medium hover:bg-green-500/30 transition">
                                                            ✓ {{ __('site.approve') }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.payouts.reject', $payout) }}" method="POST"
                                                        onsubmit="event.preventDefault(); let n=prompt('{{ __('site.rejection_reason') }}'); if(n!==null){this.querySelector('[name=admin_notes]').value=n; this.submit();}">
                                                        @csrf
                                                        <input type="hidden" name="admin_notes" value="">
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 text-xs font-medium hover:bg-red-500/30 transition">
                                                            ✗ {{ __('site.reject') }}
                                                        </button>
                                                    </form>
                                                @elseif($payout->isApproved())
                                                    <form action="{{ route('admin.payouts.markPaid', $payout) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-gold-500/20 text-gold-400 text-xs font-medium hover:bg-gold-500/30 transition"
                                                            onclick="return confirm('{{ __('site.confirm_mark_paid') }}')">
                                                            💰 {{ __('site.mark_as_paid') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-luxury-500 text-xs">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-white/5">
                        {{ $payoutRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
