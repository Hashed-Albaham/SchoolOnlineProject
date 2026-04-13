<div>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-800">{{ __('site.my_sessions') ?? 'جلساتي' }}</h3>
        <p class="text-sm text-gray-500">متابعة الجلسات التي قمت بحجزها وإدارة الدفعات.</p>
    </div>

    @if($bookings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                    <div class="p-5 flex-1">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            @if($booking->sessionSlot->type === '1-on-1')
                                <span class="bg-luxury-100 text-luxury-800 text-xs px-2 py-1 rounded font-semibold">{{ __('site.1_on_1_session') }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded font-semibold">{{ __('site.group_session') }}</span>
                            @endif

                            @if($booking->status === 'confirmed')
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-semibold">{{ __('site.confirmed') }}</span>
                            @elseif($booking->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded font-semibold">{{ __('site.pending_approval') }}</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-semibold">{{ __('site.rejected') }}</span>
                            @endif
                        </div>

                        <!-- Info -->
                        <h4 class="font-bold text-gray-800 mb-2 truncate">
                            {{ $booking->sessionSlot->course ? $booking->sessionSlot->course->title : 'جلسة عامة' }}
                        </h4>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4 mr-1 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="mr-1">{{ $booking->sessionSlot->tutor->name }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4 mr-1 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="mr-1" dir="ltr">{{ \Carbon\Carbon::parse($booking->sessionSlot->start_time)->format('Y-m-d g:i A') }}</span>
                        </div>

                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="bg-gray-50 px-5 py-4 border-t border-gray-100 mt-auto">
                        @if($booking->status === 'pending' && $booking->sessionSlot->price > 0 && !empty($booking->locked_until))
                            @if(\App\Models\Transaction::where('booking_id', $booking->id)->exists())
                                <div class="text-sm text-yellow-700 w-full text-center font-medium">
                                    جاري مراجعة الدفع
                                </div>
                            @else
                                <a href="{{ route('student.sessions.payment', $booking->id) }}" class="block w-full text-center bg-gold-400 hover:bg-gold-500 text-white font-bold py-2 px-4 rounded transition">
                                    💳 إتمام الدفع (ينتهي: {{ \Carbon\Carbon::parse($booking->locked_until)->diffForHumans() }})
                                </a>
                            @endif
                        @elseif($booking->status === 'confirmed')
                            @if($booking->sessionSlot->meeting_link)
                                <a href="{{ $booking->sessionSlot->meeting_link }}" target="_blank" class="block w-full text-center bg-luxury-900 hover:bg-luxury-800 text-white font-bold py-2 px-4 rounded transition">
                                    🔗 رابط الدخول (Zoom)
                                </a>
                            @else
                                <div class="text-sm text-gray-500 w-full text-center py-2">
                                    الرابط غير متوفر بعد
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-gray-50 rounded-xl p-8 text-center border-dashed border-2 border-gray-200">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-gray-500 font-medium">ليس لديك أي جلسات محجوزة</p>
        </div>
    @endif
</div>
