<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.admin_dashboard') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.admin_welcome', ['name' => auth()->user()->name]) }}
                </p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gold-500/10 border border-gold-500/20">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-gold-400 text-sm font-medium">{{ __('site.system_operational') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">{{ __('site.total_users') }}</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_users'] ?? 0 }}</p>
                            <p class="text-green-400 text-xs mt-1">
                                <span class="inline-flex items-center">
                                    <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('site.active_users') }}
                                </span>
                            </p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Students -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">{{ __('site.students') }}</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_students'] ?? 0 }}</p>
                            <p class="text-luxury-500 text-xs mt-1">{{ __('site.registered_students') }}</p>
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

                <!-- Tutors -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">{{ __('site.tutors') }}</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_tutors'] ?? 0 }}</p>
                            <p class="text-luxury-500 text-xs mt-1">{{ __('site.active_tutors') }}</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-royal-500 to-royal-600 flex items-center justify-center shadow-lg shadow-royal-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Courses -->
                <div class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm font-medium">{{ __('site.courses') }}</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_courses'] ?? 0 }}</p>
                            <p class="text-luxury-500 text-xs mt-1">{{ __('site.available_courses') }}</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center shadow-lg shadow-gold-500/20">
                            <svg class="w-7 h-7 text-luxury-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                <!-- Pending Tutors -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">{{ __('site.pending_tutors_req') }}</h3>
                                <p class="text-sm text-luxury-400">{{ $stats['pending_tutors'] ?? 0 }}
                                    {{ __('site.new_requests') }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.tutors.pending') }}"
                            class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                            {{ __('site.show_all') }} ←
                        </a>
                    </div>
                    <div class="p-6">
                        @if(isset($pendingTutors) && $pendingTutors->count() > 0)
                            <div class="space-y-4">
                                @foreach($pendingTutors->take(3) as $tutor)
                                    <div
                                        class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center">
                                                <span class="text-white font-semibold">{{ substr($tutor->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ $tutor->name }}</p>
                                                <p class="text-sm text-luxury-400">
                                                    {{ $tutor->tutorDetails->specialization ?? 'غير محدد' }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.tutors.show', $tutor) }}"
                                            class="px-3 py-1.5 rounded-lg bg-gold-500/20 text-gold-400 text-sm font-medium hover:bg-gold-500/30 transition">
                                            {{ __('site.review') }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-luxury-400">{{ __('site.no_pending_requests') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pending Courses -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">{{ __('site.pending_courses_req') }}</h3>
                                <p class="text-sm text-luxury-400">{{ $stats['pending_courses'] ?? 0 }}
                                    {{ __('site.new_courses_count') }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.courses.pending') }}"
                            class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                            {{ __('site.show_all') }} ←
                        </a>
                    </div>
                    <div class="p-6">
                        @if(isset($pendingCourses) && $pendingCourses->count() > 0)
                            <div class="space-y-4">
                                @foreach($pendingCourses->take(3) as $course)
                                    <div
                                        class="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition">
                                        <div>
                                            <p class="font-medium text-white">{{ Str::limit($course->title, 30) }}</p>
                                            <p class="text-sm text-luxury-400">{{ __('site.by_tutor') }}:
                                                {{ $course->tutor->name }}</p>
                                        </div>
                                        <a href="{{ route('admin.courses.show', $course) }}"
                                            class="px-3 py-1.5 rounded-lg bg-gold-500/20 text-gold-400 text-sm font-medium hover:bg-gold-500/30 transition">
                                            {{ __('site.review') }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-luxury-400">{{ __('site.no_pending_courses') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-6">{{ __('site.quick_actions_title') }}</h3>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.tutors.index') }}"
                        class="flex items-center gap-4 p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent hover:border-gold-500/20 transition group">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ __('site.manage_tutors') }}</p>
                            <p class="text-sm text-luxury-400">{{ __('site.manage_tutors_desc') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.courses.index') }}"
                        class="flex items-center gap-4 p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent hover:border-gold-500/20 transition group">
                        <div
                            class="w-12 h-12 rounded-xl bg-royal-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ __('site.manage_courses') }}</p>
                            <p class="text-sm text-luxury-400">{{ __('site.manage_courses_desc') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.tutors.pending') }}"
                        class="flex items-center gap-4 p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent hover:border-gold-500/20 transition group">
                        <div
                            class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ __('site.verify_identities') }}</p>
                            <p class="text-sm text-luxury-400">{{ __('site.review_requests') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-4 p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent hover:border-gold-500/20 transition group">
                        <div
                            class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ __('site.settings') }}</p>
                            <p class="text-sm text-luxury-400">{{ __('site.account_settings') }}</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>