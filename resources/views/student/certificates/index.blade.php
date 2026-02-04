<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white leading-tight">
            {{ __('site.my_certificates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($certificates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        <div
                            class="card-luxury bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 group hover:translate-y-[-5px] transition-all duration-300">
                            <!-- Icon -->
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-gold-500/20 to-gold-600/20 rounded-2xl flex items-center justify-center mb-4 border border-gold-500/30 group-hover:shadow-[0_0_20px_rgba(234,179,8,0.2)] transition-shadow">
                                <svg class="w-8 h-8 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold text-white mb-2">{{ $certificate->course->title }}</h3>
                            <p class="text-luxury-400 text-sm mb-4">{{ __('site.instructor') }}:
                                {{ $certificate->course->tutor->name }}</p>

                            <div class="flex flex-col gap-2 text-xs text-luxury-500 mb-6">
                                <div class="flex justify-between">
                                    <span>{{ __('site.issue_date') }}</span>
                                    <span class="text-white">{{ $certificate->issued_at->format('Y-m-d') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ __('site.certificate_code') }}</span>
                                    <span class="font-mono text-gold-400">{{ $certificate->certificate_code }}</span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('certificate.show', $certificate->id) }}" target="_blank"
                                    class="flex-1 btn-premium py-2 rounded-xl text-center text-sm font-semibold bg-gold-gradient text-luxury-900 hover:shadow-glow transition block">
                                    {{ __('site.view_certificate') }}
                                </a>
                                <button onclick="copyLink('{{ route('certificate.verify', $certificate->certificate_code) }}')"
                                    class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-luxury-400 hover:text-white transition border border-white/5"
                                    title="{{ __('site.copy_link') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $certificates->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-luxury-800/30 rounded-3xl border border-white/5">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-luxury-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('site.no_certificates') }}</h3>
                    <p class="text-luxury-400 mb-6">{{ __('site.complete_courses_msg') }}</p>
                    <a href="{{ route('student.courses.index') }}"
                        class="btn-premium px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                        {{ __('site.browse_courses') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                // You could add a toast notification here
                alert('{{ __('site.link_copied') }}');
            });
        }
    </script>
</x-app-layout>