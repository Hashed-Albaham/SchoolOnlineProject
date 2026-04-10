<x-app-layout>
<div class="py-8">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('student.transactions.index') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            العودة لسجل المدفوعات
        </a>
        <button onclick="window.print()" class="bg-gold-500 hover:bg-gold-600 text-black font-bold py-2 px-6 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            {{ __('site.fin_print') }}
        </button>
    </div>

    {{-- Invoice Container --}}
    <div class="bg-white text-gray-900 rounded-2xl p-8 md:p-12 shadow-xl print:shadow-none print:p-0">
        {{-- Header --}}
        <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-2">ProSkill Academy</h1>
                <p class="text-gray-500 text-sm">منصة التعليم الإلكتروني الرائدة</p>
            </div>
            <div class="text-left">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">
                    @if($transaction->type == 'refund')
                        إشعار استرداد
                    @else
                        {{ __('site.fin_invoice') }}
                    @endif
                </h2>
                <p class="text-sm font-mono text-gray-600">#{{ $transaction->reference_number }}</p>
                <p class="text-sm text-gray-500 mt-1">تاريخ الإصدار: {{ $transaction->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

        {{-- Entities --}}
        <div class="grid grid-cols-2 gap-8 mb-12">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-2">إصدار إلى (الطالب):</p>
                <p class="font-bold text-gray-900 text-lg">{{ auth()->user()->name }}</p>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
            </div>
            <div class="text-left">
                <p class="text-sm text-gray-500 font-medium mb-2">بخصوص الدورة:</p>
                @if($transaction->enrollment && $transaction->enrollment->course)
                    <p class="font-bold text-gray-900 text-lg">{{ $transaction->enrollment->course->title }}</p>
                    <p class="text-gray-600">المعلم: {{ $transaction->enrollment->course->tutor->name ?? '-' }}</p>
                @else
                    <p class="text-gray-900">-</p>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-900 text-gray-900">
                    <th class="py-3 text-right">الوصف</th>
                    <th class="py-3 text-center w-32">الكمية</th>
                    <th class="py-3 text-left w-40">المبلغ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="py-4">
                        <p class="font-bold text-gray-900">
                            @if($transaction->type == 'refund')
                                استرداد مبلغ اشتراك الدورة
                            @else
                                اشتراك في دورة تدريبية
                            @endif
                        </p>
                        @if($transaction->paymentMethod)
                        <p class="text-sm text-gray-500 mt-1">عبر: {{ $transaction->paymentMethod->name_ar }}</p>
                        @endif
                    </td>
                    <td class="py-4 text-center">1</td>
                    <td class="py-4 text-left font-medium">
                        {{ number_format($transaction->gross_amount, 2) }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-900">
                    <td colspan="2" class="py-4 text-right font-bold text-gray-900 text-lg">الإجمالي:</td>
                    <td class="py-4 text-left font-black text-gray-900 text-xl">
                        {{ number_format($transaction->gross_amount, 2) }} {{ App\Models\Setting::get('currency_symbol', 'SAR') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Status Stamp --}}
        <div class="flex justify-between items-end mt-12 pt-8 border-t border-gray-200">
            <div>
                <p class="text-sm text-gray-500">شكراً لاختيارك منصة ProSkill Academy.</p>
                <p class="text-sm text-gray-500 mt-1">هذه الفاتورة إلكترونية ولا تحتاج لتوقيع.</p>
            </div>

            <div class="text-center">
                @if($transaction->status == 'completed')
                    <div class="inline-block border-4 border-green-600 text-green-600 px-6 py-2 rounded-lg font-black text-xl uppercase tracking-wider transform -rotate-12 opacity-80">
                        مكتمل PAID
                    </div>
                @elseif($transaction->status == 'refunded')
                    <div class="inline-block border-4 border-gray-600 text-gray-600 px-6 py-2 rounded-lg font-black text-xl uppercase tracking-wider transform -rotate-12 opacity-80">
                        مسترد REFUNDED
                    </div>
                @elseif($transaction->status == 'pending')
                    <div class="inline-block border-4 border-yellow-600 text-yellow-600 px-6 py-2 rounded-lg font-black text-xl uppercase tracking-wider transform -rotate-12 opacity-80">
                        معلق PENDING
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: white !important; }
        nav, footer { display: none !important; }
        .container { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
    }
</style>
</div>
</div>
</x-app-layout>