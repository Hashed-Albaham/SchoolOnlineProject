<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.my_courses') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.courses_enrolled_in') }}</p>
            </div>
            <a href="{{ route('student.courses.index') }}" class="btn-premium px-6 py-3 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('site.discover_more') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(isset($enrollments) && $enrollments->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enrollment)
                        @if($enrollment->course)
                        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden card-luxury">
                            <!-- Thumbnail -->
                            <div class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative">
                                @if($enrollment->course->thumbnail)
                                    <img src="{{ Storage::url($enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-luxury-900/80 to-transparent"></div>
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 flex flex-col gap-2 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }}">
                                    @if($enrollment->payment_status === 'paid')
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/90 text-white font-medium">{{ __('site.payment_paid') }}</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/90 text-luxury-900 font-medium">{{ __('site.pending_payment') }}</span>
                                    @endif
                                    
                                    @if($enrollment->enrollment_status === 'approved')
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/90 text-white font-medium">{{ __('site.approved') }}</span>
                                    @elseif($enrollment->enrollment_status === 'pending_approval')
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/90 text-luxury-900 font-medium">{{ __('site.pending_approval') }}</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/90 text-white font-medium">{{ __('site.rejected') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-semibold text-white line-clamp-2 mb-2 min-h-[3rem]">{{ $enrollment->course->title }}</h3>
                                
                                <div class="flex items-center gap-2 mb-4">
                                    <x-avatar :user="$enrollment->course->tutor" sizeClasses="w-6 h-6" iconClasses="w-3 h-3" />
                                    <span class="text-sm text-luxury-400">{{ $enrollment->course->tutor?->name ?? __('site.tutor_label') }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm text-luxury-400 mb-4">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $enrollment->course->contents_count ?? 0 }} {{ __('site.lesson_unit') }}</span>
                                    </div>
                                    <span class="text-gold-400">{{ __('site.enrolled_on') }} {{ $enrollment->created_at->format('Y/m/d') }}</span>
                                </div>
                                
                                @if($enrollment->canAccess())
                                    <a href="{{ route('student.courses.watch', $enrollment->course) }}" 
                                        class="w-full block text-center py-3 rounded-xl bg-gold-gradient text-luxury-900 font-semibold hover:shadow-glow transition">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('site.continue_learning') }}
                                        </span>
                                    </a>
                                @elseif($enrollment->payment_status === 'paid' && $enrollment->enrollment_status === 'pending_approval')
                                    <div class="w-full block text-center py-3 rounded-xl bg-luxury-800 text-luxury-400 font-semibold border border-white/5 cursor-not-allowed">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('site.enrollment_pending_approval') }}
                                        </span>
                                    </div>
                                @else
                                    <a href="{{ route('student.enrollment.payment', $enrollment) }}" 
                                        class="w-full block text-center py-3 rounded-xl bg-yellow-500/20 text-yellow-400 font-semibold hover:bg-yellow-500/30 transition">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ __('site.complete_payment') }}
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @else
                            <!-- Skip broken enrollment -->
                        @endif
                    @endforeach
                </div>
                
                @if($enrollments->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $enrollments->links() }}
                    </div>
                @endif
            @else
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">{{ __('site.no_enrolled_yet') }}</h4>
                    <p class="text-luxury-400 mb-6">{{ __('site.start_learning_journey') }}</p>
                    <a href="{{ route('student.courses.index') }}" class="btn-premium px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                        {{ __('site.browse_courses') }}
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
