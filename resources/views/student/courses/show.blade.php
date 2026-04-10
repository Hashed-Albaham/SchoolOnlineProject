<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.courses.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.course_details') }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Thumbnail -->
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mb-6">
                        <div class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative">
                            @if($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-20 h-20 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <div class="p-8">
                            <h1 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ $course->title }}</h1>
                            
                            <!-- Meta Info -->
                            <div class="flex flex-wrap items-center gap-6 mb-6 text-luxury-400">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $course->contents->count() ?? 0 }} {{ __('site.lessons_count') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span>{{ $course->enrollments_count ?? 0 }} {{ __('site.students_count') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $course->created_at->format('Y/m/d') }}</span>
                                </div>
                            </div>
                            
                            <p class="text-luxury-300 leading-relaxed">{{ $course->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Course Content -->
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                        <div class="p-6 border-b border-white/5">
                            <h3 class="font-semibold text-white">{{ __('site.course_content') }}</h3>
                        </div>
                        
                        @if($course->contents->count() > 0)
                            <div class="divide-y divide-white/5">
                                @foreach($course->contents->sortBy('order') as $index => $content)
                                    <div class="p-4 flex items-center gap-4 hover:bg-white/5 transition">
                                        <div class="w-10 h-10 rounded-lg bg-royal-500/20 flex items-center justify-center flex-shrink-0">
                                            <span class="text-royal-400 font-semibold">{{ $index + 1 }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-white">{{ $content->title }}</h4>
                                            @if($content->description)
                                                <p class="text-sm text-luxury-400 mt-0.5">{{ Str::limit($content->description, 60) }}</p>
                                            @endif
                                        </div>
                                        
                                        
                                        @if(!$isEnrolled)
                                            <svg class="w-5 h-5 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <p class="text-luxury-400">{{ __('site.no_content_available') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Section -->
                    <livewire:course-reviews :courseId="$course->id" />

                    <!-- Quizzes Section -->
                    @if($course->quizzes->count() > 0)
                        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mt-8">
                            <h3 class="text-xl font-bold text-white mb-4">{{ __('site.quizzes_section') }}</h3>
                            <div class="space-y-4">
                                @foreach($course->quizzes as $quiz)
                                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/5">
                                        <div>
                                            <h4 class="font-bold text-white">{{ $quiz->title }}</h4>
                                            <p class="text-sm text-luxury-400 mt-1">{{ $quiz->description }}</p>
                                        </div>
                                        @if($isEnrolled)
                                            <a href="{{ route('student.quizzes.show', $quiz) }}" class="px-4 py-2 bg-royal-600 text-white font-medium rounded-lg hover:bg-royal-700 transition">
                                                {{ __('site.start_quiz') }}
                                            </a>
                                        @else
                                            <span class="text-luxury-500 text-sm">{{ __('site.enroll_to_access') }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Enrollment Card -->
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 sticky top-24">
                        <!-- Price -->
                        <div class="text-center mb-6">
                            @if($course->price > 0)
                                <p class="text-4xl font-bold text-gradient">${{ $course->price }}</p>
                            @else
                                <p class="text-4xl font-bold text-green-400">{{ __('site.free') }}</p>
                            @endif
                        </div>
                        
                        <!-- Action Button ->
                        @if($isEnrolled)
                            <a href="{{ route('student.courses.watch', $course) }}" 
                                class="btn-premium w-full py-4 rounded-xl font-semibold text-center block mb-4">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('site.continue_learning_btn') }}
                                </span>
                            </a>
                            <p class="text-center text-green-400 text-sm">
                                <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('site.enrolled_in_course') }}
                            </p>
                        @elseif(isset($enrollment) && $enrollment->enrollment_status === 'pending_approval')
                            <div class="bg-yellow-500/20 text-yellow-500 w-full py-4 rounded-xl font-semibold text-center block mb-4 border border-yellow-500/30">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('site.enrollment_pending_approval') }}
                                </span>
                            </div>
                        @elseif(isset($enrollment) && $enrollment->enrollment_status === 'rejected')
                            <div class="bg-red-500/20 text-red-400 w-full py-4 rounded-xl font-semibold text-center block mb-4 border border-red-500/30">
                                <span class="flex items-center justify-center gap-2">
                                    {{ __('site.enrollment_rejected') ?? 'Your request was rejected' }}
                                </span>
                            </div>
                            <form action="{{ route('student.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-premium w-full py-4 rounded-xl font-semibold">
                                    <span class="flex items-center justify-center gap-2">
                                        @if($course->price > 0)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ __('site.subscribe_now') }}
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            {{ __('site.register_free') }}
                                        @endif
                                    </span>
                                </button>
                            </form>
                            @if($course->price > 0)
                                <p class="text-center text-luxury-500 text-xs mt-3">{{ __('site.refund_guarantee') }}</p>
                            @endif
                        @endif
                        -->
                        <div class="mb-4">
    @auth
        @if($isEnrolled)
            {{-- الحالة 1: مشترك ومقبول (دخول الدورة) --}}
            <a href="{{ route('student.courses.watch', $course) }}" 
                class="btn-premium w-full py-4 rounded-xl font-semibold text-center block mb-2">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    </svg>
                    {{ __('site.continue_learning_btn') }}
                </span>
            </a>
            <p class="text-center text-green-400 text-sm">
                {{ __('site.enrolled_in_course') }}
            </p>

        @elseif(isset($enrollment) && $enrollment->payment_status === 'pending')
            {{-- الحالة 2: ضغط اشترك ولكن لم يدفع بعد (زر إكمال الدفع) --}}
            <a href="{{ route('student.enrollment.payment', $enrollment->id) }}" 
                class="bg-yellow-500 hover:bg-yellow-600 text-luxury-900 w-full py-4 rounded-xl font-bold text-center block transition shadow-lg shadow-yellow-500/20">
                {{ __('site.complete_payment') }}
            </a>

        @elseif(isset($enrollment) && $enrollment->enrollment_status === 'pending_approval')
            {{-- الحالة 3: دفع وبانتظار تفعيل المعلم --}}
            <div class="bg-yellow-500/10 text-yellow-500 w-full py-4 rounded-xl font-semibold text-center border border-yellow-500/20">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('site.enrollment_pending_approval') }}
                </span>
            </div>

        @elseif(isset($enrollment) && $enrollment->enrollment_status === 'rejected')
            {{-- الحالة 4: تم الرفض (زر إعادة المحاولة) --}}
            <div class="bg-red-500/10 text-red-400 p-3 rounded-xl text-center mb-4 border border-red-500/20 text-sm">
                {{ __('site.enrollment_rejected') }}
            </div>
            <form action="{{ route('student.enroll', $course) }}" method="POST">
                @csrf
                <button type="submit" class="btn-premium w-full py-4 rounded-xl font-bold">
                    {{ __('site.try_again') }}
                </button>
            </form>

        @else
            {{-- الحالة 5: طالب جديد تماماً (هذا هو الزر الذي كان مختفياً) --}}
            <form action="{{ route('student.enroll', $course) }}" method="POST">
                @csrf
                <button type="submit" class="btn-premium w-full py-4 rounded-xl font-bold hover:shadow-glow transition transform hover:scale-[1.02]">
                    <span class="flex items-center justify-center gap-2">
                        @if($course->price > 0)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ __('site.subscribe_now') }} ({{ $course->price }} SAR)
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('site.register_free') }}
                        @endif
                    </span>
                </button>
            </form>
            @if($course->price > 0)
                <p class="text-center text-luxury-500 text-xs mt-3 italic">{{ __('site.refund_guarantee') }}</p>
            @endif
        @endif
    @else
        {{-- الحالة 6: زائر غير مسجل دخول --}}
        <a href="{{ route('login') }}" class="btn-premium w-full py-4 rounded-xl font-bold text-center block">
            {{ __('site.login_to_enroll') }}
        </a>
    @endauth
</div>

                        <!-- Features -->
                        
                        <div class="border-t border-white/5 mt-6 pt-6 space-y-3">
                            <div class="flex items-center gap-3 text-luxury-300">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('site.lifetime_access') }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-luxury-300">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('site.completion_certificate') }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-luxury-300">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('site.tutor_support') }}</span>
                            </div>
                        </div>
                        
                        <!-- Tutor -->
                        <div class="border-t border-white/5 mt-6 pt-6">
                            <p class="text-luxury-400 text-sm mb-3">{{ __('site.the_tutor') }}</p>
                            <div class="flex items-center gap-3">
                                <x-avatar :user="$course->tutor" sizeClasses="w-12 h-12" iconClasses="w-6 h-6" />
                                <div>
                                    <p class="font-medium text-white">{{ $course->tutor?->name ?? __('site.deleted_user') }}</p>
                                    @if($course->tutor && $course->tutor->tutorDetails)
                                        <p class="text-sm text-luxury-400">{{ $course->tutor->tutorDetails->specialization ?? '' }}</p>
                                    @endif
                                    @auth
                                        @if($course->tutor && auth()->id() !== $course->tutor_id)
                                            <a href="{{ route('messages.show', $course->tutor->id) }}" class="inline-flex items-center gap-1 text-xs text-gold-400 hover:text-white transition mt-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ __('site.message_tutor') }}
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>