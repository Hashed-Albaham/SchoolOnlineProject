<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.courses.my') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-bold text-white truncate">{{ $course->title }}</h2>
                <p class="text-luxury-400 text-sm">{{ $course->tutor->name ?? 'المعلم' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Progress Bar --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-white font-medium">تقدمك في الكورس</span>
                    <span class="text-gold-400 font-bold">{{ $completedContents }}/{{ $totalContents }} درس ({{ $progressPercent }}%)</span>
                </div>
                <div class="h-3 bg-luxury-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-gold-500 to-gold-400 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
                
                {{-- Certificate Section --}}
                @if($canRequestCertificate)
                    <div class="mt-4 pt-4 border-t border-white/10">
                        @if($certificateRequest)
                            @if($certificateRequest->isApproved())
                                <div class="flex items-center gap-3 p-3 bg-green-500/10 rounded-xl border border-green-500/20">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-green-400 font-bold">🎉 تم إصدار الشهادة!</p>
                                        <p class="text-green-300 text-sm">رقم الشهادة: {{ $certificateRequest->certificate_code }}</p>
                                    </div>
                                </div>
                            @elseif($certificateRequest->isPending())
                                <div class="flex items-center gap-3 p-3 bg-yellow-500/10 rounded-xl border border-yellow-500/20">
                                    <svg class="w-6 h-6 text-yellow-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <div>
                                        <p class="text-yellow-400 font-medium">طلب الشهادة قيد المراجعة</p>
                                        <p class="text-yellow-300 text-sm">سيتم إشعارك عند الموافقة</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-red-400 text-sm">تم رفض طلب الشهادة: {{ $certificateRequest->rejection_reason }}</div>
                            @endif
                        @else
                            <form action="{{ route('student.courses.certificate.request', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold hover:shadow-lg hover:shadow-green-500/30 transition">
                                    🏆 طلب الشهادة - لقد أكملت جميع الدروس!
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Video Player -->
                <div class="lg:col-span-2">
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                        @if($currentContent)
                            <!-- Video -->
                            <div class="aspect-video bg-black">
                                @if($currentContent->youtube_video_id)
                                    <iframe class="w-full h-full" src="{{ $currentContent->embed_url }}"
                                        title="{{ $currentContent->title }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @elseif($currentContent->isImage())
                                    <img src="{{ Storage::url($currentContent->file_path) }}" alt="{{ $currentContent->title }}" class="w-full h-full object-contain">
                                @elseif($currentContent->isFile())
                                    <div class="w-full h-full flex items-center justify-center">
                                        <a href="{{ Storage::url($currentContent->file_path) }}" target="_blank" class="px-6 py-3 bg-gold-500 text-luxury-900 rounded-xl font-bold hover:bg-gold-400 transition">
                                            📄 تحميل الملف
                                        </a>
                                    </div>
                                @elseif($currentContent->isText())
                                    <div class="w-full h-full overflow-auto p-6 text-white">
                                        {!! nl2br(e($currentContent->text_content)) !!}
                                    </div>
                                @elseif($currentContent->isLink())
                                    <div class="w-full h-full flex items-center justify-center">
                                        <a href="{{ $currentContent->link_url }}" target="_blank" class="px-6 py-3 bg-royal-500 text-white rounded-xl font-bold hover:bg-royal-400 transition">
                                            🔗 فتح الرابط الخارجي
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-luxury-400">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <p>محتوى الدرس</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Content Info -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold text-white">{{ $currentContent->title }}</h3>
                                    @if($isCurrentCompleted)
                                        <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-sm font-medium">
                                            ✓ مكتمل
                                        </span>
                                    @endif
                                </div>
                                
                                @if($currentContent->description)
                                    <p class="text-luxury-300 leading-relaxed mb-4">{{ $currentContent->description }}</p>
                                @endif

                                {{-- Mark Complete Button --}}
                                @if(!$isCurrentCompleted)
                                    <form action="{{ route('student.courses.content.complete', [$course, $currentContent]) }}" method="POST" class="mb-4">
                                        @csrf
                                        <button type="submit" class="w-full py-3 rounded-xl bg-gold-gradient text-luxury-900 font-bold hover:shadow-glow transition">
                                            ✓ أنهيت هذا الدرس
                                        </button>
                                    </form>
                                @endif

                                <!-- Navigation -->
                                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                    @php
                                        $contents = $course->contents->sortBy('order');
                                        $currentIndex = $contents->search(function ($item) use ($currentContent) {
                                            return $item->id === $currentContent->id;
                                        });
                                        $prevContent = $currentIndex > 0 ? $contents->values()[$currentIndex - 1] : null;
                                        $nextContent = $currentIndex < $contents->count() - 1 ? $contents->values()[$currentIndex + 1] : null;
                                    @endphp

                                    @if($prevContent)
                                        <a href="{{ route('student.courses.watch', [$course, $prevContent]) }}"
                                            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 text-luxury-300 hover:bg-white/10 hover:text-white transition">
                                            <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span>الدرس السابق</span>
                                        </a>
                                    @else
                                        <div></div>
                                    @endif

                                    @if($nextContent)
                                        <a href="{{ route('student.courses.watch', [$course, $nextContent]) }}"
                                            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gold-500/20 text-gold-400 hover:bg-gold-500/30 transition">
                                            <span>الدرس التالي</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="px-4 py-2 rounded-xl bg-green-500/20 text-green-400 text-sm font-medium">
                                            🎉 أنت في الدرس الأخير!
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="aspect-video flex items-center justify-center bg-luxury-900/50">
                                <div class="text-center">
                                    <svg class="w-20 h-20 mx-auto mb-4 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0V2m0 2h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2m-10 0V4"></path>
                                    </svg>
                                    <p class="text-luxury-400">لا يوجد محتوى في هذا الكورس بعد</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar: Content List -->
                <div class="lg:col-span-1">
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden sticky top-24">
                        <div class="p-4 border-b border-white/5">
                            <h3 class="font-semibold text-white">محتوى الكورس</h3>
                            <p class="text-sm text-luxury-400 mt-1">{{ $completedContents }}/{{ $totalContents }} درس مكتمل</p>
                        </div>

                        <div class="max-h-[60vh] overflow-y-auto">
                            @foreach($course->contents->sortBy('order') as $index => $content)
                                @php
                                    $isCompleted = auth()->user()->hasCompletedContent($content->id);
                                @endphp
                                <a href="{{ route('student.courses.watch', [$course, $content]) }}"
                                    class="block p-4 border-b border-white/5 hover:bg-white/5 transition
                                            {{ $currentContent && $currentContent->id === $content->id ? 'bg-gold-500/10 border-r-4 border-r-gold-500' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                            {{ $isCompleted ? 'bg-green-500/30 text-green-400' : ($currentContent && $currentContent->id === $content->id ? 'bg-gold-500/30 text-gold-400' : 'bg-white/5 text-luxury-400') }}">
                                            @if($isCompleted)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($currentContent && $currentContent->id === $content->id)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <span class="text-sm">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium truncate {{ $isCompleted ? 'text-green-400' : ($currentContent && $currentContent->id === $content->id ? 'text-gold-400' : 'text-white') }}">
                                                {{ $content->title }}
                                            </p>
                                            <span class="text-xs {{ $isCompleted ? 'text-green-500' : 'text-luxury-500' }}">
                                                {{ $content->type_label }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>