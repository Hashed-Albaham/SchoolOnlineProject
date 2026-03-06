<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.payment_methods.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-white">{{ __('site.edit_payment_method') }}: {{ $paymentMethod->localized_name }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form method="POST" action="{{ route('admin.payment_methods.update', $paymentMethod) }}">
                    @csrf @method('PUT')
                    @include('admin.payment_methods._form')
                    <div class="flex items-center gap-4 pt-4 mt-6 border-t border-white/5">
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-luxury-900 font-semibold hover:from-gold-400 hover:to-gold-500 transition shadow-lg">
                            {{ __('site.save_changes') }}
                        </button>
                        <a href="{{ route('admin.payment_methods.index') }}" class="text-luxury-400 hover:text-white transition">{{ __('site.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
