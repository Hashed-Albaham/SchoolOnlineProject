<div class="relative" x-data="{ open: false }" @click.away="open = false" wire:poll.10s>
    <button @click="open = !open" class="relative p-2 text-luxury-300 hover:text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        @if($unreadCount > 0)
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
         class="absolute left-0 rtl:left-0 ltr:right-0 mt-2 w-80 bg-luxury-800 border border-white/10 rounded-xl shadow-xl z-50 overflow-hidden"
         style="display: none;">
        
        <div class="px-4 py-3 border-b border-white/5 bg-white/5 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-white">{{ __('site.notifications') ?? 'الإشعارات' }}</h3>
            @if($unreadCount > 0)
                <button wire:click="markAsRead" class="text-xs text-gold-400 hover:text-gold-300">{{ __('site.mark_all_read') ?? 'تحديد الكل كمقروء' }}</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-white/5 hover:bg-white/5 transition {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <a href="{{ $notification->data['link'] ?? '#' }}" class="flex gap-3">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'default';
                                $iconBg = match($type) {
                                    'new_message' => 'bg-blue-500/20 text-blue-400',
                                    'certificate_issued', 'certificate_request' => 'bg-gold-500/20 text-gold-400',
                                    'new_enrollment' => 'bg-green-500/20 text-green-400',
                                    default => 'bg-luxury-600 text-luxury-400'
                                };
                            @endphp
                            
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $iconBg }}">
                                @if($type == 'new_message')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                @elseif(in_array($type, ['certificate_issued', 'certificate_request']))
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($type == 'new_enrollment')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">{{ $notification->data['title'] ?? 'إشعار جديد' }}</p>
                            <p class="text-xs text-luxury-400 mt-1 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-luxury-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="py-12 text-center text-luxury-500">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <p class="text-sm">{{ __('site.no_notifications') ?? 'لا توجد إشعارات حالياً' }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
