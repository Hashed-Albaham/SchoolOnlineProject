<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.chat.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-white">{{ __('site.conversation_between') }} {{ $user1->name }} & {{ $user2->name }}</h2>
                <p class="text-luxury-400 text-sm">{{ $messages->count() }} {{ __('site.messages') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($messages->isEmpty())
                    <div class="p-12 text-center text-luxury-400">{{ __('site.no_messages_yet') }}</div>
                @else
                    <div class="max-h-[600px] overflow-y-auto p-6 space-y-4">
                        @foreach($messages as $msg)
                            <div class="flex gap-3 {{ $msg->sender_id === $user1->id ? '' : 'flex-row-reverse' }}">
                                    <x-avatar :user="$msg->sender" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                <div class="max-w-[70%]">
                                    <div class="flex items-center gap-2 mb-1 {{ $msg->sender_id !== $user1->id ? 'flex-row-reverse' : '' }}">
                                        <span class="text-xs font-medium {{ $msg->sender_id === $user1->id ? 'text-royal-400' : 'text-gold-400' }}">{{ $msg->sender->name }}</span>
                                        <span class="text-luxury-600 text-xs">{{ $msg->created_at->format('H:i d/m') }}</span>
                                    </div>
                                    <div class="rounded-2xl px-4 py-2.5 {{ $msg->sender_id === $user1->id ? 'bg-royal-500/10 border border-royal-500/20' : 'bg-gold-500/10 border border-gold-500/20' }}">
                                        <p class="text-white text-sm leading-relaxed">{{ $msg->content }}</p>
                                    </div>
                                    <div class="mt-1">
                                        <form action="{{ route('admin.chat.destroyMessage', $msg) }}" method="POST" class="inline"
                                            onsubmit="return confirm('{{ __('site.confirm_delete') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400/60 hover:text-red-400 text-xs transition">
                                                🗑 {{ __('site.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
