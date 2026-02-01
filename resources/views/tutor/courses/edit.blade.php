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
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="btn-premium flex-1 py-4 rounded-xl font-semibold text-lg">
                            حفظ التغييرات
                        </button>
                        <a href="{{ route('tutor.courses.contents.index', $course) }}"
                            class="px-6 py-4 rounded-xl font-semibold border border-gold-500/30 text-gold-400 hover:bg-gold-500/10 transition">
                            إدارة المحتوى
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>