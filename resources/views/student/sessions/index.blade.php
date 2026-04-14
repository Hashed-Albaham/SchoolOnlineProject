<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.session_booking') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.browse_manage_sessions') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Tabs Component styled as Filters -->
            <div x-data="{ tab: 'available_sessions' }" class="space-y-6">
                <!-- Navigation Tabs styled as Filter Box -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 mb-8">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button @click="tab = 'available_sessions'" 
                            :class="tab === 'available_sessions' ? 'bg-gold-gradient text-luxury-900 font-bold shadow-glow' : 'bg-white/5 text-luxury-300 hover:text-white hover:bg-white/10'" 
                            class="px-6 py-3 rounded-xl transition-all duration-300 flex-1 sm:flex-none">
                            🔍 {{ __('site.search_available_sessions') }}
                        </button>
                        
                        <button @click="tab = 'my_bookings'" 
                            :class="tab === 'my_bookings' ? 'bg-gold-gradient text-luxury-900 font-bold shadow-glow' : 'bg-white/5 text-luxury-300 hover:text-white hover:bg-white/10'" 
                            class="px-6 py-3 rounded-xl transition-all duration-300 flex-1 sm:flex-none">
                            📅 {{ __('site.my_scheduled_sessions') }}
                        </button>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div x-show="tab === 'my_bookings'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    @livewire('student-my-bookings')
                </div>

                <div x-show="tab === 'available_sessions'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                    @livewire('student-session-finder')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
