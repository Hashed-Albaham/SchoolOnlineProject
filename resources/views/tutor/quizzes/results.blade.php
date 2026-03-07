<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.courses.quizzes.index', $course) }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.student_results') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ $quiz->title }} - {{ $course->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-luxury-800/50 border border-white/5 rounded-xl p-6 text-center">
                    <span class="text-3xl font-bold text-white">{{ $stats['total_attempts'] }}</span>
                    <p class="text-luxury-400 text-sm mt-1">{{ __('site.total_attempts') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-white/5 rounded-xl p-6 text-center">
                    <span class="text-3xl font-bold text-green-400">{{ $stats['passed'] }}</span>
                    <p class="text-luxury-400 text-sm mt-1">{{ __('site.passed_students') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-white/5 rounded-xl p-6 text-center">
                    <span class="text-3xl font-bold text-red-400">{{ $stats['failed'] }}</span>
                    <p class="text-luxury-400 text-sm mt-1">{{ __('site.failed_students') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-white/5 rounded-xl p-6 text-center">
                    <span class="text-3xl font-bold text-gold-400">{{ number_format($stats['average_score'], 1) }}</span>
                    <p class="text-luxury-400 text-sm mt-1">{{ __('site.average_score') }}</p>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-luxury-800/50 border border-white/5 rounded-2xl overflow-hidden">
                @if($attempts->count() > 0)
                    <div class="p-4 border-b border-white/5 flex justify-end">
                        <form action="{{ route('tutor.courses.quizzes.attempts.clear', [$course, $quiz]) }}" method="POST" 
                              onsubmit="return confirm('{{ __('site.delete_all_results_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition text-sm font-medium">
                                {{ __('site.delete_all_results') }}
                            </button>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-luxury-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-luxury-400 uppercase tracking-wider">{{ __('site.the_student') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-luxury-400 uppercase tracking-wider">{{ __('site.the_grade') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-luxury-400 uppercase tracking-wider">{{ __('site.the_result') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-luxury-400 uppercase tracking-wider">{{ __('site.the_date') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-luxury-400 uppercase tracking-wider">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($attempts as $attempt)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <x-avatar :user="$attempt->user" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                                <div>
                                                    <p class="text-white font-medium">{{ $attempt->user->name }}</p>
                                                    <p class="text-luxury-500 text-sm">{{ $attempt->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-white font-bold text-lg">{{ $attempt->score }}</span>
                                            <span class="text-luxury-500">/ {{ $quiz->questions->sum('points') ?? '?' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($attempt->passed)
                                                <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-sm font-medium">
                                                    {{ __('site.passed_mark') }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-sm font-medium">
                                                    {{ __('site.failed_mark') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-luxury-400">
                                            {{ $attempt->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('tutor.courses.quizzes.attempts.show', [$course, $quiz, $attempt]) }}"
                                                   class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition text-sm">
                                                    {{ __('site.the_answers') }}
                                                </a>
                                                <form action="{{ route('tutor.courses.quizzes.attempts.delete', [$course, $quiz, $attempt]) }}" 
                                                      method="POST" onsubmit="return confirm('{{ __('site.delete_attempt_confirm') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition text-sm">
                                                        {{ __('site.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 text-luxury-400">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-lg">{{ __('site.no_attempts_yet') }}</p>
                        <p class="text-sm mt-2">{{ __('site.no_student_took_quiz_yet') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
