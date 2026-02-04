<div wire:poll.3s="poll" class="flex flex-col h-full bg-transparent overflow-hidden">
    
    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 custom-scrollbar" id="chat-messages">
        
        <!-- Welcome/Start Message (Optional) -->
        <div class="flex justify-center pb-4">
            <span class="px-3 py-1 rounded-full bg-white/5 text-[10px] text-luxury-500">{{ now()->format('d M Y') }}</span>
        </div>

        @forelse($messages as $message)
            @php
                $isMe = $message['sender_id'] == auth()->id();
            @endphp
            <div class="flex {{ $isMe ? 'justify-start' : 'justify-end' }} group animate-fade-in-up">
                <div class="flex flex-col {{ $isMe ? 'items-start' : 'items-end' }} max-w-[85%] sm:max-w-[75%]">

                    <div class="flex items-end gap-2 {{ $isMe ? 'flex-row' : 'flex-row-reverse' }}">
                        <!-- Avatar -->
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold border border-white/10 shadow-sm
                                    {{ $isMe ? 'bg-gold-500/10 text-gold-400' : 'bg-royal-500/10 text-royal-400' }}">
                            {{ substr($message['sender']['name'] ?? 'U', 0, 1) }}
                        </div>

                        <div class="relative px-5 py-3.5 text-sm leading-relaxed shadow-md transition-all duration-300
                                    {{ $isMe
                                        ? 'bg-gradient-to-br from-royal-600 to-royal-700 text-white rounded-2xl rounded-tr-none shadow-royal-900/20'
                                        : 'bg-luxury-700/80 backdrop-blur-sm border border-white/5 text-luxury-100 rounded-2xl rounded-tl-none shadow-black/10' }}">
                            {{ $message['content'] }}
                            
                            <!-- Timestamp & Status inside bubbles for cleaner look -->
                            <div class="flex items-center gap-1 mt-1 {{ $isMe ? 'justify-end text-royal-200/70' : 'justify-start text-luxury-400/70' }}">
                                <span class="text-[10px]">{{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}</span>
                                @if($isMe)
                                    @if($message['is_read'])
                                        <svg class="w-3 h-3 text-gold-400" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="h-full flex flex-col items-center justify-center text-luxury-500 space-y-6 opacity-60">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gold-500/20 rounded-full blur-xl"></div>
                    <div class="w-20 h-20 rounded-full bg-luxury-800 border border-white/10 flex items-center justify-center relative shadow-2xl">
                        <svg class="w-10 h-10 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-lg font-semibold text-white">{{ __('site.no_messages') }}</p>
                    <p class="text-xs text-luxury-400 mt-1">{{ __('site.start_conversation_now') }}</p>
                </div>
            </div>
        @endforelse
        
        <!-- Invisible element to scroll to -->
        <div id="scroll-anchor"></div>
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-luxury-900 border-t border-white/5 backdrop-blur-md">
        <form wire:submit.prevent="sendMessage" class="relative flex items-end gap-3 max-w-4xl mx-auto">
            
            <!-- Attachment Button (Placeholder) -->
            <button type="button" class="p-3 rounded-xl text-luxury-400 hover:text-white hover:bg-white/5 transition mb-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
            </button>

            <div class="flex-1 relative">
                <textarea wire:model="newMessage" 
                    placeholder="{{ __('site.type_message') }}"
                    rows="1"
                    class="w-full pl-16 pr-5 py-3.5 rounded-2xl bg-luxury-800 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50 transition shadow-inner resize-none custom-scrollbar overflow-y-auto"
                    style="min-height: 52px; max-height: 120px;"
                    oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"
                    autocomplete="off"></textarea>
                    
                <!-- Send Button Inside Input -->
                <button type="submit"
                    class="absolute left-2 bottom-2 p-2 rounded-xl bg-gold-gradient text-luxury-900 shadow-lg hover:shadow-gold-500/20 hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:hover:scale-100"
                    wire:loading.attr="disabled" wire:target="sendMessage">
                    <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5 rtl:-rotate-90 transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </button>
            </div>
        </form>
        @error('newMessage')
            <p class="text-red-400 text-xs mt-2 text-center">{{ $message }}</p>
        @enderror
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
        .animate-fade-in-up { animation: fadeInUp 0.3s ease-out; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const chatContainer = document.getElementById('chat-messages');
            const scrollAnchor = document.getElementById('scroll-anchor');

            const scrollBottom = (smooth = false) => {
                if (scrollAnchor) {
                    scrollAnchor.scrollIntoView({ behavior: smooth ? 'smooth' : 'auto' });
                }
            };

            // Initial scroll
            scrollBottom();

            // Scroll on new message
            Livewire.on('messageSent', () => {
                setTimeout(() => scrollBottom(true), 100);
            });

            // Prevent scroll jump when polling if user scrolled up
            let isAutoScrollEnabled = true;
            if(chatContainer) {
                chatContainer.addEventListener('scroll', () => {
                    const threshold = 100;
                    const position = chatContainer.scrollTop + chatContainer.clientHeight;
                    const height = chatContainer.scrollHeight;
                    isAutoScrollEnabled = position > height - threshold;
                });
            }

            // Observe DOM changes to auto-scroll if already at bottom
            const observer = new MutationObserver(() => {
                if (isAutoScrollEnabled) {
                    scrollBottom(true);
                }
            });

            if(chatContainer) {
                observer.observe(chatContainer, { childList: true, subtree: true });
            }
        });
    </script>
</div>