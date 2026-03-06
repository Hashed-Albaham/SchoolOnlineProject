<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.payment_methods') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.payment_methods_desc') }}</p>
            </div>
            <a href="{{ route('admin.payment_methods.create') }}"
                class="btn-premium px-5 py-2.5 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('site.add_payment_method') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if($paymentMethods->isEmpty())
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-luxury-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-luxury-400 text-lg mb-2">{{ __('site.no_payment_methods') }}</p>
                    <p class="text-luxury-500 text-sm">{{ __('site.add_payment_method_hint') }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($paymentMethods as $method)
                        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 flex items-center gap-4 {{ !$method->is_active ? 'opacity-60' : '' }}">
                            <!-- Icon -->
                            <div class="w-14 h-14 rounded-xl bg-gold-500/10 flex items-center justify-center text-3xl flex-shrink-0">
                                {{ $method->icon ?? '💳' }}
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-white font-bold text-lg">{{ $method->localized_name }}</h3>
                                    @if(!$method->is_active)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-red-500/20 text-red-400">{{ __('site.inactive') }}</span>
                                    @else
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-500/20 text-green-400">{{ __('site.active') }}</span>
                                    @endif
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-royal-500/20 text-royal-400">
                                        @switch($method->type)
                                            @case('bank_transfer') {{ __('site.bank_transfer') }} @break
                                            @case('crypto') {{ __('site.crypto') }} @break
                                            @case('wallet') {{ __('site.wallet') }} @break
                                            @case('cash') {{ __('site.cash') }} @break
                                            @default {{ __('site.other') }}
                                        @endswitch
                                    </span>
                                </div>
                                @if($method->account_number)
                                    <p class="text-luxury-400 text-sm">{{ __('site.account_number') }}: <span class="text-white font-mono">{{ $method->account_number }}</span></p>
                                @endif
                                <p class="text-luxury-500 text-xs mt-1 truncate">{{ Str::limit($method->instructions_ar, 80) }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <!-- Toggle Active -->
                                <form action="{{ route('admin.payment_methods.toggle', $method) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="{{ $method->is_active ? __('site.deactivate') : __('site.activate') }}"
                                        class="p-2 rounded-lg {{ $method->is_active ? 'text-green-400 hover:bg-green-500/10' : 'text-luxury-500 hover:bg-white/5' }} transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $method->is_active ? 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('admin.payment_methods.edit', $method) }}"
                                    class="p-2 rounded-lg text-luxury-400 hover:text-gold-400 hover:bg-white/5 transition" title="{{ __('site.edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.payment_methods.destroy', $method) }}" method="POST"
                                    onsubmit="return confirm('{{ __('site.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-red-400 hover:bg-red-500/10 transition" title="{{ __('site.delete') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
