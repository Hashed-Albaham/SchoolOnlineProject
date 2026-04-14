<div>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-white">{{ __('site.my_sessions') ?? 'جلساتي' }}</h3>
        <p class="text-sm text-luxury-400">متابعة الجلسات التي قمت بحجزها وإدارة الدفعات.</p>
    </div>

    @if($bookings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookings as $booking)
                @if(!$booking->sessionSlot)
                    @continue
                @endif
                <div class="bg-luxury-800/50 backdrop-blur-xl rounded-2xl shadow-lg border border-white/5 overflow-hidden hover:-translate-y-1 hover:shadow-xl hover:shadow-black/50 transition-all duration-300 flex flex-col">
                    <div class="p-5 flex-1">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            @if($booking->sessionSlot->type === '1-on-1')
                                <span class="bg-luxury-500/20 text-luxury-300 border border-luxury-500/30 text-xs px-2 py-1 rounded-md font-semibold">{{ __('site.1_on_1_session') }}</span>
                            @else
                                <span class="bg-white/10 text-white border border-white/20 text-xs px-2 py-1 rounded-md font-semibold">{{ __('site.group_session') }}</span>
                            @endif

                            @if($booking->status === 'confirmed')
                                <span class="bg-green-500/10 text-green-400 border border-green-500/20 text-xs px-2 py-1 rounded-md font-semibold">{{ __('site.confirmed') }}</span>
                            @elseif($booking->status === 'pending')
                                <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-xs px-2 py-1 rounded-md font-semibold">{{ __('site.pending_approval') }}</span>
                            @else
                                <span class="bg-red-500/10 text-red-400 border border-red-500/20 text-xs px-2 py-1 rounded-md font-semibold">{{ __('site.rejected') }}</span>
                            @endif
                        </div>

                        <!-- Info -->
                        <h4 class="font-bold text-white mb-3 text-lg leading-tight line-clamp-2">
                            {{ $booking->sessionSlot->course ? $booking->sessionSlot->course->title : 'جلسة عامة' }}
                        </h4>
                        
                        <div class="flex items-center text-sm text-luxury-300 mb-2.5">
                            <svg class="w-4 h-4 ml-2 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>{{ $booking->sessionSlot->tutor->name }}</span>
                        </div>

                        <div class="flex items-center text-sm text-luxury-300 mb-2">
                            <svg class="w-4 h-4 ml-2 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span dir="ltr">{{ \Carbon\Carbon::parse($booking->sessionSlot->start_time)->format('Y-m-d g:i A') }}</span>
                        </div>

                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="bg-luxury-900/50 px-5 py-4 border-t border-white/5 mt-auto">
                        @if($booking->status === 'pending' && $booking->sessionSlot->price > 0 && !empty($booking->locked_until))
                            @if(\App\Models\Transaction::where('booking_id', $booking->id)->exists())
                                <div class="text-sm text-yellow-400 w-full text-center font-medium bg-yellow-500/10 py-2 rounded-lg border border-yellow-500/20">
                                    جاري مراجعة الدفع
                                </div>
                            @else
                                <a href="{{ route('student.sessions.payment', $booking->id) }}" class="block w-full text-center bg-gold-gradient text-luxury-900 font-bold py-2.5 px-4 rounded-xl transition-all shadow-glow hover:scale-[1.02]">
                                    💳 إتمام الدفع (ينتهي: {{ \Carbon\Carbon::parse($booking->locked_until)->diffForHumans() }})
                                </a>
                            @endif
                        @elseif($booking->status === 'confirmed')
                            @if($booking->sessionSlot->meeting_link)
                                <a href="{{ $booking->sessionSlot->meeting_link }}" target="_blank" class="block w-full text-center bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-md">
                                    🔗 رابط الدخول للمنصة
                                </a>
                            @else
                                <div class="text-sm text-luxury-500 w-full text-center py-2 bg-black/20 rounded-lg">
                                    الرابط غير متوفر بعد
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-luxury-800/20 rounded-2xl p-12 text-center border-dashed border-2 border-white/10">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-luxury-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-luxury-400 font-medium text-lg">ليس لديك أي جلسات محجوزة</p>
        </div>
    @endif
</div>
