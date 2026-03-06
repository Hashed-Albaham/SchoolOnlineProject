<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.edit_course') }}: {{ $course->title }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.by_tutor') }}: {{ $course->tutor->name ?? '-' }}</p>
            </div>
            <a href="{{ route('admin.courses.show', $course) }}" class="px-4 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-luxury-300 text-sm hover:bg-luxury-700 transition">
                ← {{ __('site.back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="space-y-6">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.course_title') }}</label>
                            <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                            @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.description') }}</label>
                            <textarea name="description" rows="5" required
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">{{ old('description', $course->description) }}</textarea>
                            @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Price --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.price') }} ($)</label>
                            <input type="number" name="price" value="{{ old('price', $course->price) }}" min="0" step="0.01" required
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                            @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.status') }}</label>
                            <select name="status" class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                                <option value="pending" {{ old('status', $course->status) == 'pending' ? 'selected' : '' }}>{{ __('site.pending') }}</option>
                                <option value="approved" {{ old('status', $course->status) == 'approved' ? 'selected' : '' }}>{{ __('site.approved') }}</option>
                                <option value="rejected" {{ old('status', $course->status) == 'rejected' ? 'selected' : '' }}>{{ __('site.rejected') }}</option>
                            </select>
                        </div>

                        {{-- Thumbnail --}}
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.thumbnail') }}</label>
                            @if($course->thumbnail)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="thumbnail" class="w-32 h-20 object-cover rounded-lg">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" accept="image/*"
                                class="w-full text-sm text-luxury-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold-500/20 file:text-gold-400 hover:file:bg-gold-500/30">
                            @error('thumbnail') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-luxury-900 font-semibold hover:from-gold-400 hover:to-gold-500 transition shadow-lg">
                                {{ __('site.save_changes') }}
                            </button>
                            <a href="{{ route('admin.courses.show', $course) }}" class="text-luxury-400 hover:text-white transition">{{ __('site.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
