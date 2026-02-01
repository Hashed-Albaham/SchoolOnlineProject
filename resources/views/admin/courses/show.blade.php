<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">تفاصيل الكورس</h2>
                <p class="text-luxury-400 text-sm mt-1">مراجعة محتوى الكورس واتخاذ الإجراء المناسب</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Course Card -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mb-8">
                <!-- Thumbnail -->
                <div class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-20 h-20 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4">
                        @if($course->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-xl bg-green-500/90 text-white font-semibold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                معتمد
                            </span>
                        @elseif($course->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-xl bg-yellow-500/90 text-luxury-900 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                بانتظار الموافقة
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-xl bg-red-500/90 text-white font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                مرفوض
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-white mb-4">{{ $course->title }}</h3>
                    
                    <div class="grid sm:grid-cols-4 gap-4 mb-6">
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">السعر</p>
                            <p class="text-gold-400 font-bold text-lg mt-1">
                                @if($course->price > 0) ${{ $course->price }} @else مجاني @endif
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">عدد الدروس</p>
                            <p class="text-white font-bold text-lg mt-1">{{ $course->contents->count() }} درس</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">الطلاب المسجلون</p>
                            <p class="text-white font-bold text-lg mt-1">{{ $course->enrollments->count() ?? 0 }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">تاريخ الإنشاء</p>
                            <p class="text-white font-medium mt-1">{{ $course->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-luxury-400 mb-2">الوصف</h4>
                        <p class="text-luxury-300 leading-relaxed">{{ $course->description }}</p>
                    </div>
                    
                    <!-- Tutor Info -->
                    <div class="p-4 rounded-xl bg-white/5 flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center">
                                <span class="text-white font-semibold">{{ substr($course->tutor->name ?? 'M', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $course->tutor->name ?? 'المعلم' }}</p>
                                <p class="text-sm text-luxury-400">{{ $course->tutor->email ?? '' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.tutors.show', $course->tutor) }}" class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                            عرض الملف ←
                        </a>
                    </div>
                    
                    <!-- Action Buttons -->
                    @if($course->status === 'pending')
                        <div class="flex flex-wrap gap-4">
                            <form action="{{ route('admin.courses.approve', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    الموافقة على الكورس
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.courses.reject', $course) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="px-6 py-3 rounded-xl font-semibold border border-red-500/30 text-red-400 hover:bg-red-500/10 transition inline-flex items-center gap-2"
                                    onclick="return confirm('هل أنت متأكد من رفض هذا الكورس؟')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    رفض الكورس
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Course Contents -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">محتوى الكورس</h3>
                </div>
                
                @if($course->contents->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($course->contents->sortBy('order') as $index => $content)
                            <div class="p-6 hover:bg-white/5 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-royal-500/20 flex items-center justify-center flex-shrink-0">
                                        <span class="text-royal-400 font-semibold">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-white">{{ $content->title }}</h4>
                                        @if($content->description)
                                            <p class="text-sm text-luxury-400 mt-1">{{ Str::limit($content->description, 100) }}</p>
                                        @endif
                                    </div>
                                    @if($content->youtube_video_id)
                                        <a href="https://www.youtube.com/watch?v={{ $content->youtube_video_id }}" target="_blank" 
                                            class="text-luxury-400 hover:text-gold-400 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0V2m0 2h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2m-10 0V4"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400">لا يوجد محتوى في هذا الكورس حتى الآن</p>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</x-app-layout>