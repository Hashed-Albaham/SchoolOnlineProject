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

                <div class="p-6 sm:p-8">
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('student.sessions.payment.process', $booking) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.select_payment_method') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse($paymentMethods as $method)
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                        <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="sr-only peer" required>
                                        <div class="flex flex-1">
                                            <div class="flex flex-col">
                                                <span class="block text-sm font-medium text-gray-900 peer-checked:text-indigo-600">
                                                    {{ app()->getLocale() == 'ar' && $method->name_ar ? $method->name_ar : $method->name }}
                                                </span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">
                                                    {{ $method->details }}
                                                </span>
                                            </div>
                                        </div>
                                        <svg class="h-5 w-5 text-indigo-600 opacity-0 peer-checked:opacity-100 absolute top-4 right-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="absolute -inset-px rounded-lg border-2 pointer-events-none border-transparent peer-checked:border-indigo-600" aria-hidden="true"></div>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-2">{{ __('site.no_payment_methods') }}</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.upload_payment_proof') }}</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-gray-50">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="payment_proof" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>{{ __('site.choose_file') }}</span>
                                            <input id="payment_proof" name="payment_proof" type="file" class="sr-only" required accept="image/*">
                                        </label>
                                        <p class="pl-1">{{ __('site.or_drag_and_drop') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, JPEG up to 2MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('student.sessions.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ __('site.cancel') }}
                            </a>
                            <button type="submit" class="px-8 py-3 border border-transparent rounded-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transition-all">
                                {{ __('site.submit_payment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
