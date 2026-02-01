<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutors.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">تفاصيل المعلم</h2>
                <p class="text-luxury-400 text-sm mt-1">مراجعة بيانات المعلم واتخاذ الإجراء المناسب</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Profile Card -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mb-8">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-24 h-24 rounded-2xl bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center shadow-lg shadow-royal-500/20">
                                <span class="text-white font-bold text-4xl">{{ substr($tutor->name, 0, 1) }}</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <h3 class="text-2xl font-bold text-white">{{ $tutor->name }}</h3>
                                @if($tutor->tutorDetails && $tutor->tutorDetails->is_verified)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-sm rounded-xl bg-green-500/20 text-green-400 border border-green-500/30">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        حساب موثق
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-sm rounded-xl bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        بانتظار التحقق
                                    </span>
                                @endif
                            </div>
                            <p class="text-luxury-400 mb-4">{{ $tutor->email }}</p>

                            <div class="grid sm:grid-cols-3 gap-4">
                                <div class="p-4 rounded-xl bg-white/5">
                                    <p class="text-luxury-400 text-sm">التخصص</p>
                                    <p class="text-white font-medium mt-1">
                                        {{ $tutor->tutorDetails->specialization ?? 'غير محدد' }}</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5">
                                    <p class="text-luxury-400 text-sm">عدد الكورسات</p>
                                    <p class="text-white font-medium mt-1">{{ $tutor->courses->count() }} كورس</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5">
                                    <p class="text-luxury-400 text-sm">تاريخ الانضمام</p>
                                    <p class="text-white font-medium mt-1">{{ $tutor->created_at->format('Y/m/d') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                @if($tutor->tutorDetails && $tutor->tutorDetails->bio)
                    <div class="px-8 pb-8">
                        <h4 class="text-sm font-medium text-luxury-400 mb-2">نبذة عن المعلم</h4>
                        <p class="text-luxury-300 leading-relaxed">{{ $tutor->tutorDetails->bio }}</p>
                    </div>
                @endif

                <!-- CV Download -->
                @if($tutor->tutorDetails && $tutor->tutorDetails->cv_path)
                    <div class="px-8 pb-8">
                        <a href="{{ route('tutor.profile.cv', $tutor->tutorDetails) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-royal-500/20 text-royal-400 hover:bg-royal-500/30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            تحميل السيرة الذاتية
                        </a>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if(!($tutor->tutorDetails && $tutor->tutorDetails->is_verified))
                    <div class="px-8 pb-8 flex flex-wrap gap-4">
                        <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn-premium px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                الموافقة والتحقق
                            </button>
                        </form>

                        <form action="{{ route('admin.tutors.reject', $tutor) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 rounded-xl font-semibold border border-red-500/30 text-red-400 hover:bg-red-500/10 transition inline-flex items-center gap-2"
                                onclick="return confirm('هل أنت متأكد من رفض هذا المعلم؟')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                رفض الطلب
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Courses -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">كورسات المعلم</h3>
                </div>

                @if($tutor->courses->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($tutor->courses as $course)
                            <div class="p-6 hover:bg-white/5 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-white">{{ $course->title }}</h4>
                                        <p class="text-sm text-luxury-400 mt-1">{{ Str::limit($course->description, 80) }}</p>
                                        <div class="flex items-center gap-4 mt-2">
                                            <span class="text-gold-400 font-semibold">
                                                @if($course->price > 0) ${{ $course->price }} @else مجاني @endif
                                            </span>
                                            <span class="text-luxury-500 text-sm">{{ $course->contents->count() }} دروس</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($course->status === 'approved')
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">معتمد</span>
                                        @elseif($course->status === 'pending')
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">بانتظار</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400">مرفوض</span>
                                        @endif
                                        <a href="{{ route('admin.courses.show', $course) }}"
                                            class="text-luxury-400 hover:text-gold-400 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-luxury-400">لم يقم هذا المعلم بإنشاء أي كورسات بعد</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>