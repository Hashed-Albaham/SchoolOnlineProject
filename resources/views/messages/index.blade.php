<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-luxury-800 overflow-hidden shadow-luxury sm:rounded-2xl border border-white/5">
                <div class="grid grid-cols-1 md:grid-cols-4 h-[600px]">

                    <!-- Sidebar (Contacts) -->
                    <div class="md:col-span-1 border-l border-white/5 bg-luxury-900/50">
                        <div class="p-4 border-b border-white/5">
                            <h2 class="text-xl font-bold text-white">المحادثات</h2>
                        </div>

                        <!-- Search Bar -->
                        <div class="p-3 border-b border-white/5">
                            <form action="{{ route('messages.index') }}" method="GET">
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="بحث عن مستخدم..."
                                        class="w-full bg-luxury-800 text-white text-sm rounded-xl border-none focus:ring-1 focus:ring-gold-500 py-2.5 pl-10 pr-4 placeholder-luxury-500">
                                    <button type="submit"
                                        class="absolute left-3 top-2.5 text-luxury-500 hover:text-gold-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if(isset($isSearch) && $isSearch)
                            <div class="px-3 pt-2 pb-1 flex justify-between items-center text-xs text-luxury-400">
                                <span>نتائج البحث</span>
                                <a href="{{ route('messages.index') }}" class="text-gold-400 hover:underline">إلغاء</a>
                            </div>
                        @endif
                        <div class="overflow-y-auto h-[530px] p-2 space-y-2">
                            @forelse($contacts as $contact)
                                <a href="{{ route('messages.show', $contact->id) }}"
                                    class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 {{ isset($user) && $user->id == $contact->id ? 'bg-white/10' : 'hover:bg-white/5' }}">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold">
                                            {{ substr($contact->name, 0, 1) }}
                                        </div>
                                        <!-- Online Status (Optional) -->
                                        <span
                                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-luxury-900 rounded-full"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white truncate">
                                            {{ $contact->name }}
                                        </p>
                                        <p class="text-xs text-luxury-400 truncate">
                                            {{ $contact->role == 'tutor' ? 'معلم' : 'طالب' }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-8 text-luxury-400 text-sm">
                                    لا توجد محادثات سابقة.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="md:col-span-3 bg-luxury-800 flex flex-col">
                        @if(isset($user))
                            <!-- Chat Header -->
                            <div class="p-4 border-b border-white/5 flex items-center gap-3 bg-luxury-900/30">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-white">{{ $user->name }}</h3>
                                    <span
                                        class="text-xs text-luxury-400">{{ $user->role == 'tutor' ? 'معلم' : 'طالب' }}</span>
                                </div>
                            </div>

                            <!-- Chat Component -->
                            <div class="flex-1 flex flex-col overflow-hidden" style="max-height: calc(600px - 73px);">
                                <livewire:chat-box :receiverId="$user->id" wire:key="chat-{{ $user->id }}" />
                            </div>
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-luxury-400">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <p>اختر محادثة للبدء في المراسلة</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>