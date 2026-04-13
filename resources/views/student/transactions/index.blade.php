<x-app-layout>
<div class="py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">{{ __('site.fin_payment_history') }}</h1>
        <p class="text-luxury-400 text-sm mt-1">سجل مدفوعاتك الخاصة بالاشتراكات</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <h3 class="text-gray-400 text-sm font-medium mb-1">إجمالي المدفوعات</h3>
            <p class="text-2xl font-bold text-white">
                {{ number_format($transactions->where('status', 'completed')->sum('gross_amount'), 2) }}
                {{ App\Models\Setting::get('currency_symbol', 'SAR') }}
            </p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900/50 text-gray-400 text-sm">
                        <th class="p-4 font-medium">{{ __('site.fin_reference_number') }}</th>
                        <th class="p-4 font-medium">الدورة</th>
                        <th class="p-4 font-medium">{{ __('site.fin_type') }}</th>
                        <th class="p-4 font-medium">المبلغ</th>
                        <th class="p-4 font-medium">الحالة</th>
                        <th class="p-4 font-medium">التاريخ</th>
                        <th class="p-4 font-medium text-center">الفاتورة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="p-4 text-sm font-medium text-white">
                                {{ $transaction->reference_number }}
                            </td>
                            <td class="p-4 text-sm text-luxury-300">
                                @if($transaction->enrollment && $transaction->enrollment->course)
                                    <a href="{{ route('student.courses.show', $transaction->enrollment->course) }}" class="hover:text-gold-400">
                                        {{ Str::limit($transaction->enrollment->course->title, 40) }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 text-sm">
                                @if($transaction->type == 'enrollment')
                                    <span class="text-blue-400">{{ __('site.fin_type_enrollment') }}</span>
                                @elseif($transaction->type == 'refund')
                                    <span class="text-orange-400">{{ __('site.fin_type_refund') }}</span>
                                @else
                                    <span class="text-gray-400">{{ $transaction->type }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-white font-medium">
                                {{ number_format($transaction->gross_amount, 2) }} {{ \App\Models\Setting::get('currency_symbol', 'ر.س') }}
                            </td>
                            <td class="p-4 text-sm">
                                @if($transaction->status == 'completed')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-500/10 text-green-400">
                                        {{ __('site.fin_status_completed') }}
                                    </span>
                                @elseif($transaction->status == 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400">
                                        {{ __('site.fin_status_pending') }}
                                    </span>
                                @elseif($transaction->status == 'failed')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-500/10 text-red-400">
                                        {{ __('site.fin_status_failed') }}
                                    </span>
                                @elseif($transaction->status == 'refunded')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-500/10 text-gray-400">
                                        {{ __('site.fin_status_refunded') }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-luxury-400">
                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-4 text-sm text-center">
                                <a href="{{ route('student.transactions.show', $transaction) }}" class="text-gold-400 hover:text-gold-300 transition px-3 py-1 bg-gold-400/10 rounded inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    الفاتورة
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-luxury-400">
                                {{ __('site.fin_no_transactions') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="p-4 border-t border-gray-700">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
</div>
</x-app-layout>