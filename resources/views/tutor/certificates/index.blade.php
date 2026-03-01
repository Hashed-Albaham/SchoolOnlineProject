<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.manage_certificates') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.certificates_page_desc') }}</p>
            </div>
            <a href="{{ route('tutor.dashboard') }}"
                class="text-gold-400 hover:text-gold-300 text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ __('site.back_to_dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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

            <!-- Status Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('tutor.certificates.index', array_merge(request()->except('status', 'page'))) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ !$status ? 'bg-gold-gradient text-luxury-900' : 'bg-white/5 text-luxury-300 hover:bg-white/10 border border-white/10' }}">
                    {{ __('site.all') }} <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
                </a>
                <a href="{{ route('tutor.certificates.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ $status === 'pending' ? 'bg-yellow-500/30 text-yellow-300 border border-yellow-500/50' : 'bg-white/5 text-luxury-300 hover:bg-white/10 border border-white/10' }}">
                    {{ __('site.pending') }} <span class="ml-1 opacity-70">({{ $counts['pending'] }})</span>
                </a>
                <a href="{{ route('tutor.certificates.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ $status === 'approved' ? 'bg-green-500/30 text-green-300 border border-green-500/50' : 'bg-white/5 text-luxury-300 hover:bg-white/10 border border-white/10' }}">
                    {{ __('site.issued') }} <span class="ml-1 opacity-70">({{ $counts['approved'] }})</span>
                </a>
                <a href="{{ route('tutor.certificates.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ $status === 'rejected' ? 'bg-red-500/30 text-red-300 border border-red-500/50' : 'bg-white/5 text-luxury-300 hover:bg-white/10 border border-white/10' }}">
                    {{ __('site.rejected') }} <span class="ml-1 opacity-70">({{ $counts['rejected'] }})</span>
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 mb-6">
                <form action="{{ route('tutor.certificates.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <div class="flex-1 min-w-[200px]">
                        <select name="course_id"
                            class="w-full bg-luxury-900 text-white text-sm rounded-xl border border-white/10 focus:ring-1 focus:ring-gold-500 py-2.5 px-4">
                            <option value="">{{ __('site.all_courses') }}</option>
                            @foreach($allCourses as $course)
                                <option value="{{ $course->id }}" {{ $courseFilter == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[180px]">
                        <select name="sort"
                            class="w-full bg-luxury-900 text-white text-sm rounded-xl border border-white/10 focus:ring-1 focus:ring-gold-500 py-2.5 px-4">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>{{ __('site.newest_first') }}</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>{{ __('site.oldest_first') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gold-gradient text-luxury-900 font-bold text-sm hover:shadow-lg hover:shadow-gold-500/20 transition">
                        {{ __('site.filter') }}
                    </button>
                    <a href="{{ route('tutor.certificates.index') }}" class="text-luxury-400 hover:text-white text-sm transition">
                        {{ __('site.reset') }}
                    </a>
                </form>
            </div>

            <!-- Certificates List -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($certificates->total() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($certificates as $cert)
                            <div class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-white/5 transition">
                                <!-- Student Info -->
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shrink-0">
                                        <span class="text-white font-bold text-sm">{{ mb_substr($cert->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ $cert->user->name ?? '-' }}</p>
                                        <p class="text-xs text-luxury-400 truncate">{{ $cert->course->title ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- Status & Date -->
                                <div class="flex items-center gap-3 shrink-0">
                                    @if($cert->status === 'pending')
                                        <span class="px-3 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400 font-semibold">{{ __('site.pending') }}</span>
                                    @elseif($cert->status === 'approved')
                                        <span class="px-3 py-1 text-xs rounded-lg bg-green-500/20 text-green-400 font-semibold">{{ __('site.issued') }}</span>
                                    @else
                                        <span class="px-3 py-1 text-xs rounded-lg bg-red-500/20 text-red-400 font-semibold">{{ __('site.rejected') }}</span>
                                    @endif
                                    <span class="text-xs text-luxury-500">{{ $cert->created_at->diffForHumans() }}</span>
                                    @if($cert->certificate_code)
                                        <span class="text-xs font-mono text-gold-400">{{ $cert->certificate_code }}</span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($cert->status === 'pending')
                                        <form action="{{ route('tutor.certificates.issue', $cert) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition text-xs font-bold">
                                                {{ __('site.issue_certificate') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('tutor.certificates.reject', $cert) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition text-xs font-bold"
                                                onclick="return confirm('{{ __('site.are_you_sure') }}')">
                                                {{ __('site.reject') }}
                                            </button>
                                        </form>
                                    @elseif($cert->status === 'approved')
                                        <form action="{{ route('tutor.certificates.revoke', $cert) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition text-xs font-bold"
                                                onclick="return confirm('{{ __('site.confirm_revoke_certificate') }}')">
                                                {{ __('site.revoke_certificate') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($certificates->hasPages())
                        <div class="px-6 py-4 border-t border-white/5">
                            {{ $certificates->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-16 text-center">
                        <div class="w-20 h-20 rounded-full bg-purple-500/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400 text-lg mb-1">{{ __('site.no_certificate_requests') }}</p>
                        <p class="text-luxury-500 text-sm">{{ __('site.certificates_will_appear_here') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
