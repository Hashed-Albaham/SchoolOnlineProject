<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">إدارة الحجوزات</h2>
                <p class="text-luxury-400 text-sm mt-1">متابعة وإدارة حجوزات الجلسات الخاصة</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-6">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-luxury-400 text-xs mb-1">الحالة</label>
                        <select name="status" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-gold-500">
                            <option value="">كل الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في انتظار الدفع</option>
                            <option value="pending_tutor_approval" {{ request('status') == 'pending_tutor_approval' ? 'selected' : '' }}>بانتظار موافقة المعلم</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد من المعلم</option>
                            <option value="rejected_by_tutor" {{ request('status') == 'rejected_by_tutor' ? 'selected' : '' }}>مرفوض من المعلم</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل الدفع</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترجع (Refunded)</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-luxury-900 font-bold py-2 px-6 rounded-xl transition-all shadow-md">
                            تصفية
                        </button>
                    </div>
                    @if(request()->filled('status'))
                        <div>
                            <a href="{{ route('admin.bookings.index') }}" class="bg-white/5 hover:bg-white/10 text-white font-bold py-2 px-4 rounded-xl transition-colors inline-block border border-white/10">
                                إلغاء
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            {{-- Table --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">الطالب</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">المعلم</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">النوع/المبلغ</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">تاريخ الجلسة</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">حالة الحجز</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">الدفع</th>
                                    <th class="px-6 py-4 text-center text-xs font-medium text-luxury-400 uppercase">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($bookings as $booking)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-white text-sm">{{ $booking->student->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">{{ $booking->sessionSlot->tutor->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">
                                            {{ $booking->sessionSlot->type === '1-on-1' ? __('site.one_on_one_session') : __('site.group_session') }}
                                            <div class="text-xs text-gold-400 mt-1">{{ $booking->sessionSlot->price > 0 ? $booking->sessionSlot->price . ' ' . App\Models\Setting::get('currency_symbol','$') : 'مجانية' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">
                                            {{ \Carbon\Carbon::parse($booking->sessionSlot->start_time)->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($booking->status === 'confirmed')
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">مؤكد</span>
                                            @elseif($booking->status === 'pending_tutor_approval')
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">يُنتظر المعلم</span>
                                            @elseif($booking->status === 'pending')
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-gray-500/20 text-gray-400">ينتظر الدفع</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400">{{ __('site.'.$booking->status) ?? $booking->status }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($booking->transaction)
                                                @if($booking->transaction->status === 'completed')
                                                    <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400 whitespace-nowrap">اكتمل النقل</span>
                                                @elseif($booking->transaction->status === 'pending')
                                                    <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400 whitespace-nowrap">معلّقة كإيراد</span>
                                                @else
                                                    <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400 whitespace-nowrap">{{ $booking->transaction->status }}</span>
                                                @endif
                                                <br>
                                                <a href="{{ route('admin.transactions.show', $booking->transaction) }}" class="text-xs text-blue-400 hover:text-blue-300 block mt-1">
                                                    {{ $booking->transaction->reference_number }}
                                                </a>
                                            @else
                                                <span class="text-xs text-luxury-500">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex flex-col gap-2 justify-center items-center">
                                                @if($booking->status === 'pending_tutor_approval')
                                                    <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking) }}" class="inline-block">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" onclick="return confirm('تأكيد الموافقة نيابة عن المعلم؟')" class="w-full text-center px-3 py-1.5 rounded bg-green-500/20 text-green-400 hover:bg-green-500/30 text-xs transition">
                                                            موافقة الحجز
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking) }}" class="inline-block">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected_by_tutor">
                                                        <button type="submit" onclick="return confirm('تأكيد الرفض والإلغاء؟')" class="w-full text-center px-3 py-1.5 rounded bg-red-500/20 text-red-400 hover:bg-red-500/30 text-xs transition mt-1">
                                                            رفض
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($booking->status === 'confirmed' && $booking->transaction && $booking->transaction->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.bookings.approvePayment', $booking) }}" class="inline-block">
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('هل أنت متأكد من تأكيد نقل الأموال (البالغة {{ $booking->transaction->tutor_amount }}) لتتاح في رصيد المعلم؟')" class="w-full text-center px-3 py-1.5 rounded bg-gold-500/20 text-gold-400 hover:bg-gold-500/30 text-xs transition font-bold border border-gold-500/30">
                                                            اعتماد الرصيد للمُعلم
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($bookings->hasPages())
                        <div class="p-6 border-t border-white/5">{{ $bookings->links() }}</div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">لا يوجد حجوزات مطابقة.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
