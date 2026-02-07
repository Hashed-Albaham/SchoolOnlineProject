<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.courses.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">تعديل الكورس</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ $course->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Status Banner -->
            <div class="mb-6 p-4 rounded-xl 
                @if($course->status === 'approved') bg-green-500/10 border border-green-500/20
                @elseif($course->status === 'pending') bg-yellow-500/10 border border-yellow-500/20
                @else bg-red-500/10 border border-red-500/20 @endif">
                <div class="flex items-center gap-3">
                    @if($course->status === 'approved')
                        <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-green-400 font-medium">الكورس معتمد ومنشور</p>
                    @elseif($course->status === 'pending')
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-yellow-400 font-medium">الكورس بانتظار مراجعة المسؤول</p>
                    @else
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-400 font-medium">الكورس مرفوض - يرجى مراجعة المحتوى وإعادة التقديم</p>
                    @endif
                </div>
            </div>

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form action="{{ route('tutor.courses.update', $course) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-luxury-300 mb-2">عنوان الكورس <span
                                class="text-red-400">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                        @error('title')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-luxury-300 mb-2">الوصف <span
                                class="text-red-400">*</span></label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition resize-none">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-luxury-300 mb-2">السعر
                            (بالدولار)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-luxury-500">$</span>
                            </div>
                            <input type="number" id="price" name="price" value="{{ old('price', $course->price) }}"
                                min="0" step="0.01"
                                class="w-full pr-10 pl-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                        </div>
                        @error('price')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Thumbnail -->
                    @if($course->thumbnail)
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">صورة الغلاف الحالية</label>
                            <div class="w-full max-w-md rounded-xl overflow-hidden border border-white/10">
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}"
                                    class="w-full h-auto">
                            </div>
                        </div>
                    @endif

                    <!-- New Thumbnail -->
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-luxury-300 mb-2">
                            {{ $course->thumbnail ? 'تغيير صورة الغلاف' : 'صورة الغلاف' }}
                        </label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 file:font-medium file:cursor-pointer focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                        @error('thumbnail')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="btn-premium w-full py-4 rounded-xl font-semibold text-lg">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            <!-- Content Management Section -->
            <div id="content-section"
                class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8 mt-8">
                <h3 class="text-xl font-bold text-white mb-6">إدارة محتوى الكورس</h3>

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Add Content Form -->
                <form action="{{ route('tutor.courses.content.add', $course) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4 bg-white/5 rounded-xl p-6 mb-8"
                    x-data="{ contentType: 'video' }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Content Title -->
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">عنوان المحتوى <span
                                    class="text-red-400">*</span></label>
                            <input type="text" name="title" required
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="مثال: الدرس الأول - المقدمة">
                            @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Content Type -->
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">نوع المحتوى <span
                                    class="text-red-400">*</span></label>
                            <select name="type" x-model="contentType"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition [&>option]:bg-luxury-800 [&>option]:text-white">
                                <option class="bg-luxury-800" value="video">🎬 فيديو YouTube</option>
                                <option class="bg-luxury-800" value="file">📄 ملف (PDF, DOC, ...)</option>
                                <option class="bg-luxury-800" value="image">🖼️ صورة</option>
                                <option class="bg-luxury-800" value="text">📝 نص / ملاحظات</option>
                                <option class="bg-luxury-800" value="link">🔗 رابط خارجي</option>
                                <option class="bg-luxury-800" value="quiz">❓ اختبار</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Fields Based on Type -->
                    <div>
                        <!-- Video Field -->
                        <div x-show="contentType === 'video'" x-data="{ videoSource: 'youtube' }" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">مصدر الفيديو <span
                                    class="text-red-400">*</span></label>
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="video_source" value="youtube" x-model="videoSource"
                                        class="text-gold-500 focus:ring-gold-500 bg-white/10 border-white/20">
                                    <span class="text-white text-sm">YouTube</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="video_source" value="local" x-model="videoSource"
                                        class="text-gold-500 focus:ring-gold-500 bg-white/10 border-white/20">
                                    <span class="text-white text-sm">رفع من الجهاز</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="video_source" value="external" x-model="videoSource"
                                        class="text-gold-500 focus:ring-gold-500 bg-white/10 border-white/20">
                                    <span class="text-white text-sm">رابط خارجي</span>
                                </label>
                            </div>

                            <!-- YouTube -->
                            <div x-show="videoSource === 'youtube'">
                                <label class="block text-sm font-medium text-luxury-300 mb-2">رابط YouTube</label>
                                <input type="text" name="youtube_url"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition"
                                    placeholder="https://www.youtube.com/watch?v=...">
                            </div>

                            <!-- Local Upload -->
                            <div x-show="videoSource === 'local'">
                                <label class="block text-sm font-medium text-luxury-300 mb-2">اختر ملف الفيديو</label>
                                <input type="file" name="video_file" accept="video/mp4,video/mpeg,video/quicktime"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 transition">
                                <p class="text-luxury-500 text-xs mt-1">MP4, MPEG, MOV (الحد الأقصى 500 ميجابايت)</p>
                            </div>

                            <!-- External Link -->
                            <div x-show="videoSource === 'external'">
                                <label class="block text-sm font-medium text-luxury-300 mb-2">رابط الفيديو
                                    المباشر</label>
                                <input type="url" name="video_url"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition"
                                    placeholder="https://example.com/video.mp4">
                            </div>

                            @error('youtube_url') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            @error('video_file') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            @error('video_url') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- File Field -->
                        <div x-show="contentType === 'file'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">رفع ملف <span
                                    class="text-red-400">*</span></label>
                            <input type="file" name="content_file"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 transition">
                            <p class="text-luxury-500 text-xs mt-1">الحد الأقصى: 50 ميجابايت</p>
                            @error('content_file') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Image Field -->
                        <div x-show="contentType === 'image'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">رفع صورة <span
                                    class="text-red-400">*</span></label>
                            <input type="file" name="content_image" accept="image/*"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 transition">
                            @error('content_image') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Text Field -->
                        <div x-show="contentType === 'text'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">المحتوى النصي <span
                                    class="text-red-400">*</span></label>
                            <textarea name="text_content" rows="5"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition resize-none"
                                placeholder="أضف ملاحظات، شرح نصي، أو أي محتوى مكتوب..."></textarea>
                            @error('text_content') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Link Field -->
                        <div x-show="contentType === 'link'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">رابط خارجي <span
                                    class="text-red-400">*</span></label>
                            <input type="url" name="link_url"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition"
                                placeholder="https://example.com/resource">
                            @error('link_url') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Quiz Field -->
                        <div x-show="contentType === 'quiz'" x-cloak>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">اختر اختبار</label>
                            <select name="quiz_id"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 transition [&>option]:bg-luxury-800 [&>option]:text-white">
                                <option value="">-- اختر اختبار --</option>
                                @foreach(\App\Models\Quiz::where('course_id', $course->id)->get() as $quiz)
                                    <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                @endforeach
                            </select>
                            <p class="text-luxury-500 text-xs mt-1">أنشئ اختبارات من قسم الاختبارات أولاً</p>
                            @error('quiz_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">وصف (اختياري)</label>
                        <textarea name="description" rows="2"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 transition resize-none"
                            placeholder="وصف مختصر للمحتوى..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-gold-gradient text-luxury-900 font-semibold hover:shadow-glow transition">
                        + إضافة محتوى
                    </button>
                </form>

                <!-- Existing Contents List -->
                <h4 class="text-white font-semibold mb-4">المحتويات الحالية ({{ $course->contents->count() }})</h4>

                @if($course->contents->count() > 0)
                    <div class="space-y-3">
                        @foreach($course->contents as $content)
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/10">
                                <div class="flex items-center gap-4">
                                    <span class="text-luxury-400 font-mono text-sm">{{ $content->order }}</span>
                                    <div>
                                        <p class="text-white font-medium">{{ $content->title }}</p>
                                        <span
                                            class="text-xs px-2 py-1 rounded-full bg-gold-500/20 text-gold-400">{{ $content->type_label }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('tutor.courses.content.edit', [$course, $content]) }}"
                                        class="p-2 text-luxury-400 hover:text-gold-400 hover:bg-gold-500/10 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('tutor.courses.content.delete', [$course, $content]) }}"
                                        method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المحتوى؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-luxury-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <p>لا يوجد محتوى بعد. أضف أول درس!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>