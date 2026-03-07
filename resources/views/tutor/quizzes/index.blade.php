<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('tutor.courses.edit', $course) }}"
                    class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ __('site.course_quizzes') }}</h2>
                    <p class="text-luxury-400 text-sm mt-1">{{ $course->title }}</p>
                </div>
            </div>
            <a href="{{ route('tutor.courses.quizzes.create', $course) }}"
                class="px-4 py-2 bg-gold-gradient text-luxury-900 rounded-lg font-bold hover:shadow-glow transition">
                + {{ __('site.create_new_quiz') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($quizzes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-white/5 border-b border-white/10 text-luxury-300">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">{{ __('site.quiz_title') }}</th>
                                    <th class="px-6 py-4 font-semibold">{{ __('site.questions_count') }}</th>
                                    <th class="px-6 py-4 font-semibold">{{ __('site.duration_minutes') }}</th>
                                    <th class="px-6 py-4 font-semibold">{{ __('site.pass_percentage_label') }}</th>
                                    <th class="px-6 py-4 font-semibold">{{ __('site.attempts') }}</th>
                                    <th class="px-6 py-4 font-semibold">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($quizzes as $quiz)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-white font-medium">{{ $quiz->title }}</td>
                                        <td class="px-6 py-4 text-luxury-400">{{ $quiz->questions_count }}</td>
                                        <td class="px-6 py-4 text-luxury-400">{{ $quiz->time_limit_minutes ?? __('site.unlimited') }}</td>
                                        <td class="px-6 py-4 text-luxury-400">{{ $quiz->pass_percentage }}%</td>
                                        <td class="px-6 py-4 text-luxury-400">{{ $quiz->max_attempts ?? __('site.unlimited') }}</td>
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <a href="{{ route('tutor.courses.quizzes.builder', [$course, $quiz]) }}"
                                                class="text-blue-400 hover:text-blue-300 text-sm">{{ __('site.the_questions') }}</a>
                                            <a href="{{ route('tutor.courses.quizzes.results', [$course, $quiz]) }}"
                                                class="text-green-400 hover:text-green-300 text-sm">{{ __('site.result') }}</a>
                                            <a href="{{ route('tutor.courses.quizzes.edit', [$course, $quiz]) }}"
                                                class="text-gold-400 hover:text-gold-300 text-sm">{{ __('site.quiz_settings') }}</a>
                                            <form action="{{ route('tutor.courses.quizzes.destroy', [$course, $quiz]) }}"
                                                method="POST" onsubmit="return confirm('{{ __('site.are_you_sure') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-300 text-sm">{{ __('site.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center text-luxury-400">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-lg">{{ __('site.no_quizzes_added_yet') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>