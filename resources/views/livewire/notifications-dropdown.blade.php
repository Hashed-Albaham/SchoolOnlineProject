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
            <h3 class="text-sm font-semibold text-white">{{ __('site.notifications') }}</h3>
            <div class="flex gap-2">
                @if($unreadCount > 0)
                    <button wire:click="markAsRead" class="text-xs text-gold-400 hover:text-gold-300">{{ __('site.mark_all_read') }}</button>
                @endif
            </div>
        </div>

        @if($unreadMessagesCount > 0)
            <div class="px-4 py-2 bg-blue-500/10 border-b border-blue-500/10">
                <a href="{{ route('messages.index') }}" class="flex items-center justify-between text-blue-400 hover:text-blue-300 transition group">
                    <span class="text-xs font-medium"> لديك {{ $unreadMessagesCount }} رسائل غير مقروءة</span>
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        @endif

        @if($unreadMessagesCount > 0)
            <div class="px-4 py-2 bg-blue-500/10 border-b border-blue-500/10">
                <a href="{{ route('messages.index') }}" class="flex items-center justify-between text-blue-400 hover:text-blue-300 transition group">
                    <span class="text-xs font-medium"> لديك {{ $unreadMessagesCount }} رسائل غير مقروءة</span>
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        @endif

        <div class="max-h-96 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-white/5 hover:bg-white/5 transition {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <a href="{{ $notification->data['link'] ?? '#' }}" class="flex gap-3">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'default';
                                $iconBg = match($type) {
                                    'new_message', 'tutor_verification_request' => 'bg-blue-500/20 text-blue-400',
                                    'certificate_issued', 'certificate_request', 'course_submission' => 'bg-gold-500/20 text-gold-400',
                                    'new_enrollment', 'course_approved' => 'bg-green-500/20 text-green-400',
                                    'course_rejected' => 'bg-red-500/20 text-red-400',
                                    default => 'bg-luxury-600 text-luxury-400'
                                };
                            @endphp
                            
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $iconBg }}">
                                @if($type == 'new_message')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                @elseif(in_array($type, ['certificate_issued', 'certificate_request']))
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif(in_array($type, ['new_enrollment', 'course_approved']))
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($type == 'course_rejected')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @elseif($type == 'course_submission')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                @elseif($type == 'tutor_verification_request')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
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
