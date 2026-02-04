<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">تعديل الاختبار: {{ $quiz->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form action="{{ route('tutor.courses.quizzes.update', [$course, $quiz]) }}" method="POST"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">عنوان الاختبار <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50">
                        @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">وصف (اختياري)</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50">{{ old('description', $quiz->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">المدة (دقائق) <small>اتركه
                                    فارغاً لوقت مفتوح</small></label>
                            <input type="number" name="time_limit_minutes"
                                value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">نسبة النجاح (%) <span
                                    class="text-red-400">*</span></label>
                            <input type="number" name="pass_percentage"
                                value="{{ old('pass_percentage', $quiz->pass_percentage) }}" min="0" max="100" required
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">عدد المحاولات <small>اتركه
                                    فارغاً لمحاولات غير محدودة</small></label>
                            <input type="number" name="max_attempts"
                                value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('tutor.courses.quizzes.index', $course) }}"
                            class="px-6 py-3 rounded-xl text-luxury-300 hover:text-white hover:bg-white/5 transition">إلغاء</a>
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-gold-gradient text-luxury-900 font-bold hover:shadow-glow transition">حفظ
                            التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>