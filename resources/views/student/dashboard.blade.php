<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">مرحباً، {{ auth()->user()->name }}! 👋</h2>
                <p class="text-luxury-400 text-sm mt-1">استمر في التعلم وتطوير مهاراتك</p>
            </div>
            <a href="{{ route('student.courses.index') }}"
                class="btn-premium px-6 py-3 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                اكتشف كورسات جديدة
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <!-- Enrolled Courses -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">الكورسات المسجلة</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['enrolled_courses'] ?? 0 }}</p>
                            <p class="text-green-400 text-xs mt-1">كورس نشط</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">بانتظار الدفع</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['pending_enrollments'] ?? 0 }}</p>
                            <p class="text-yellow-400 text-xs mt-1">قيد الانتظار</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed (placeholder) -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">شهادات مكتسبة</p>
                            <p class="text-3xl font-bold text-white mt-2">0</p>
                            <p class="text-royal-400 text-xs mt-1">أكمل كورس لتحصل على شهادة</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-royal-500 to-royal-600 flex items-center justify-center shadow-lg shadow-royal-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.42 3.42 0 010 3.976 3.42 3.42 0 00-.723 1.745 3.42 3.42 0 01-2.812 2.812 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-2.812-2.812 3.42 3.42 0 00-.723-1.745 3.42 3.42 0 010-3.976 3.42 3.42 0 00.723-1.745 3.42 3.42 0 012.812-2.812z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid sm:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('student.courses.index') }}"
                    class="group bg-gradient-to-br from-gold-500/10 to-gold-600/5 border border-gold-500/20 hover:border-gold-500/40 rounded-2xl p-6 transition-all">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-gold-gradient flex items-center justify-center shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-luxury-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-lg">تصفح الكورسات</p>
                            <p class="text-luxury-400 text-sm">اكتشف محتوى جديد</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('student.courses.my') }}"
                    class="group bg-luxury-800/50 backdrop-blur-xl border border-white/5 hover:border-green-500/30 rounded-2xl p-6 transition-all">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-green-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-lg">كورساتي</p>
                            <p class="text-luxury-400 text-sm">استكمل التعلم</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="group bg-luxury-800/50 backdrop-blur-xl border border-white/5 hover:border-royal-500/30 rounded-2xl p-6 transition-all">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-royal-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-lg">ملفي الشخصي</p>
                            <p class="text-luxury-400 text-sm">تعديل البيانات</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- My Enrolled Courses -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mb-8">
                <div class="p-6 border-b border-white/5 flex items-center justify-between">
                    <h3 class="font-semibold text-white text-lg">كورساتي المسجلة</h3>
                    <a href="{{ route('student.courses.my') }}"
                        class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                        عرض الكل ←
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($enrolledCourses) && $enrolledCourses->count() > 0)
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($enrolledCourses->take(3) as $enrollment)
                                <div class="bg-white/5 rounded-2xl overflow-hidden group hover:bg-white/10 transition">
                                    <div
                                        class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative overflow-hidden">
                                        @if($enrollment->course->thumbnail)
                                            <img src="{{ Storage::url($enrollment->course->thumbnail) }}"
                                                alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-12 h-12 text-royal-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-luxury-900/80 to-transparent"></div>
                                        <div class="absolute bottom-3 right-3 left-3">
                                            <p class="text-white font-semibold line-clamp-1">{{ $enrollment->course->title }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-luxury-400 text-sm">
                                                {{ $enrollment->course->tutor->name ?? 'المعلم' }}</p>
                                            <span class="text-xs px-2 py-1 rounded-lg bg-green-500/20 text-green-400">نشط</span>
                                        </div>
                                        <a href="{{ route('student.courses.watch', $enrollment->course) }}"
                                            class="w-full block text-center py-2.5 rounded-xl bg-gold-500/20 text-gold-400 font-medium hover:bg-gold-500/30 transition">
                                            متابعة التعلم →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-luxury-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-white mb-2">لم تسجل في أي كورس بعد</h4>
                            <p class="text-luxury-400 mb-6">ابدأ رحلة التعلم الآن واستكشف كورسات رائعة</p>
                            <a href="{{ route('student.courses.index') }}"
                                class="btn-premium px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                                تصفح الكورسات
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recommended Courses -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gold-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white text-lg">كورسات مقترحة لك</h3>
                    </div>
                    <a href="{{ route('student.courses.index') }}"
                        class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                        عرض المزيد ←
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recommendedCourses) && $recommendedCourses->count() > 0)
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($recommendedCourses->take(4) as $course)
                                <a href="{{ route('student.courses.show', $course) }}" class="group">
                                    <div class="bg-white/5 rounded-2xl overflow-hidden hover:bg-white/10 transition">
                                        <div
                                            class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center">
                                            @if($course->thumbnail)
                                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-10 h-10 text-royal-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <p class="font-medium text-white line-clamp-1 group-hover:text-gold-400 transition">
                                                {{ $course->title }}</p>
                                            <p class="text-sm text-luxury-400 mt-1">{{ $course->tutor->name ?? 'المعلم' }}</p>
                                            <div class="flex items-center justify-between mt-3">
                                                <span class="text-gold-400 font-bold">
                                                    @if($course->price > 0) ${{ $course->price }} @else مجاني @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-luxury-400 py-8">لا توجد كورسات متاحة حالياً</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>