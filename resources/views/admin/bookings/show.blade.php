<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">تفاصيل الحجز رقم #{{ $booking->id }}</h1>
                    <p class="text-luxury-400 text-sm mt-1">
                        @if($booking->status === 'confirmed')
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-lg">مؤكد</span>
                        @elseif($booking->status === 'pending_tutor_approval')
                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-lg">ينتظر المعلم</span>
                        @else
                            <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-lg">{{ $booking->status }}</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="text-luxury-400 hover:text-white transition">العودة ←</a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8 space-y-6">
                <!-- Info Section -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-luxury-500 text-sm">الطالب</p>
                        <p class="text-white font-bold">{{ $booking->student->name ?? 'غير متوفر' }}</p>
                    </div>
                    <div>
                        <p class="text-luxury-500 text-sm">الجلسة</p>
                        <p class="text-white font-bold">{{ $booking->sessionSlot->type === '1-on-1' ? 'جلسة فردية' : 'مجموعة' }}</p>
                    </div>
                    <div>
                        <p class="text-luxury-500 text-sm">المعلم</p>
                        <p class="text-white font-bold">{{ $booking->sessionSlot->tutor->name ?? 'غير متوفر' }}</p>
                    </div>
                    <div>
                        <p class="text-luxury-500 text-sm">وقت الجلسة</p>
                        <p class="text-white font-bold">{{ \Carbon\Carbon::parse($booking->sessionSlot->start_time)->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <hr class="border-white/10">

                <div>
                    <h3 class="text-lg font-bold text-white mb-4">المعاملة المالية</h3>
                    @if($booking->transaction)
                        <div class="bg-luxury-900 border border-white/5 p-4 rounded-xl">
                            <p class="text-sm text-luxury-300">رقم المرجع: <a href="{{ route('admin.transactions.show', $booking->transaction) }}" class="text-blue-400 font-bold hover:underline">{{ $booking->transaction->reference_number }}</a></p>
                            <p class="text-sm text-luxury-300 mt-2">المبلغ: <span class="text-gold-400 font-bold">{{ $booking->transaction->gross_amount }}</span></p>
                            <p class="text-sm text-luxury-300 mt-2">عمولة المنصة: <span class="text-red-400 font-bold">{{ $booking->transaction->platform_fee_amount }}</span></p>
                            <p class="text-sm text-luxury-300 mt-2">صافي المعلم: <span class="text-green-400 font-bold">{{ $booking->transaction->tutor_amount }}</span></p>
                            
                            <div class="mt-4">
                                @if($booking->transaction->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400 whitespace-nowrap">معلّقة كإيراد</span>
                                @elseif($booking->transaction->status === 'completed')
                                    <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400 whitespace-nowrap">مكتملة والمبلغ متاح للمعلم</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400 whitespace-nowrap">{{ $booking->transaction->status }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-luxury-400">لا يوجد بيانات مالية مرتبطة (ربما تكون جلسة مجانية).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
