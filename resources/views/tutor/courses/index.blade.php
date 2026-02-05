<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">كورساتي</h2>
                <p class="text-luxury-400 text-sm mt-1">إدارة وتعديل الكورسات الخاصة بك</p>
            </div>
            <a href="{{ route('tutor.courses.create') }}" class="btn-premium px-6 py-3 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إنشاء كورس جديد
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(isset($courses) && $courses->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden card-luxury">
                            <!-- Thumbnail -->
                            <div class="aspect-video bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center relative">
                                @if($course->thumbnail)
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($course->status === 'approved')
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/90 text-white font-medium">معتمد</span>
                                    @elseif($course->status === 'pending')
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/90 text-luxury-900 font-medium">قيد المراجعة</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/90 text-white font-medium">مرفوض</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-semibold text-white line-clamp-2 mb-2 min-h-[3rem]">{{ $course->title }}</h3>
                                
                                <div class="flex items-center justify-between text-sm mb-4">
                                    <span class="text-gold-400 font-bold">
                                        @if($course->price > 0) ${{ $course->price }} @else مجاني @endif
                                    </span>
                                    <span class="text-luxury-400">{{ $course->contents_count ?? 0 }} درس</span>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm text-luxury-400 mb-4">
                                    <a href="{{ route('tutor.courses.show', $course) }}" class="hover:text-gold-400 transition">
                                        {{ $course->enrollments_count ?? 0 }} طالب ←
                                    </a>
                                    <span>{{ $course->created_at->format('Y/m/d') }}</span>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('tutor.courses.edit', $course) }}" 
                                        class="flex-1 text-center py-2.5 rounded-xl bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                        تعديل
                                    </a>
                                    <a href="{{ route('tutor.courses.edit', $course) }}#content-section" 
                                        class="flex-1 text-center py-2.5 rounded-xl bg-gold-500/20 text-gold-400 text-sm font-medium hover:bg-gold-500/30 transition">
                                        المحتوى
                                    </a>
                                    <form action="{{ route('tutor.courses.destroy', $course) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="p-2.5 rounded-xl bg-red-500/20 text-red-400 hover:bg-red-500/30 transition"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا الكورس؟')">
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
                
                @if($courses->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $courses->links() }}
                    </div>
                @endif
            @else
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-royal-500/10 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">لم تنشئ أي كورسات بعد</h4>
                    <p class="text-luxury-400 mb-6">شارك معرفتك مع الآخرين وابدأ بإنشاء أول كورس لك</p>
                    <a href="{{ route('tutor.courses.create') }}" class="btn-premium px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                        إنشاء كورس جديد
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
