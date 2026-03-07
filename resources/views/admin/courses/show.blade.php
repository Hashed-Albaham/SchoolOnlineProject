<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.admin_course_details') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.admin_course_review_desc') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

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
                    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }}">
                        @if($course->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-xl bg-green-500/90 text-white font-semibold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('site.status_approved') }}
                            </span>
                        @elseif($course->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-xl bg-yellow-500/90 text-luxury-900 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('site.status_pending') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-xl bg-red-500/90 text-white font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('site.status_rejected') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    <h3 class="text-2xl font-bold text-white mb-4">{{ $course->title }}</h3>

                    <div class="grid sm:grid-cols-4 gap-4 mb-6">
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">{{ __('site.price') }}</p>
                            <p class="text-gold-400 font-bold text-lg mt-1">
                                @if($course->price > 0) ${{ $course->price }} @else {{ __('site.free') }} @endif
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">{{ __('site.lessons_total') }}</p>
                            <p class="text-white font-bold text-lg mt-1">{{ $course->contents->count() }} {{ __('site.lessons_count') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">{{ __('site.enrolled_students') }}</p>
                            <p class="text-white font-bold text-lg mt-1">{{ $course->enrollments->count() ?? 0 }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-sm">{{ __('site.creation_date') }}</p>
                            <p class="text-white font-medium mt-1">{{ $course->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-luxury-400 mb-2">{{ __('site.description') }}</h4>
                        <p class="text-luxury-300 leading-relaxed">{{ $course->description }}</p>
                    </div>

                    <!-- Tutor Info -->
                    <div class="p-4 rounded-xl bg-white/5 flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center">
                                <span class="text-white font-semibold">{{ substr($course->tutor->name ?? 'M', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $course->tutor?->name ?? __('site.the_tutor') }}</p>
                                <p class="text-sm text-luxury-400">{{ $course->tutor->email ?? '' }}</p>
                            </div>
                        </div>
                        @if($course->tutor)
                            <a href="{{ route('admin.tutors.show', $course->tutor) }}" class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                                {{ __('site.view_profile') }} →
                            </a>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        @if($course->status === 'pending')
                            <form action="{{ route('admin.courses.approve', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('site.approve_course') }}
                                </button>
                            </form>
                            <form action="{{ route('admin.courses.reject', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 rounded-xl font-semibold border border-red-500/30 text-red-400 hover:bg-red-500/10 transition inline-flex items-center gap-2"
                                    onclick="return confirm('{{ __('site.confirm_reject_course') }}')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ __('site.reject_course') }}
                                </button>
                            </form>
                        @endif

                        @if($course->status === 'approved')
                            <form action="{{ route('admin.courses.unapprove', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 rounded-xl font-semibold border border-orange-500/30 text-orange-400 hover:bg-orange-500/10 transition inline-flex items-center gap-2"
                                    onclick="return confirm('{{ __('site.confirm_unapprove_course') }}')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    {{ __('site.unapprove_course') }}
                                </button>
                            </form>
                        @endif

                        @if($course->status === 'rejected')
                            <form action="{{ route('admin.courses.approve', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('site.approve_course') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Course Management Links for Admin -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 mt-6">
                <a href="{{ route('tutor.courses.edit', $course) }}#content-section" class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 flex items-center gap-4 hover:bg-white/5 transition group">
                    <div class="w-12 h-12 rounded-xl bg-gold-500/20 text-gold-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-1">{{ __('site.course_lessons_management') }}</h3>
                        <p class="text-luxury-400 text-xs text-balance">{{ __('site.course_lessons_management_desc') }}</p>
                    </div>
                </a>
                
                <a href="{{ route('tutor.courses.quizzes.index', $course) }}" class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 flex items-center gap-4 hover:bg-white/5 transition group">
                    <div class="w-12 h-12 rounded-xl bg-royal-500/20 text-royal-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-1">{{ __('site.course_quizzes_management') }}</h3>
                        <p class="text-luxury-400 text-xs text-balance">{{ __('site.course_quizzes_management_desc') }}</p>
                    </div>
                </a>
            </div>

            <!-- Course Contents -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mt-6">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.course_content') }}</h3>
                </div>

                @if($course->contents->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($course->contents->sortBy('order') as $index => $content)
                            <div class="p-6 hover:bg-white/5 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-royal-500/20 flex items-center justify-center flex-shrink-0">
                                        <span class="text-royal-400 font-semibold">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-white">{{ $content->title }}</h4>
                                        @if($content->description)
                                            <p class="text-sm text-luxury-400 mt-1">{{ Str::limit($content->description, 100) }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-royal-500/20 text-royal-400">{{ $content->type ?? 'video' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($content->youtube_video_id)
                                            <a href="https://www.youtube.com/watch?v={{ $content->youtube_video_id }}" target="_blank"
                                                class="p-2 rounded-lg text-luxury-400 hover:text-gold-400 hover:bg-white/5 transition" title="{{ __('site.watch_video') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        {{-- [A10] Admin Delete Content Button --}}
                                        <form action="{{ route('admin.courses.content.destroy', [$course, $content]) }}" method="POST"
                                            onsubmit="return confirm('{{ __('site.confirm_delete_content') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-red-400 hover:bg-red-500/10 transition" title="{{ __('site.delete') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.no_content_in_course') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>