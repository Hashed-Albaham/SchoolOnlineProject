<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('site.quiz_certificate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    {{-- Certificate Header --}}
                    <div class="border-4 border-double border-yellow-500 p-8 rounded-lg">
                        <div class="mb-6">
                            <h1 class="text-3xl font-bold text-yellow-600 mb-2">🏆 {{ __('site.certificate_of_completion') }}</h1>
                            <p class="text-gray-500">{{ __('site.quiz_certificate') }}</p>
                        </div>

                        <div class="my-8">
                            <p class="text-lg text-gray-600 mb-2">{{ __('site.this_certifies_that') }}</p>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $attempt->user->name ?? '-' }}</h2>
                            <p class="text-gray-600 mb-2">{{ __('site.has_completed_quiz') }}</p>
                            <h3 class="text-xl font-semibold text-blue-600 mb-4">{{ $attempt->quiz->title ?? '-' }}</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto text-sm text-gray-600">
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="block font-semibold">{{ __('site.score') }}</span>
                                <span class="text-lg text-green-600 font-bold">{{ $attempt->score }}%</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="block font-semibold">{{ __('site.date') }}</span>
                                <span>{{ $attempt->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>

                        @if($attempt->quiz && $attempt->quiz->course)
                        <div class="mt-6 text-gray-500 text-sm">
                            <p>{{ __('site.course') }}: {{ $attempt->quiz->course->title }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
