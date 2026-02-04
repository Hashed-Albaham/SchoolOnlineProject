<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2 text-luxury-300 hover:text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border border-luxury-900 animate-pulse"></span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute left-0 mt-2 w-80 bg-luxury-800 border border-white/10 rounded-xl shadow-xl z-50 overflow-hidden"
         style="display: none;">
        
        <div class="px-4 py-3 border-b border-white/5 bg-white/5 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-white">الإشعارات</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <a href="{{ route('notifications.markAsRead') }}" class="text-xs text-gold-400 hover:text-gold-300">تحديد الكل كمقروء</a>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse(auth()->user()->notifications as $notification)
                <div class="p-4 border-b border-white/5 hover:bg-white/5 transition {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <a href="{{ $notification->data['link'] ?? '#' }}" class="flex gap-3">
                        <div class="flex-shrink-0">
                            @if(($notification->data['icon'] ?? '') == 'certificate')
                                <div class="w-10 h-10 rounded-full bg-gold-500/20 flex items-center justify-center text-gold-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @elseif(($notification->data['icon'] ?? '') == 'message')
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-luxury-600 flex items-center justify-center text-luxury-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $notification->data['title'] ?? 'إشعار جديد' }}</p>
                            <p class="text-xs text-luxury-400 mt-1 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-luxury-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="py-8 text-center text-luxury-500 text-sm">
                    لا توجد إشعارات حالياً
                </div>
            @endforelse
        </div>
    </div>
</div>
