<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-luxury-800 overflow-hidden shadow-luxury sm:rounded-2xl border border-white/5">
                <div class="p-8">
                    <div class="border-b border-white/5 pb-8 mb-8">
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $quiz->title }}</h2>
                        <p class="text-luxury-400">{{ $quiz->description }}</p>
                        <div class="flex items-center gap-4 mt-4 text-sm font-semibold text-gold-400">
                            <span>⏱️ {{ $quiz->time_limit_minutes ?? '30' }} {{ __('site.minutes') }}</span>
                            <span>🎯 {{ __('site.pass_mark') }} {{ $quiz->pass_percentage }}%</span>
                        </div>
                    </div>

                    <form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST">
                        @csrf
                        <div class="space-y-8">
                            @foreach($quiz->questions as $index => $question)
                                <div class="bg-white/5 p-6 rounded-xl border border-white/5">
                                    <h3 class="text-lg font-bold text-white mb-4">
                                        <span class="text-gold-400 text-xl mx-2">{{ $index + 1 }}.</span>
                                        {{ $question->question_text }}
                                    </h3>

                                    <div class="space-y-3 mr-6">
                                        @foreach($question->options as $option)
                                            <label
                                                class="flex items-center p-3 rounded-lg border border-white/10 hover:bg-white/5 cursor-pointer transition">
                                                <input type="radio" name="q-{{ $question->id }}" value="{{ $option->id }}"
                                                    class="text-gold-500 focus:ring-gold-500 bg-luxury-900 border-white/20">
                                                <span class="mr-3 text-luxury-200">{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-gold-gradient text-luxury-900 font-bold rounded-xl hover:shadow-glow transition transform hover:-translate-y-1">
                                {{ __('site.submit_answers') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>