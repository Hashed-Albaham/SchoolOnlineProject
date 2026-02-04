<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white">بناء الأسئلة: {{ $quiz->title }}</h2>
            <a href="{{ route('tutor.courses.quizzes.index', $course) }}" class="text-luxury-400 hover:text-white transition">عودة للاختبارات</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Existing Questions -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-xl font-bold text-white mb-4">الأسئلة المضافة ({{ $quiz->questions->count() }})</h3>
                
                @forelse($quiz->questions as $question)
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 relative">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-lg font-medium text-white">{{ $loop->iteration }}. {{ $question->question_text }}</h4>
                            <div class="flex items-center gap-2">
                                <span class="bg-gold-500/20 text-gold-400 text-xs px-2 py-1 rounded-full">{{ $question->points }} نقاط</span>
                                <form action="{{ route('tutor.courses.quizzes.questions.destroy', [$course, $quiz, $question]) }}" method="POST" onsubmit="return confirm('حذف هذا السؤال؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <div class="flex items-center gap-3 p-3 rounded-lg {{ $option->is_correct ? 'bg-green-500/10 border border-green-500/20' : 'bg-white/5 border border-white/5' }}">
                                    @if($option->is_correct)
                                        <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <div class="w-5 h-5 rounded-full border border-luxury-500"></div>
                                    @endif
                                    <span class="{{ $option->is_correct ? 'text-green-400' : 'text-luxury-300' }}">{{ $option->option_text }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-luxury-800/30 rounded-2xl border border-white/5 border-dashed">
                        <p class="text-luxury-400">لم يتم إضافة أي أسئلة بعد.</p>
                    </div>
                @endforelse
            </div>

            <!-- Right Column: Add New Question Form -->
            <div class="lg:col-span-1">
                <div class="bg-luxury-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-white mb-6">إضافة سؤال جديد</h3>
                    
                    <form action="{{ route('tutor.courses.quizzes.questions.store', [$course, $quiz]) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">نص السؤال</label>
                            <textarea name="question_text" rows="3" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">النقاط</label>
                            <input type="number" name="points" value="1" min="1" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-luxury-300">الخيارات (حدد الإجابة الصحيحة)</label>
                            
                            @for($i = 0; $i < 4; $i++)
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="correct_option" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }} class="text-gold-500 focus:ring-gold-500 bg-white/10 border-white/20">
                                    <input type="text" name="options[]" placeholder="الخيار {{ $i + 1 }}" required class="flex-1 px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white focus:border-gold-500/50 text-sm">
                                </div>
                            @endfor
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-gold-gradient text-luxury-900 font-bold hover:shadow-glow transition mt-4">
                            + إضافة السؤال
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
