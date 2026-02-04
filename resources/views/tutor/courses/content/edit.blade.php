<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.courses.edit', $course) }}"
                class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">تعديل المحتوى</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ $course->title }} / {{ $content->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form action="{{ route('tutor.courses.content.update', [$course, $content]) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6" x-data="{ contentType: '{{ $content->type }}' }">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Content Title -->
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">عنوان المحتوى <span
                                    class="text-red-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $content->title) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Content Type -->
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">نوع المحتوى <span
                                    class="text-red-400">*</span></label>
                            <select name="type" x-model="contentType"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                                <option value="video">🎬 فيديو YouTube</option>
                                <option value="file">📄 ملف (PDF, DOC, ...)</option>
                                <option value="image">🖼️ صورة</option>
                                <option value="text">📝 نص / ملاحظات</option>
                                <option value="link">🔗 رابط خارجي</option>
                                <option value="quiz">❓ اختبار</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Fields Based on Type -->
                    <div>
                        <!-- Video Field -->
                        <div x-show="contentType === 'video'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">رابط YouTube <span
                                    class="text-red-400">*</span></label>
                            <input type="text" name="youtube_url"
                                value="{{ old('youtube_url', $content->type === 'video' ? 'https://www.youtube.com/watch?v=' . $content->youtube_video_id : '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition">
                            @error('youtube_url') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- File Field -->
                        <div x-show="contentType === 'file'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">تحديث الملف</label>
                            @if($content->type === 'file' && $content->file_path)
                                <div class="mb-2 text-sm text-green-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    يوجد ملف مرفق حالياً
                                </div>
                            @endif
                            <input type="file" name="content_file"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 transition">
                            @error('content_file') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Image Field -->
                        <div x-show="contentType === 'image'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">تحديث الصورة</label>
                            @if($content->type === 'image' && $content->file_path)
                                <img src="{{ Storage::url($content->file_path) }}" alt="Preview"
                                    class="h-20 rounded-lg mb-2 border border-white/10">
                            @endif
                            <input type="file" name="content_image" accept="image/*"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 transition">
                            @error('content_image') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Text Field -->
                        <div x-show="contentType === 'text'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">المحتوى النصي <span
                                    class="text-red-400">*</span></label>
                            <textarea name="text_content" rows="5"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition resize-none">{{ old('text_content', $content->text_content) }}</textarea>
                            @error('text_content') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Link Field -->
                        <div x-show="contentType === 'link'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">رابط خارجي <span
                                    class="text-red-400">*</span></label>
                            <input type="url" name="link_url" value="{{ old('link_url', $content->link_url) }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition">
                            @error('link_url') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Quiz Field -->
                        <div x-show="contentType === 'quiz'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">اختر اختبار</label>
                            <select name="quiz_id"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 transition">
                                <option value="">-- اختر اختبار --</option>
                                @foreach(\App\Models\Quiz::where('course_id', $course->id)->get() as $quiz)
                                    <option value="{{ $quiz->id }}" {{ $content->quiz_id == $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->title }}</option>
                                @endforeach
                            </select>
                            @error('quiz_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">وصف (اختياري)</label>
                        <textarea name="description" rows="2"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition resize-none">{{ old('description', $content->description) }}</textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 py-3 rounded-xl bg-gold-gradient text-luxury-900 font-semibold hover:shadow-glow transition">
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('tutor.courses.edit', $course) }}"
                            class="px-6 py-3 rounded-xl border border-white/10 text-white hover:bg-white/5 transition">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>