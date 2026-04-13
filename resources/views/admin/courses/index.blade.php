<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.manage_courses') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.review_approve_courses_desc') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.categories.index') }}" 
                    class="px-4 py-2 rounded-xl text-sm font-medium border border-royal-500/30 text-royal-400 hover:bg-royal-500/10 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    {{ __('site.categories') }}
                </a>
                <a href="{{ route('admin.courses.pending') }}" 
                    class="px-4 py-2 rounded-xl text-sm font-medium border border-yellow-500/30 text-yellow-400 hover:bg-yellow-500/10 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('site.pending_approval_count', ['count' => $pendingCount ?? 0]) }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.all_courses') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $allCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.approved_courses_label') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $approvedCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.pending_approval_label') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $pendingCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.rejected_courses_label') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $rejectedCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Courses Grid -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.all_courses') }}</h3>
                </div>
                
                @if(isset($courses) && $courses->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($courses as $course)
                            <div class="bg-white/5 rounded-2xl overflow-hidden group hover:bg-white/10 transition card-luxury">
                                <div class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative">
                                    @if($course->thumbnail)
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-12 h-12 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }}">
                                        @if($course->status === 'approved')
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/90 text-white font-medium">{{ __('site.status_approved') }}</span>
                                        @elseif($course->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/90 text-luxury-900 font-medium">{{ __('site.status_pending') }}</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/90 text-white font-medium">{{ __('site.status_rejected') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="p-5">
                                    <h4 class="font-semibold text-white line-clamp-1 mb-2">{{ $course->title }}</h4>
                                    <p class="text-sm text-luxury-400 line-clamp-2 mb-4">{{ $course->description }}</p>
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center">
                                                <span class="text-white text-xs font-semibold">{{ substr($course->tutor->name ?? 'M', 0, 1) }}</span>
                                            </div>
                                            <span class="text-sm text-luxury-400">{{ $course->tutor->name ?? __('site.tutor_label') }}</span>
                                        </div>
                                        <span class="text-gold-400 font-bold">
                                            @if($course->price > 0) {{ $course->price }} {{ \App\Models\Setting::get('currency_symbol', 'ر.س') }} @else {{ __('site.free') }} @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.courses.show', $course) }}" 
                                            class="flex-1 text-center py-2.5 rounded-xl bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                            {{ __('site.view_details') }}
                                        </a>
                                        @if($course->status === 'pending')
                                            <form action="{{ route('admin.courses.approve', $course) }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="p-2.5 rounded-xl bg-green-500/20 text-green-400 hover:bg-green-500/30 transition" title="{{ __('site.approve_action') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.courses.reject', $course) }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="p-2.5 rounded-xl bg-red-500/20 text-red-400 hover:bg-red-500/30 transition" title="{{ __('site.reject_action') }}"
                                                    onclick="return confirm('{{ __('site.confirm_reject_course') }}')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($courses->hasPages())
                        <div class="p-6 border-t border-white/5">
                            {{ $courses->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400">{{ __('site.no_courses_currently') }}</p>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</x-app-layout>
