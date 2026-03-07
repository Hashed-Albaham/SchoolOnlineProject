<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.courses.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.create_course') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.share_knowledge_world') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form action="{{ route('tutor.courses.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.course_title') }} <span
                                class="text-red-400">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.course_title_placeholder') }}">
                        @error('title')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.course_description') }} <span
                                class="text-red-400">*</span></label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition resize-none"
                            placeholder="{{ __('site.course_description_placeholder') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.course_price') }}
                            ({{ __('site.currency') }})</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-luxury-500">$</span>
                            </div>
                            <input type="number" id="price" name="price" value="{{ old('price', 0) }}" min="0"
                                step="0.01"
                                class="w-full pr-10 pl-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ __('site.free_course_placeholder') }}">
                        </div>
                        <p class="text-luxury-500 text-xs mt-2">{{ __('site.leave_zero_for_free') }}</p>
                        @error('price')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.category') }}</label>
                        <select id="category_id" name="category_id"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition [&>option]:bg-luxury-800 [&>option]:text-white">
                            <option value="">{{ __('site.select_category') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->localized_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Thumbnail -->
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.course_thumbnail') }}</label>
                        <div class="relative">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 file:font-medium file:cursor-pointer focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition">
                        </div>
                        <p class="text-luxury-500 text-xs mt-2">{{ __('site.thumbnail_hint') }}</p>
                        @error('thumbnail')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/20">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-blue-400 font-medium">{{ __('site.note') }}</p>
                                <p class="text-luxury-400 text-sm mt-1">{{ __('site.create_course_note') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="btn-premium flex-1 py-4 rounded-xl font-semibold text-lg">
                            {{ __('site.create_course') }}
                        </button>
                        <a href="{{ route('tutor.courses.index') }}"
                            class="px-6 py-4 rounded-xl font-semibold border border-white/10 text-luxury-400 hover:bg-white/5 transition">
                            {{ __('site.cancel') }}
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>