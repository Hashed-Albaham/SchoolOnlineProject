<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.complete_booking_payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6 sm:p-8 bg-luxury-900 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold mb-1">{{ __('site.session_booking') }}</h3>
                        <p class="text-luxury-300">
                            {{ $booking->sessionSlot->type === '1-on-1' ? __('site.one_on_one_session') : __('site.group_session') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-luxury-300 uppercase tracking-widest">{{ __('site.total_amount') }}</p>
                        <p class="text-3xl font-bold text-gold-400">
                            {{ $booking->sessionSlot->price }} {{ \App\Models\Setting::get('currency_symbol', '$') }}
                        </p>
                    </div>
                </div>

                <div class="p-6 sm:p-8 bg-gray-50 border-b border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ __('site.payment_instructions') }}</h4>
                    <p class="text-gray-600 mb-4">
                        {{ \App\Models\Setting::get('payment_instructions', __('site.payment_instructions_default')) }}
                    </p>
                </div>

                    <!-- Simulated Payment Form -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6 mt-6">
                        <p class="text-sm text-yellow-800">
                            {{ __('site.payment_simulation_note') }}
                        </p>
                    </div>

                    <form action="{{ route('student.sessions.payment.process', $booking) }}" method="POST">
                        @csrf
                        
                        <!-- Fake Card Input -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.card_number_display') }}</label>
                            <input type="text" value="4242 4242 4242 4242" disabled
                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.expiration_date') }}</label>
                                <input type="text" value="12/28" disabled
                                    class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                <input type="text" value="123" disabled
                                    class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                            {{ __('site.confirm_payment_sim') }}
                        </button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('student.sessions.index') }}"
                            class="text-gray-600 hover:text-gray-800 text-sm">
                            {{ __('site.cancel_and_return') }}
                        </a>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
