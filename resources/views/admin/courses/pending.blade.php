<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">كورسات بانتظار الموافقة</h2>
                <p class="text-luxury-400 text-sm mt-1">مراجعة الكورسات الجديدة قبل نشرها</p>
            </div>
            <a href="{{ route('admin.courses.index') }}"
                class="text-gold-400 hover:text-gold-300 text-sm font-medium transition flex items-center gap-2">
                ← العودة لجميع الكورسات
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden shadow-luxury">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">الكورسات المعلقة</h3>
                </div>

                @if($courses->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($courses as $course)
                            <div class="p-6 hover:bg-white/5 transition group">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-16 h-16 rounded-xl bg-gradient-to-br from-royal-500/20 to-royal-600/20 flex items-center justify-center overflow-hidden border border-white/10">
                                            @if($course->thumbnail)
                                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-8 h-8 text-royal-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-white group-hover:text-gold-400 transition">
                                                {{ $course->title }}</h4>
                                            <div class="flex items-center gap-3 mt-1 text-sm text-luxury-400">
                                                <span>بواسطة: {{ $course->tutor?->name ?? 'معلم محذوف' }}</span>
                                                <span class="w-1 h-1 rounded-full bg-luxury-600"></span>
                                                <span>السعر: ${{ $course->price }}</span>
                                                <span class="w-1 h-1 rounded-full bg-luxury-600"></span>
                                                <span>التاريخ: {{ $course->created_at->format('Y/m/d') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.courses.show', $course) }}"
                                            class="px-4 py-2 rounded-xl bg-white/5 text-white hover:bg-white/10 transition text-sm font-medium">
                                            تفاصيل
                                        </a>
                                        <form action="{{ route('admin.courses.approve', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 rounded-xl bg-green-500/20 text-green-400 hover:bg-green-500/30 transition text-sm font-bold">
                                                موافقة
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.courses.reject', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 rounded-xl bg-red-500/20 text-red-400 hover:bg-red-500/30 transition text-sm font-bold"
                                                onclick="return confirm('هل أنت متأكد؟')">
                                                رفض
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-6 border-t border-white/5">
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="p-20 text-center">
                        <div
                            class="w-20 h-20 rounded-full bg-luxury-900/50 flex items-center justify-center mx-auto mb-4 border border-white/5">
                            <svg class="w-10 h-10 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400 text-lg">لا توجد كورسات بانتظار الموافقة حالياً</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>