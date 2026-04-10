@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ __('site.fin_transactions') }}</h1>
            <p class="text-luxury-400 text-sm mt-1">سجل الحركات المالية للمنصة</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <h3 class="text-gray-400 text-sm font-medium mb-1">إجمالي الإيرادات</h3>
            <p class="text-2xl font-bold text-green-400">{{ number_format($stats['total_revenue'], 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}</p>
        </div>
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <h3 class="text-gray-400 text-sm font-medium mb-1">عمولة المنصة</h3>
            <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['platform_fees'], 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}</p>
        </div>
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <h3 class="text-gray-400 text-sm font-medium mb-1">إجمالي المدفوعات للمعلمين</h3>
            <p class="text-2xl font-bold text-purple-400">{{ number_format($stats['total_payouts'], 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}</p>
        </div>
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <h3 class="text-gray-400 text-sm font-medium mb-1">معاملات معلقة</h3>
            <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending_count'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث برقم المرجع..."
                       class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
            </div>
            <div>
                <select name="type" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                    <option value="">كل الأنواع</option>
                    <option value="enrollment" {{ request('type') == 'enrollment' ? 'selected' : '' }}>{{ __('site.fin_type_enrollment') }}</option>
                    <option value="payout" {{ request('type') == 'payout' ? 'selected' : '' }}>{{ __('site.fin_type_payout') }}</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>{{ __('site.fin_type_refund') }}</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>{{ __('site.fin_type_adjustment') }}</option>
                </select>
            </div>
            <div>
                <select name="status" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                    <option value="">كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('site.fin_status_pending') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('site.fin_status_completed') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('site.fin_status_failed') }}</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>{{ __('site.fin_status_refunded') }}</option>
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
            </div>
            <div>
                <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-black font-bold py-2 px-6 rounded-lg transition-colors">
                    تصفية
                </button>
            </div>
            @if(request()->anyFilled(['search', 'type', 'status', 'date_from', 'date_to']))
                <div>
                    <a href="{{ route('admin.transactions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors inline-block">
                        إلغاء الفلاتر
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900/50 text-gray-400 text-sm">
                        <th class="p-4 font-medium">{{ __('site.fin_reference_number') }}</th>
                        <th class="p-4 font-medium">{{ __('site.fin_type') }}</th>
                        <th class="p-4 font-medium">الطالب/المعلم</th>
                        <th class="p-4 font-medium">المبلغ الإجمالي</th>
                        <th class="p-4 font-medium">العمولة</th>
                        <th class="p-4 font-medium">صافي المعلم</th>
                        <th class="p-4 font-medium">الحالة</th>
                        <th class="p-4 font-medium">التاريخ</th>
                        <th class="p-4 font-medium text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="p-4 text-sm font-medium text-white">
                                {{ $transaction->reference_number }}
                            </td>
                            <td class="p-4 text-sm">
                                @if($transaction->type == 'enrollment')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-500/10 text-blue-400">
                                        {{ __('site.fin_type_enrollment') }}
                                    </span>
                                @elseif($transaction->type == 'payout')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-500/10 text-purple-400">
                                        {{ __('site.fin_type_payout') }}
                                    </span>
                                @elseif($transaction->type == 'refund')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-500/10 text-orange-400">
                                        {{ __('site.fin_type_refund') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-500/10 text-gray-400">
                                        {{ __('site.fin_type_adjustment') }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-luxury-300">
                                @if($transaction->student)
                                    <div><span class="text-gray-500 text-xs">طالب:</span> {{ $transaction->student->name }}</div>
                                @endif
                                @if($transaction->tutor)
                                    <div><span class="text-gray-500 text-xs">معلم:</span> {{ $transaction->tutor->name }}</div>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-white">
                                {{ number_format($transaction->gross_amount, 2) }}
                            </td>
                            <td class="p-4 text-sm text-red-400">
                                {{ number_format($transaction->platform_fee_amount, 2) }}
                            </td>
                            <td class="p-4 text-sm text-green-400">
                                {{ number_format($transaction->tutor_amount, 2) }}
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
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-gold-400 hover:text-gold-300 transition px-2 py-1 bg-gold-400/10 rounded">
                                    عرض
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-luxury-400">
                                لا توجد معاملات مالية مطابقة للبحث.
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
@endsection