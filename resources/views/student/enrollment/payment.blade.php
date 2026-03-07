<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.complete_payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Course Info -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-lg font-semibold mb-2">{{ $enrollment->course->title }}</h3>
                        <p class="text-sm text-gray-500">{{ __('site.by_tutor', ['name' => $enrollment->course->tutor->name]) }}</p>
                    </div>

                    <!-- Price Summary -->
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">{{ __('site.course_price') }}</span>
                            <span class="font-medium">${{ $enrollment->course->price }}</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>{{ __('site.total') }}</span>
                            <span class="text-indigo-600">${{ $enrollment->course->price }}</span>
                        </div>
                    </div>

                    <!-- Simulated Payment Form -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                        <p class="text-sm text-yellow-800">
                            {{ __('site.payment_simulation_note') }}
                        </p>
                    </div>

                    <form action="{{ route('student.enrollment.payment.process', $enrollment) }}" method="POST">
                        @csrf

                        <!-- Fake Card Input -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.card_number_display') }}</label>
                            <input type="text" value="4242 4242 4242 4242" disabled
                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.expiration_date') }}</label>
                                <input type="text" value="12/28" disabled
                                    class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                <input type="text" value="123" disabled
                                    class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                            {{ __('site.confirm_payment_sim') }}
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="{{ route('student.courses.show', $enrollment->course) }}"
                            class="text-gray-600 hover:text-gray-800 text-sm">
                            {{ __('site.cancel_and_return') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>