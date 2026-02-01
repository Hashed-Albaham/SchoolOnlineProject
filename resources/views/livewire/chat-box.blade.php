<div wire:poll.3s="poll" class="flex flex-col h-96 bg-white rounded-lg shadow-sm border">
    <!-- Header -->
    <div class="p-4 border-b bg-gray-50 rounded-t-lg">
        @if($receiver)
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold">{{ substr($receiver->name, 0, 1) }}</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">{{ $receiver->name }}</h4>
                    <p class="text-sm text-gray-500">{{ ucfirst($receiver->role) }}</p>
                </div>
            </div>
        @else
            <h4 class="font-semibold text-gray-900">الدردشة</h4>
        @endif
    </div>

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-messages">
        @forelse($messages as $message)
            <div class="flex {{ $message['sender_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div
                    class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message['sender_id'] == auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                    <p class="text-sm">{{ $message['content'] }}</p>
                    <p
                        class="text-xs mt-1 {{ $message['sender_id'] == auth()->id() ? 'text-indigo-200' : 'text-gray-500' }}">
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                <p class="mt-2">لا توجد رسائل بعد</p>
                <p class="text-sm">ابدأ المحادثة!</p>
            </div>
        @endforelse
    </div>

    <!-- Input -->
    @if($receiverId)
        <div class="p-4 border-t">
            <form wire:submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" wire:model="newMessage" placeholder="اكتب رسالتك..."
                    class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    autocomplete="off">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            @error('newMessage')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endif
</div>

<script>
    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:updated', () => {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>