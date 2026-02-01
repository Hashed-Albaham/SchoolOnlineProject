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
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-luxury-400">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p>لا يوجد فيديو لهذا الدرس</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Content Info -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-white mb-2">{{ $currentContent->title }}</h3>
                                @if($currentContent->description)
                                    <p class="text-luxury-300 leading-relaxed">{{ $currentContent->description }}</p>
                                @endif

                                <!-- Navigation -->
                                <div class="flex items-center justify-between mt-6 pt-6 border-t border-white/5">
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
                                            <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
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
                                    <svg class="w-20 h-20 mx-auto mb-4 text-luxury-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0V2m0 2h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2m-10 0V4">
                                        </path>
                                    </svg>
                                    <p class="text-luxury-400">لا يوجد محتوى في هذا الكورس بعد</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar: Content List -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden sticky top-24">
                        <div class="p-4 border-b border-white/5">
                            <h3 class="font-semibold text-white">محتوى الكورس</h3>
                            <p class="text-sm text-luxury-400 mt-1">{{ $course->contents->count() }} درس</p>
                        </div>

                        <div class="max-h-[60vh] overflow-y-auto">
                            @foreach($course->contents->sortBy('order') as $index => $content)
                                <a href="{{ route('student.courses.watch', [$course, $content]) }}"
                                    class="block p-4 border-b border-white/5 hover:bg-white/5 transition
                                            {{ $currentContent && $currentContent->id === $content->id ? 'bg-gold-500/10 border-r-4 border-r-gold-500' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                                {{ $currentContent && $currentContent->id === $content->id ? 'bg-gold-500/30 text-gold-400' : 'bg-white/5 text-luxury-400' }}">
                                            @if($currentContent && $currentContent->id === $content->id)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <span class="text-sm">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="font-medium truncate
                                                    {{ $currentContent && $currentContent->id === $content->id ? 'text-gold-400' : 'text-white' }}">
                                                {{ $content->title }}
                                            </p>
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