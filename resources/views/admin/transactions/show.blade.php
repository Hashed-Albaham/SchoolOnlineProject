<x-app-layout>
<div class="py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ __('site.fin_transaction_details') }}</h1>
            <p class="text-luxury-400 text-sm mt-1">{{ $transaction->reference_number }}</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition border border-gray-700">
            العودة للسجل
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white mb-2">معلومات أساسية</h2>
                        <div class="flex gap-2">
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
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">تاريخ الإنشاء</p>
                        <p class="text-white">{{ $transaction->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    @if($transaction->student)
                    <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                        <p class="text-xs text-gray-500 mb-1">الطالب</p>
                        <p class="text-white font-medium">{{ $transaction->student->name }}</p>
                        <p class="text-sm text-gray-400">{{ $transaction->student->email }}</p>
                    </div>
                    @endif

                    @if($transaction->tutor)
                    <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                        <p class="text-xs text-gray-500 mb-1">المعلم</p>
                        <p class="text-white font-medium">{{ $transaction->tutor->name }}</p>
                        <p class="text-sm text-gray-400">{{ $transaction->tutor->email }}</p>
                    </div>
                    @endif
                </div>

                @if($transaction->enrollment && $transaction->enrollment->course)
                <div class="mb-6 p-4 bg-blue-500/5 rounded-lg border border-blue-500/20">
                    <p class="text-xs text-blue-400 mb-1">مرتبطة باشتراك دورة:</p>
                    <a href="{{ route('admin.courses.show', $transaction->enrollment->course) }}" class="text-white font-medium hover:text-gold-400 transition">
                        {{ $transaction->enrollment->course->title }}
                    </a>
                </div>
                @endif

                @if($transaction->payoutRequest)
                <div class="mb-6 p-4 bg-purple-500/5 rounded-lg border border-purple-500/20">
                    <p class="text-xs text-purple-400 mb-1">مرتبطة بطلب سحب:</p>
                    <p class="text-white font-medium">طلب سحب رقم #{{ $transaction->payout_request_id }}</p>
                </div>
                @endif

                @if($transaction->notes)
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-2">ملاحظات</h3>
                    <p class="text-white bg-gray-900 p-4 rounded-lg border border-gray-700">{{ $transaction->notes }}</p>
                </div>
                @endif
            </div>

            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4">معلومات المعالجة</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('site.fin_processed_by') }}</p>
                        <p class="text-white font-medium">{{ $transaction->processor ? $transaction->processor->name : 'غير متوفر' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('site.fin_processed_at') }}</p>
                        <p class="text-white font-medium">{{ $transaction->processed_at ? $transaction->processed_at->format('Y-m-d H:i') : 'لم تتم المعالجة' }}</p>
                    </div>
                    @if($transaction->paymentMethod)
                    <div class="col-span-2 mt-2">
                        <p class="text-sm text-gray-400 mb-1">طريقة الدفع</p>
                        <p class="text-white font-medium">{{ $transaction->paymentMethod->name_ar }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Financial Breakdown --}}
        <div class="space-y-6">
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 sticky top-6">
                <h2 class="text-xl font-bold text-white mb-6">توزيع المبلغ</h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                        <span class="text-gray-400">{{ __('site.fin_gross_amount') }}</span>
                        <span class="text-xl font-bold text-white">{{ number_format($transaction->gross_amount, 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                        <div>
                            <span class="text-gray-400 block">{{ __('site.fin_platform_fee') }}</span>
                            <span class="text-xs text-gray-500">نسبة: {{ $transaction->platform_fee_rate }}%</span>
                        </div>
                        <span class="text-lg font-bold text-red-400">- {{ number_format($transaction->platform_fee_amount, 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}</span>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-medium text-white">{{ __('site.fin_tutor_amount') }}</span>
                        <span class="text-2xl font-bold text-green-400">{{ number_format($transaction->tutor_amount, 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}</span>
                    </div>
                </div>

                {{-- Visual Bar --}}
                @if($transaction->gross_amount > 0)
                <div class="mt-8">
                    <div class="h-4 w-full bg-gray-700 rounded-full overflow-hidden flex">
                        <div class="bg-red-500 h-full" style="width: {{ ($transaction->platform_fee_amount / $transaction->gross_amount) * 100 }}%"></div>
                        <div class="bg-green-500 h-full" style="width: {{ ($transaction->tutor_amount / $transaction->gross_amount) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-400">
                        <span>المنصة</span>
                        <span>المعلم</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
</x-app-layout>