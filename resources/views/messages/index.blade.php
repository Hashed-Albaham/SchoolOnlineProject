<x-app-layout>
    <div class="h-[calc(100vh-65px)] bg-luxury-900 flex overflow-hidden">

        <!-- Sidebar (Contacts) -->
        <!-- Logic: On mobile, hide if user is selected (showing chat). On desktop, always show. -->
        <div class="w-full md:w-80 lg:w-96 flex-shrink-0 bg-luxury-900 border-l border-white/5 flex flex-col transition-all duration-300
                    {{ isset($user) ? 'hidden md:flex' : 'flex' }}">

            <!-- Header -->
            <div class="p-4 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">{{ __('site.messages') }}</h2>
                <div class="flex gap-2">
                    <!-- Optional: Add New Message Button -->
                </div>
            </div>

            <!-- Search Bar -->
            <div class="p-4 border-b border-white/5 bg-luxury-800/50">
                <form action="{{ route('messages.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('site.search_people') }}"
                            class="w-full bg-luxury-900 text-white text-sm rounded-xl border border-white/10 focus:ring-1 focus:ring-gold-500 py-3 pl-10 pr-4 placeholder-luxury-500 transition-all">
                        <button type="submit" class="absolute left-3 top-3 text-luxury-500 hover:text-gold-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contacts List -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-2">
                @if(isset($isSearch) && $isSearch)
                    <div class="px-2 pb-2 flex justify-between items-center text-xs text-luxury-400">
                        <span>{{ __('site.search_results') }}</span>
                        <a href="{{ route('messages.index') }}"
                            class="text-gold-400 hover:underline">{{ __('site.cancel') }}</a>
                    </div>
                @endif

                @forelse($contacts as $contact)
                            @php
                                $unread = isset($unreadCounts) ? ($unreadCounts[$contact->id] ?? 0) : 0;
                                $lastMsg = isset($lastMessages) ? ($lastMessages[$contact->id] ?? null) : null;
                            @endphp
                            <a href="{{ route('messages.show', $contact->id) }}" class="flex items-center gap-4 p-3 rounded-xl transition-all duration-200 group
                                           {{ isset($user) && $user->id == $contact->id
                    ? 'bg-gold-500/10 border border-gold-500/20'
                    : ($unread > 0 ? 'bg-blue-500/5 border border-blue-500/10 hover:bg-blue-500/10' : 'hover:bg-white/5 border border-transparent') }}">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-105 transition-transform">
                                        {{ substr($contact->name, 0, 1) }}
                                    </div>
                                    @if($unread > 0)
                                        <span class="absolute -top-1 {{ app()->getLocale() === 'ar' ? '-left-1' : '-right-1' }} w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-[10px] text-white font-bold border-2 border-luxury-900">
                                            {{ $unread > 9 ? '9+' : $unread }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="text-sm font-bold {{ $unread > 0 ? 'text-white' : 'text-luxury-300' }} truncate group-hover:text-gold-400 transition-colors">
                                            {{ $contact->name }}
                                        </h3>
                                        @if($lastMsg)
                                            <span class="text-[10px] {{ $unread > 0 ? 'text-blue-400' : 'text-luxury-500' }}">
                                                {{ $lastMsg->created_at->diffForHumans(null, true, true) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($lastMsg)
                                        <p class="text-xs {{ $unread > 0 ? 'text-luxury-300 font-medium' : 'text-luxury-500' }} truncate">
                                            @if($lastMsg->sender_id === auth()->id())
                                                <span class="text-luxury-500">{{ __('site.you') }}: </span>
                                            @endif
                                            {{ Str::limit($lastMsg->content, 40) }}
                                        </p>
                                    @else
                                        <p class="text-xs text-luxury-500 truncate">
                                            {{ $contact->role == 'tutor' ? __('site.tutor') : ($contact->role == 'admin' ? __('site.admin') : __('site.student')) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-luxury-400">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm">{{ __('site.no_contacts') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <!-- Logic: On mobile, hide if NO user is selected. On desktop, always show (placeholder or chat). -->
        <div class="flex-1 flex flex-col bg-luxury-800 relative
                    {{ isset($user) ? 'flex' : 'hidden md:flex' }}">

            @if(isset($user))
                <!-- Chat Header -->
                <div
                    class="h-auto min-h-[70px] px-6 py-3 border-b border-white/5 bg-luxury-900/80 backdrop-blur-md flex items-center justify-between z-10 shadow-sm">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Back Button -->
                        <a href="{{ route('messages.index') }}"
                            class="md:hidden p-2 -mr-2 text-luxury-400 hover:text-white transition">
                            <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                        </a>

                        <div class="relative">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-luxury-900 rounded-full"></span>
                        </div>

                        <div>
                            <h3 class="font-bold text-white text-base">{{ $user->name }}</h3>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-xs text-luxury-400">{{ $user->role == 'tutor' ? __('site.tutor') : __('site.student') }}</span>
                                <span class="w-1 h-1 rounded-full bg-luxury-600"></span>
                                <span class="text-xs text-green-400">{{ __('site.online') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <button class="p-2 text-luxury-400 hover:text-gold-400 hover:bg-gold-500/10 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Chat Component Container -->
                <!-- We pass height-full to let the Livewire component manage internal scrolling -->
                <div class="flex-1 overflow-hidden relative">
                    <livewire:chat-box :receiverId="$user->id" wire:key="chat-{{ $user->id }}" />
                </div>
            @else
                <!-- Empty State -->
                <div class="flex-1 flex flex-col items-center justify-center text-luxury-400 bg-luxury-900/50">
                    <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mb-6 animate-pulse">
                        <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('site.welcome2') }}!</h3>
                    <p class="text-luxury-500">{{ __('site.select_conversation') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>