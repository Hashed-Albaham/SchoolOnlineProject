<div wire:poll.3s="poll" class="flex flex-col h-full bg-luxury-800/50">
    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
        @forelse($messages as $message)
            <div class="flex {{ $message['sender_id'] == auth()->id() ? 'justify-start' : 'justify-end' }}">
                <div
                    class="flex flex-col {{ $message['sender_id'] == auth()->id() ? 'items-start' : 'items-end' }} max-w-[80%]">
                    <div
                        class="px-4 py-2 rounded-2xl text-sm {{ $message['sender_id'] == auth()->id() ? 'bg-royal-600 text-white rounded-tr-none' : 'bg-luxury-700 text-luxury-100 rounded-tl-none' }}">
                        {{ $message['content'] }}
                    </div>
                    <span class="text-[10px] text-luxury-500 mt-1 px-1">
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                    </span>
                </div>
            </div>
        @empty
            <div class="h-full flex flex-col items-center justify-center text-luxury-500 space-y-2">
                <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                <p class="text-sm">لا توجد رسائل بعد.. ابدأ المحادثة الآن!</p>
            </div>
        @endforelse
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-luxury-900/50 border-t border-white/5">
        <form wire:submit.prevent="sendMessage" class="flex gap-2">
            <input type="text" wire:model="newMessage" placeholder="اكتب رسالتك هنا..."
                class="flex-1 bg-luxury-700 border-none text-white placeholder-luxury-400 rounded-xl focus:ring-1 focus:ring-gold-500/50 text-sm py-2.5"
                autocomplete="off">
            <button type="submit"
                class="w-10 h-10 rounded-xl bg-gold-gradient flex items-center justify-center text-luxury-900 hover:shadow-glow transition-all duration-300 disabled:opacity-50"
                wire:loading.attr="disabled">
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </form>
        @error('newMessage')
            <p class="text-red-400 text-[10px] mt-1 mr-1">{{ $message }}</p>
        @enderror
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollBottom = () => {
                const chatContainer = document.getElementById('chat-messages');
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            };

            scrollBottom();

            Livewire.on('messageSent', () => {
                setTimeout(scrollBottom, 100);
            });

            // Poll manual scroll
            setInterval(() => {
                if (window.livewire_scrolling) return;
                // scrollBottom();
            }, 5000);
        });
    </script>
</div>