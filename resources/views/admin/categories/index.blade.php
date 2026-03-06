<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.manage_categories') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.manage_categories_desc') }}</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-luxury-900 font-semibold hover:from-gold-400 hover:to-gold-500 transition shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('site.add_category') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.all_categories') }} ({{ $categories->total() }})</h3>
                </div>

                @if($categories->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.order') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.category_name') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.category_name_ar') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.courses') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.status') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($categories as $category)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $category->order }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($category->icon)
                                                    <span class="text-xl">{{ $category->icon }}</span>
                                                @else
                                                    <div class="w-8 h-8 rounded-lg bg-royal-500/20 flex items-center justify-center">
                                                        <span class="text-royal-400 font-semibold text-sm">{{ mb_substr($category->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <span class="font-medium text-white">{{ $category->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">{{ $category->name_ar ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-royal-500/20 text-royal-400">
                                                {{ $category->courses_count }} {{ __('site.course_unit') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($category->is_active)
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">{{ __('site.active') }}</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400">{{ __('site.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1.5 rounded-lg bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                                    {{ __('site.edit') }}
                                                </a>
                                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('site.confirm_delete_category') }}')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 text-sm font-medium hover:bg-red-500/30 transition">
                                                        {{ __('site.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($categories->hasPages())
                        <div class="p-6 border-t border-white/5">{{ $categories->links() }}</div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.no_categories_found') }}</p>
                        <a href="{{ route('admin.categories.create') }}" class="mt-4 inline-block px-5 py-2.5 rounded-xl bg-gold-500/20 text-gold-400 font-medium hover:bg-gold-500/30 transition">
                            {{ __('site.add_first_category') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
