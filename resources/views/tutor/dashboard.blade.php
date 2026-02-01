<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">لوحة تحكم المعلم</h2>
                <p class="text-luxury-400 text-sm mt-1">مرحباً {{ auth()->user()->name }}، نتمنى لك يوماً مثمراً</p>
            </div>
            @if(auth()->user()->tutorDetails && auth()->user()->tutorDetails->is_verified)
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500/10 border border-green-500/20">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-green-400 text-sm font-medium">حساب موثق</span>
                </div>
            @else
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-yellow-400 text-sm font-medium">بانتظار التحقق</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(!auth()->user()->tutorDetails || !auth()->user()->tutorDetails->is_verified)
                <!-- Verification Alert -->
                <div
                    class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-yellow-500/10 to-orange-500/10 border border-yellow-500/20">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-lg">أكمل ملفك الشخصي للتحقق</h3>
                            <p class="text-luxury-400 mt-1">لتتمكن من نشر الكورسات، يرجى إكمال ملفك الشخصي ورفع السيرة
                                الذاتية. سيتم مراجعة حسابك والموافقة عليه.</p>
                        </div>
                        <a href="{{ route('tutor.profile.edit') }}"
                            class="btn-premium px-6 py-3 rounded-xl text-sm font-semibold whitespace-nowrap">
                            إكمال الملف
                        </a>
                    </div>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- My Courses -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">كورساتي</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_courses'] ?? 0 }}</p>
                            <p class="text-luxury-500 text-xs mt-1">إجمالي الكورسات</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-royal-500 to-royal-600 flex items-center justify-center shadow-lg shadow-royal-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Approved Courses -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">كورسات معتمدة</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['approved_courses'] ?? 0 }}</p>
                            <p class="text-green-400 text-xs mt-1">نشطة ومتاحة</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Courses -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">بانتظار الموافقة</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['pending_courses'] ?? 0 }}</p>
                            <p class="text-yellow-400 text-xs mt-1">قيد المراجعة</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Students -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">إجمالي الطلاب</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_students'] ?? 0 }}</p>
                            <p class="text-luxury-500 text-xs mt-1">مسجلون في كورساتك</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center shadow-lg shadow-gold-500/20">
                            <svg class="w-7 h-7 text-luxury-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid lg:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('tutor.courses.create') }}"
                    class="group bg-gradient-to-br from-gold-500/10 to-gold-600/5 border border-gold-500/20 hover:border-gold-500/40 rounded-2xl p-6 transition-all">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-gold-gradient flex items-center justify-center shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-luxury-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-lg">إنشاء كورس جديد</p>
                            <p class="text-luxury-400 text-sm">شارك معرفتك مع الآخرين</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tutor.courses.index') }}"
                    class="group bg-luxury-800/50 backdrop-blur-xl border border-white/5 hover:border-royal-500/30 rounded-2xl p-6 transition-all">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-royal-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-lg">إدارة كورساتي</p>
                            <p class="text-luxury-400 text-sm">عرض وتعديل الكورسات</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tutor.profile.edit') }}"
                    class="group bg-luxury-800/50 backdrop-blur-xl border border-white/5 hover:border-green-500/30 rounded-2xl p-6 transition-all">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-green-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-lg">ملفي الشخصي</p>
                            <p class="text-luxury-400 text-sm">تعديل البيانات والسيرة</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Courses & Activity -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Recent Courses -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <h3 class="font-semibold text-white">آخر الكورسات</h3>
                        <a href="{{ route('tutor.courses.index') }}"
                            class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                            عرض الكل ←
                        </a>
                    </div>
                    <div class="p-6">
                        @if(isset($recentCourses) && $recentCourses->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentCourses as $course)
                                    <div
                                        class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-12 h-12 rounded-lg bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-royal-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ Str::limit($course->title, 25) }}</p>
                                                <p class="text-sm text-luxury-400">
                                                    @if($course->price > 0) ${{ $course->price }} @else مجاني @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($course->status === 'approved')
                                                <span
                                                    class="px-2 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">معتمد</span>
                                            @elseif($course->status === 'pending')
                                                <span
                                                    class="px-2 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">بانتظار</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-lg bg-red-500/20 text-red-400">مرفوض</span>
                                            @endif
                                            <a href="{{ route('tutor.courses.edit', $course) }}"
                                                class="text-luxury-400 hover:text-gold-400 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 rounded-full bg-royal-500/10 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-royal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <p class="text-luxury-400 mb-4">لم تقم بإنشاء أي كورسات بعد</p>
                                <a href="{{ route('tutor.courses.create') }}"
                                    class="inline-flex items-center gap-2 text-gold-400 hover:text-gold-300 font-medium transition">
                                    إنشاء أول كورس
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="font-semibold text-white">آخر التسجيلات</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($recentEnrollments) && $recentEnrollments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentEnrollments as $enrollment)
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-white/5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                                <span
                                                    class="text-white font-semibold text-sm">{{ substr($enrollment->user->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ $enrollment->user->name ?? 'طالب' }}</p>
                                                <p class="text-sm text-luxury-400">
                                                    {{ Str::limit($enrollment->course->title ?? '', 20) }}</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-luxury-500">{{ $enrollment->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-luxury-400">لا توجد تسجيلات حتى الآن</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>