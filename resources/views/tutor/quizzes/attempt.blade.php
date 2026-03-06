<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.courses.quizzes.results', [$course, $quiz]) }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">إجابات الطالب</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ $attempt->user->name }} - {{ $quiz->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Student Info & Score -->
            <div class="bg-luxury-800/50 border border-white/5 rounded-xl p-6 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <x-avatar :user="$attempt->user" sizeClasses="w-12 h-12" iconClasses="w-6 h-6" />
                        <div>
                            <h3 class="text-white font-bold text-lg">{{ $attempt->user->name }}</h3>
                            <p class="text-luxury-400 text-sm">{{ $attempt->user->email }}</p>
                            <p class="text-luxury-500 text-xs mt-1">{{ $attempt->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold {{ $attempt->passed ? 'text-green-400' : 'text-red-400' }}">
                            {{ $attempt->score }} / {{ $quiz->questions->sum('points') }}
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm {{ $attempt->passed ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $attempt->passed ? 'ناجح' : 'راسب' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Questions & Answers -->
            <div class="space-y-6">
                @php
                    $studentAnswers = is_array($attempt->answers) ? $attempt->answers : json_decode($attempt->answers ?? '{}', true);
                @endphp
                
                @foreach($quiz->questions as $index => $question)
                    @php
                        $selectedOptionId = $studentAnswers["q-{$question->id}"] ?? null;
                        $correctOption = $question->options->where('is_correct', true)->first();
                        $isCorrect = $correctOption && $correctOption->id == $selectedOptionId;
                    @endphp
                    
                    <div class="bg-luxury-800/50 border border-white/5 rounded-xl p-6">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="text-gold-400 text-xl font-bold">{{ $index + 1 }}.</span>
                            <div class="flex-1">
                                <h4 class="text-white font-medium">{{ $question->question_text }}</h4>
                                <span class="text-luxury-500 text-sm">({{ $question->points }} نقطة)</span>
                            </div>
                            @if($isCorrect)
                                <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-sm">صحيح ✓</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-sm">خطأ ✗</span>
                            @endif
                        </div>
                        
                        <div class="space-y-2 mr-8">
                            @foreach($question->options as $option)
                                @php
                                    $isSelected = $option->id == $selectedOptionId;
                                    $isCorrectOption = $option->is_correct;
                                    
                                    if ($isCorrectOption) {
                                        $bgClass = 'bg-green-500/10 border-green-500/30';
                                        $textClass = 'text-green-400';
                                    } elseif ($isSelected && !$isCorrectOption) {
                                        $bgClass = 'bg-red-500/10 border-red-500/30';
                                        $textClass = 'text-red-400';
                                    } else {
                                        $bgClass = 'bg-luxury-900/50 border-white/5';
                                        $textClass = 'text-luxury-300';
                                    }
                                @endphp
                                
                                <div class="p-3 rounded-lg border {{ $bgClass }} flex items-center gap-3">
                                    @if($isSelected)
                                        <svg class="w-5 h-5 {{ $textClass }}" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="8" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-luxury-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="8" stroke-width="2" />
                                        </svg>
                                    @endif
                                    <span class="{{ $textClass }}">{{ $option->option_text }}</span>
                                    @if($isCorrectOption)
                                        <span class="text-green-400 text-xs">(الإجابة الصحيحة)</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
