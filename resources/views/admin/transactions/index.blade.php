<x-app-layout>
<div class="py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ __('site.fin_transactions') }}</h1>
            <p class="text-luxury-400 text-sm mt-1">سجل الحركات المالية للمنصة</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
            <p class="text-luxury-400 text-sm font-medium mb-1">إجمالي الإيرادات</p>
            <p class="text-2xl font-bold text-green-400">{{ number_format($stats['total_revenue'], 2) }} <span class="text-lg text-luxury-500">{{ App\Models\Setting::get('currency_symbol', 'SAR') }}</span></p>
        </div>
        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
            <p class="text-luxury-400 text-sm font-medium mb-1">عمولة المنصة</p>
            <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['platform_fees'], 2) }} <span class="text-lg text-luxury-500">{{ App\Models\Setting::get('currency_symbol', 'SAR') }}</span></p>
        </div>
        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
            <p class="text-luxury-400 text-sm font-medium mb-1">إجمالي المدفوعات للمعلمين</p>
            <p class="text-2xl font-bold text-purple-400">{{ number_format($stats['total_payouts'], 2) }} <span class="text-lg text-luxury-500">{{ App\Models\Setting::get('currency_symbol', 'SAR') }}</span></p>
        </div>
        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
            <p class="text-luxury-400 text-sm font-medium mb-1">معاملات معلقة</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending_count'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-6">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-luxury-400 text-xs mb-1">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث برقم المرجع..."
                       class="w-full bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-luxury-400 text-xs mb-1">النوع</label>
                <select name="type" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                    <option value="">كل الأنواع</option>
                    <option value="enrollment" {{ request('type') == 'enrollment' ? 'selected' : '' }}>{{ __('site.fin_type_enrollment') }}</option>
                    <option value="payout" {{ request('type') == 'payout' ? 'selected' : '' }}>{{ __('site.fin_type_payout') }}</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>{{ __('site.fin_type_refund') }}</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>{{ __('site.fin_type_adjustment') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-luxury-400 text-xs mb-1">الحالة</label>
                <select name="status" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                    <option value="">كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('site.fin_status_pending') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('site.fin_status_completed') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('site.fin_status_failed') }}</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>{{ __('site.fin_status_refunded') }}</option>
                </select>
            </div>
            <div>
                @php /** @var \App\Models\User[] $tutors */ @endphp
                <label class="block text-luxury-400 text-xs mb-1">المعلم</label>
                <select name="tutor_id" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500 min-w-[120px]">
                    <option value="">كل المعلمين</option>
                    @foreach($tutors ?? [] as $tutor)
                        <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>{{ $tutor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                @php /** @var \App\Models\User[] $students */ @endphp
                <label class="block text-luxury-400 text-xs mb-1">الطالب</label>
                <select name="student_id" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500 min-w-[120px]">
                    <option value="">كل الطلاب</option>
                    @foreach($students ?? [] as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-luxury-400 text-xs mb-1">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-luxury-400 text-xs mb-1">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
            </div>
            <div>
                <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-luxury-900 font-bold py-2.5 px-6 rounded-xl transition-all shadow-md">
                    تصفية
                </button>
            </div>
            @if(request()->anyFilled(['search', 'type', 'status', 'date_from', 'date_to', 'tutor_id', 'student_id']))
                <div>
                    <a href="{{ route('admin.transactions.index') }}" class="bg-white/5 hover:bg-white/10 text-white font-bold py-2.5 px-4 rounded-xl transition-colors inline-block border border-white/10">
                        إلغاء الفلاتر
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-luxury-900/50 text-luxury-400 text-sm border-b border-white/5">
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
                <tbody class="divide-y divide-white/5">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-4 text-sm font-medium text-white">
                                {{ $transaction->reference_number }}
                            </td>
                            <td class="p-4 text-sm">
                                @if($transaction->type == 'enrollment')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        {{ __('site.fin_type_enrollment') }}
                                    </span>
                                @elseif($transaction->type == 'payout')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                        {{ __('site.fin_type_payout') }}
                                    </span>
                                @elseif($transaction->type == 'refund')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                        {{ __('site.fin_type_refund') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-luxury-500/10 text-luxury-400 border border-luxury-500/20">
                                        {{ __('site.fin_type_adjustment') }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-luxury-300">
                                @if($transaction->student)
                                    <div><span class="text-luxury-500 text-xs">طالب:</span> {{ $transaction->student->name }}</div>
                                @endif
                                @if($transaction->tutor)
                                    <div><span class="text-luxury-500 text-xs">معلم:</span> {{ $transaction->tutor->name }}</div>
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
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                        {{ __('site.fin_status_completed') }}
                                    </span>
                                @elseif($transaction->status == 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                        {{ __('site.fin_status_pending') }}
                                    </span>
                                @elseif($transaction->status == 'failed')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                        {{ __('site.fin_status_failed') }}
                                    </span>
                                @elseif($transaction->status == 'refunded')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-luxury-500/10 text-luxury-400 border border-luxury-500/20">
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
</div>
</x-app-layout>