<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutors.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.admin_tutor_details') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.admin_tutor_review_desc') }}</p>
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
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Profile Card -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden mb-8">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                                <x-avatar :user="$tutor" sizeClasses="w-32 h-32" iconClasses="w-16 h-16" />
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <h3 class="text-2xl font-bold text-white">{{ $tutor->name }}</h3>
                                @if($tutor->tutorDetails && $tutor->tutorDetails->is_verified)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm rounded-xl bg-green-500/20 text-green-400 border border-green-500/30">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ __('site.verified_account') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm rounded-xl bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('site.pending_verification') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-luxury-400 mb-4">{{ $tutor->email }}</p>

                            <div class="grid sm:grid-cols-3 gap-4">
                                <div class="p-4 rounded-xl bg-white/5">
                                    <p class="text-luxury-400 text-sm">{{ __('site.specialization') }}</p>
                                    <p class="text-white font-medium mt-1">{{ $tutor->tutorDetails->specialization ?? __('site.not_specified') }}</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5">
                                    <p class="text-luxury-400 text-sm">{{ __('site.courses_count') }}</p>
                                    <p class="text-white font-medium mt-1">{{ $tutor->courses->count() }} {{ __('site.course_unit') }}</p>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5">
                                    <p class="text-luxury-400 text-sm">{{ __('site.join_date') }}</p>
                                    <p class="text-white font-medium mt-1">{{ $tutor->created_at->format('Y/m/d') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                @if($tutor->tutorDetails && $tutor->tutorDetails->bio)
                    <div class="px-8 pb-6">
                        <h4 class="text-sm font-medium text-luxury-400 mb-2">{{ __('site.about_tutor') }}</h4>
                        <p class="text-luxury-300 leading-relaxed">{{ $tutor->tutorDetails->bio }}</p>
                    </div>
                @endif

                <!-- CV Download -->
                @if($tutor->tutorDetails && $tutor->tutorDetails->cv_path)
                    <div class="px-8 pb-6">
                        <a href="{{ Storage::url($tutor->tutorDetails->cv_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-royal-500/20 text-royal-400 hover:bg-royal-500/30 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('site.download_cv') }}
                        </a>
                    </div>
                @endif

                <!-- [REQ] Qualifications Section -->
                @if($tutor->tutorDetails)
                    @php $td = $tutor->tutorDetails; @endphp
                    @if($td->university || $td->graduation_year || $td->skills || $td->portfolio_url || $td->degree_certificate_path)
                    <div class="px-8 pb-6">
                        <h4 class="text-sm font-medium text-luxury-400 mb-3 flex items-center gap-2">🎓 {{ __('site.qualifications') }}</h4>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @if($td->university)
                            <div class="p-4 rounded-xl bg-white/5">
                                <p class="text-luxury-400 text-xs">{{ __('site.university') }}</p>
                                <p class="text-white font-medium mt-1">{{ $td->university }}</p>
                            </div>
                            @endif
                            @if($td->graduation_year)
                            <div class="p-4 rounded-xl bg-white/5">
                                <p class="text-luxury-400 text-xs">{{ __('site.graduation_year') }}</p>
                                <p class="text-white font-medium mt-1">{{ $td->graduation_year }}</p>
                            </div>
                            @endif
                        </div>
                        @if($td->skills)
                        <div class="mt-4 p-4 rounded-xl bg-white/5">
                            <p class="text-luxury-400 text-xs mb-2">{{ __('site.skills_label') }}</p>
                            <p class="text-luxury-300 text-sm leading-relaxed">{{ $td->skills }}</p>
                        </div>
                        @endif
                        <div class="flex flex-wrap gap-3 mt-4">
                            @if($td->portfolio_url)
                            <a href="{{ $td->portfolio_url }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-500/20 text-purple-400 hover:bg-purple-500/30 transition text-sm font-medium">
                                🔗 {{ __('site.portfolio_url') }}
                            </a>
                            @endif
                            @if($td->degree_certificate_path)
                            <a href="{{ Storage::url($td->degree_certificate_path) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500/20 text-green-400 hover:bg-green-500/30 transition text-sm font-medium">
                                📄 {{ __('site.download_degree_certificate') }}
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif

                {{-- [v8.0] Eligibility & Historical Fairness Section --}}
                @if($tutor->tutorDetails && ($tutor->tutorDetails->gpa || $tutor->tutorDetails->step_score))
                    @php
                        $td = $tutor->tutorDetails;
                        $currentMinGpa = \App\Models\Setting::get('min_gpa', 0);
                        $currentMinStep = \App\Models\Setting::get('min_step_score', 0);
                    @endphp
                    <div class="px-8 pb-6">
                        <h4 class="text-sm font-medium text-luxury-400 mb-3 flex items-center gap-2">
                            📊 {{ __('site.eligibility_data') }}
                        </h4>
                        <div class="grid sm:grid-cols-3 gap-4">
                            @if($td->gpa)
                            <div class="p-4 rounded-xl bg-white/5">
                                <p class="text-luxury-400 text-xs">{{ __('site.gpa_label') }}</p>
                                @php
                                    $gpaColor = ($td->req_gpa_at_registration && $td->gpa >= $td->req_gpa_at_registration) ? 'text-green-400' : 'text-white';
                                @endphp
                                <p class="{{ $gpaColor }} font-bold text-lg mt-1">{{ $td->gpa }} / {{ $td->gpa_scale ?? '4.0' }}</p>
                                @if($td->req_gpa_at_registration)
                                    <p class="text-luxury-500 text-xs mt-2">
                                        {{ __('site.req_at_registration') }}: {{ $td->req_gpa_at_registration }}
                                        @if($currentMinGpa && $currentMinGpa != $td->req_gpa_at_registration)
                                            | {{ __('site.current_req') }}: {{ $currentMinGpa }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            @endif

                            @if($td->step_score)
                            <div class="p-4 rounded-xl bg-white/5">
                                <p class="text-luxury-400 text-xs">{{ __('site.step_score_label') }}</p>
                                @php
                                    $stepColor = ($td->req_step_at_registration && $td->step_score >= $td->req_step_at_registration) ? 'text-green-400' : 'text-white';
                                @endphp
                                <p class="{{ $stepColor }} font-bold text-lg mt-1">{{ $td->step_score }}</p>
                                @if($td->req_step_at_registration)
                                    <p class="text-luxury-500 text-xs mt-2">
                                        {{ __('site.req_at_registration') }}: {{ $td->req_step_at_registration }}
                                        @if($currentMinStep && $currentMinStep != $td->req_step_at_registration)
                                            | {{ __('site.current_req') }}: {{ $currentMinStep }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Message Tutor -->
                <div class="px-8 pb-6">
                    <a href="{{ route('messages.show', $tutor->id) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gold-500/20 text-gold-400 hover:bg-gold-500/30 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        {{ __('site.message_tutor') }}
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="px-8 pb-8 flex flex-wrap gap-4">
                    @if(!($tutor->tutorDetails && $tutor->tutorDetails->is_verified))
                        <!-- Verify Button -->
                        <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('site.verify_tutor') }}
                            </button>
                        </form>
                    @else
                        <!-- Revoke Verification Button -->
                        <form action="{{ route('admin.tutors.reject', $tutor) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 rounded-xl font-semibold border border-orange-500/30 text-orange-400 hover:bg-orange-500/10 transition inline-flex items-center gap-2"
                                onclick="return confirm('{{ __('site.confirm_revoke_tutor') }}')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                {{ __('site.revoke_verification') }}
                            </button>
                        </form>
                    @endif

                    <!-- Approve All Courses Button -->
                    @if($tutor->courses->count() > 0)
                        <form action="{{ route('admin.tutors.approveAllCourses', $tutor) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 rounded-xl font-semibold border border-green-500/30 text-green-400 hover:bg-green-500/10 transition inline-flex items-center gap-2"
                                onclick="return confirm('{{ __('site.confirm_approve_all_courses') }}')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('site.approve_all_courses') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Courses -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.tutor_courses') }}</h3>
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
                                                @if($course->price > 0) ${{ $course->price }} @else {{ __('site.free') }} @endif
                                            </span>
                                            <span class="text-luxury-500 text-sm">{{ $course->contents->count() }} {{ __('site.lessons_count') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($course->status === 'approved')
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">{{ __('site.status_approved') }}</span>
                                        @elseif($course->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">{{ __('site.status_pending_short') }}</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400">{{ __('site.status_rejected') }}</span>
                                        @endif
                                        <a href="{{ route('admin.courses.show', $course) }}" class="text-luxury-400 hover:text-gold-400 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.tutor_no_courses') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>