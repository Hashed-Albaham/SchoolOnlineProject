{{-- Language Switcher Component --}}
<div class="relative inline-block text-left" x-data="{ open: false }">
    <button @click="open = !open"
        class="flex items-center gap-2 px-3 py-2 text-sm text-luxury-300 hover:text-gold-400 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
        </svg>
        <span>{{ LaravelLocalization::getCurrentLocaleNative() }}</span>
        <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-40 rounded-xl bg-luxury-800 border border-white/10 shadow-xl overflow-hidden z-50">

        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <a rel="alternate" hreflang="{{ $localeCode }}"
                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="flex items-center gap-3 px-4 py-3 text-sm 
                          {{ app()->getLocale() === $localeCode ? 'bg-gold-500/20 text-gold-400' : 'text-luxury-300 hover:bg-luxury-700 hover:text-white' }}
                          transition">
                @if($localeCode === 'ar')
                    <span class="text-lg">🇸🇦</span>
                @else
                    <span class="text-lg">🇬🇧</span>
                @endif
                <span>{{ $properties['native'] }}</span>
                @if(app()->getLocale() === $localeCode)
                    <svg class="w-4 h-4 ms-auto text-gold-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
</div>