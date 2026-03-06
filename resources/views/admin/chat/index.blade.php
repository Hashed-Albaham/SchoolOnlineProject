<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.chat_oversight') }}</h2>
        <p class="text-luxury-400 text-sm mt-1">{{ __('site.chat_oversight_desc') }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-luxury-800/50 border border-white/5 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['total_messages']) }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.total_messages') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-white/5 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-royal-400">{{ number_format($stats['total_conversations']) }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.total_conversations') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-gold-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-gold-400">{{ $stats['today_messages'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.today_messages') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-green-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-green-400">{{ $stats['active_chatters'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.active_chatters') }}</p>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.all_conversations') }}</h3>
                </div>

                @if($conversations->isEmpty())
                    <div class="p-12 text-center text-luxury-400">{{ __('site.no_conversations') }}</div>
                @else
                    <div class="divide-y divide-white/5">
                        @foreach($conversations as $conv)
                            @php
                                $u1 = $users[$conv->user1_id] ?? null;
                                $u2 = $users[$conv->user2_id] ?? null;
                                $lastMsg = $lastMessages[$conv->last_message_id] ?? null;
                            @endphp
                            @if($u1 && $u2)
                            <a href="{{ route('admin.chat.show', [$u1, $u2]) }}"
                                class="flex items-center gap-4 p-5 hover:bg-white/5 transition">
                                <!-- User avatars -->
                                        <div class="relative z-10 p-0.5 bg-luxury-800 rounded-full">
                                            <x-avatar :user="$u1" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                        </div>
                                        <div class="relative -ml-4 z-0 p-0.5 bg-luxury-800 rounded-full">
                                            <x-avatar :user="$u2" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                        </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-white font-medium text-sm">{{ $u1->name }}</span>
                                        <svg class="w-4 h-4 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span class="text-white font-medium text-sm">{{ $u2->name }}</span>
                                    </div>
                                    @if($lastMsg)
                                        <p class="text-luxury-500 text-xs truncate">{{ Str::limit($lastMsg->content, 60) }}</p>
                                    @endif
                                </div>

                                <!-- Meta -->
                                <div class="text-left flex-shrink-0">
                                    <span class="text-gold-400 font-bold text-sm">{{ $conv->message_count }}</span>
                                    <span class="text-luxury-500 text-xs">{{ __('site.messages') }}</span>
                                    @if($lastMsg)
                                        <p class="text-luxury-500 text-xs mt-0.5">{{ $lastMsg->created_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </a>
                            @endif
                        @endforeach
                    </div>
                    <div class="p-4 border-t border-white/5">
                        {{ $conversations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
