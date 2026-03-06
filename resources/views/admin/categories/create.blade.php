<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.add_category') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.add_category_desc') }}</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-luxury-300 text-sm hover:bg-luxury-700 transition">
                ← {{ __('site.back_to_categories') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-8">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.category_name') }} ({{ __('site.english') }})</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50"
                                placeholder="e.g. Programming">
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.category_name_ar') }}</label>
                            <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50"
                                placeholder="مثال: البرمجة" dir="rtl">
                            @error('name_ar') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.description') }} ({{ __('site.optional') }})</label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.icon') }} ({{ __('site.optional') }})</label>
                                <input type="text" name="icon" value="{{ old('icon') }}"
                                    class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50"
                                    placeholder="💻">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.order') }}</label>
                                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                                    class="w-full px-4 py-3 rounded-xl bg-luxury-700/50 border border-white/10 text-white focus:outline-none focus:border-gold-500/50">
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                class="w-5 h-5 rounded bg-luxury-700 border-white/20 text-gold-500 focus:ring-gold-500/50">
                            <label for="is_active" class="text-sm text-luxury-300">{{ __('site.category_active') }}</label>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-luxury-900 font-semibold hover:from-gold-400 hover:to-gold-500 transition shadow-lg">
                                {{ __('site.create') }}
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="text-luxury-400 hover:text-white transition">{{ __('site.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
