<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">استكشف الكورسات</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ $courses->total() ?? 0 }} كورس متاح للتعلم</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Search & Filter -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-8">
                <form action="{{ route('student.courses.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن كورس..." 
                            class="w-full pr-12 pl-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-0 transition">
                    </div>
                    <select name="sort" class="px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-0">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>الأحدث</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>الأقدم</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                    </select>
                    <button type="submit" class="btn-premium px-6 py-3 rounded-xl font-semibold">
                        بحث
                    </button>
                </form>
            </div>
            
            <!-- Courses Grid -->
            @if(isset($courses) && $courses->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($courses as $course)
                        <a href="{{ route('student.courses.show', $course) }}" class="group">
                            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden card-luxury">
                                <!-- Thumbnail -->
                                <div class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative overflow-hidden">
                                    @if($course->thumbnail)
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <svg class="w-12 h-12 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-luxury-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    <!-- Price Badge -->
                                    <div class="absolute top-3 left-3">
                                        @if($course->price > 0)
                                            <span class="px-3 py-1.5 text-sm rounded-lg bg-gold-500/90 text-luxury-900 font-bold">${{ $course->price }}</span>
                                        @else
                                            <span class="px-3 py-1.5 text-sm rounded-lg bg-green-500/90 text-white font-bold">مجاني</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="p-5">
                                    <h3 class="font-semibold text-white line-clamp-2 mb-2 group-hover:text-gold-400 transition min-h-[3rem]">{{ $course->title }}</h3>
                                    
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center">
                                            <span class="text-white text-xs font-semibold">{{ substr($course->tutor->name ?? 'M', 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm text-luxury-400">{{ $course->tutor->name ?? 'المعلم' }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-1 text-luxury-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $course->contents->count() ?? 0 }} درس</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-luxury-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span>{{ $course->enrollments->count() ?? 0 }} طالب</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($courses->hasPages())
                    <div class="flex justify-center">
                        {{ $courses->links() }}
                    </div>
                @endif
            @else
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">لا توجد كورسات</h4>
                    <p class="text-luxury-400">
                        @if(request('search'))
                            لم نجد نتائج تطابق بحثك "{{ request('search') }}"
                        @else
                            لا توجد كورسات متاحة حالياً، تابعنا لمزيد من المحتوى قريباً!
                        @endif
                    </p>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
