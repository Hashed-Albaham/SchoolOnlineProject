<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-luxury-800 overflow-hidden shadow-luxury sm:rounded-2xl border border-white/5 text-center p-12">

                @if($attempt->passed)
                    <div class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-2">مبروك! لقد نجحت</h2>
                    <p class="text-luxury-400 mb-8">لقد اجتزت اختبار {{ $quiz->title }} بنجاح.</p>
                @else
                    <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-2">للأسف، لم تنجح</h2>
                    <p class="text-luxury-400 mb-8">حاول مرة أخرى لتحقيق الدرجة المطلوبة.</p>
                @endif

                <div class="bg-luxury-900/50 rounded-xl p-6 max-w-sm mx-auto mb-8 border border-white/5">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <span class="block text-sm text-luxury-500 mb-1">الدرجة</span>
                            <span class="text-2xl font-bold text-white">{{ $attempt->score }}</span>
                        </div>
                        <div class="text-center border-r border-white/10">
                            <span class="block text-sm text-luxury-500 mb-1">النتيجة</span>
                            <span class="text-2xl font-bold {{ $attempt->passed ? 'text-green-400' : 'text-red-400' }}">
                                {{ $attempt->passed ? 'ناجح' : 'راسب' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('student.courses.show', $quiz->course_id) }}"
                        class="px-6 py-2 border border-white/10 rounded-lg text-luxury-300 hover:bg-white/5 transition">
                        العودة للكورس
                    </a>
                    @if($attempt->passed)
                        <a href="{{ route('certificates.show', $attempt) }}" target="_blank"
                            class="px-6 py-2 bg-gold-gradient text-luxury-900 font-bold rounded-lg hover:shadow-glow transition">
                            تحميل الشهادة
                        </a>
                    @else
                        <a href="{{ route('student.quizzes.show', $quiz) }}"
                            class="px-6 py-2 bg-royal-600 text-white font-bold rounded-lg hover:bg-royal-700 transition">
                            إعادة المحاولة
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>