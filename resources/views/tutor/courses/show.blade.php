<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.courses.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $course->title }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.course_details_and_enrolled') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Enrollments -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-royal-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $course->enrollments->count() }}</p>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_enrolled') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Paid -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $course->enrollments->where('payment_status', 'paid')->count() }}</p>
                            <p class="text-luxury-400 text-sm">{{ __('site.paid_enrolled') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Certificates -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $certificateRequests->where('status', 'pending')->count() }}</p>
                            <p class="text-luxury-400 text-sm">{{ __('site.pending_certificate_requests') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contents -->
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gold-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $course->contents->count() }}</p>
                            <p class="text-luxury-400 text-sm">{{ __('site.number_of_lessons') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Certificate Requests --}}
            @if($certificateRequests->where('status', 'pending')->count() > 0)
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 mb-8">
                    <h4 class="text-xl font-bold text-yellow-400 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('site.pending_cert_requests_title') }}
                    </h4>
                    <div class="space-y-3">
                        @foreach($certificateRequests->where('status', 'pending') as $request)
                            <div class="flex items-center justify-between p-4 bg-yellow-500/10 rounded-xl border border-yellow-500/10">
                                <div class="flex items-center gap-3">
                                        <x-avatar :user="$request->user" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                    <div>
                                        <p class="text-white font-medium">{{ $request->user->name }}</p>
                                        <p class="text-luxury-400 text-sm">{{ $request->user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('tutor.certificates.issue', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition font-medium">
                                            ✓ {{ __('site.issue_certificate') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('tutor.certificates.reject', $request) }}" method="POST" onsubmit="return confirm('{{ __('site.confirm_reject_request') }}')">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition font-medium">
                                            ✗ {{ __('site.reject') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Course Info -->
                <div class="lg:col-span-1">
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-2">{{ $course->title }}</h3>
                            <p class="text-luxury-400 text-sm mb-4">{{ Str::limit($course->description, 150) }}</p>
                            <div class="flex items-center justify-between border-t border-white/10 pt-4">
                                <span class="text-gold-400 font-bold">{{ number_format($course->price, 2) }} {{ \App\Models\Setting::get('currency_symbol', 'ر.س') }}</span>
                                <a href="{{ route('tutor.courses.edit', $course) }}" 
                                    class="px-4 py-2 rounded-xl bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                    {{ __('site.edit_course') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contents List -->
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mt-6">
                        <h4 class="text-white font-bold mb-4">{{ __('site.course_content_count', ['count' => $course->contents->count()]) }}</h4>
                        @if($course->contents->count() > 0)
                            <div class="space-y-2">
                                @foreach($course->contents as $content)
                                    <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl">
                                        <span class="text-luxury-500 font-mono text-xs">{{ $content->order }}</span>
                                        <div class="flex-1">
                                            <p class="text-white text-sm">{{ $content->title }}</p>
                                            <span class="text-xs text-gold-400">{{ $content->type_label }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-luxury-400 text-sm text-center py-4">{{ __('site.no_content_yet') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Enrollments List with Progress -->
                <div class="lg:col-span-2">
                    <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                        <h4 class="text-xl font-bold text-white mb-6">{{ __('site.enrolled_students_progress') }}</h4>
                        
                        @if($enrollmentsWithProgress->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-white/10">
                                            <th class="text-right text-luxury-400 text-sm font-medium pb-4">{{ __('site.student') }}</th>
                                            <th class="text-center text-luxury-400 text-sm font-medium pb-4">{{ __('site.payment_status') }}</th>
                                            <th class="text-center text-luxury-400 text-sm font-medium pb-4">{{ __('site.progress') }}</th>
                                            <th class="text-center text-luxury-400 text-sm font-medium pb-4">{{ __('site.certificate') }}</th>
                                            <th class="text-center text-luxury-400 text-sm font-medium pb-4">{{ __('site.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($enrollmentsWithProgress as $enrollment)
                                            @php
                                                $studentCert = $certificateRequests->where('user_id', $enrollment->student->id)->first();
                                            @endphp
                                            <tr class="hover:bg-white/5 transition">
                                                <td class="py-4">
                                                    <div class="flex items-center gap-3">
                                                            <x-avatar :user="$enrollment->student" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                                        <div>
                                                            <span class="text-white font-medium">{{ $enrollment->student->name ?? __('site.unknown_student') }}</span>
                                                            <p class="text-luxury-500 text-xs">{{ $enrollment->student->email ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-center">
                                                    @if($enrollment->payment_status === 'paid')
                                                        <span class="px-3 py-1 rounded-full text-xs bg-green-500/20 text-green-400">{{ __('site.paid') }}</span>
                                                    @else
                                                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">{{ __('site.pending') }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-4">
                                                    <div class="flex flex-col items-center gap-1">
                                                        <span class="text-sm {{ $enrollment->progress_percent == 100 ? 'text-green-400' : 'text-gold-400' }} font-bold">
                                                            {{ $enrollment->completed_count }}/{{ $enrollment->total_count }}
                                                        </span>
                                                        <div class="w-20 h-2 bg-luxury-700 rounded-full overflow-hidden">
                                                            <div class="h-full {{ $enrollment->progress_percent == 100 ? 'bg-green-500' : 'bg-gold-500' }} rounded-full" style="width: {{ $enrollment->progress_percent }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-luxury-500">{{ $enrollment->progress_percent }}%</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-center">
                                                    @if($studentCert)
                                                        @if($studentCert->isApproved())
                                                            <span class="px-3 py-1 rounded-full text-xs bg-green-500/20 text-green-400">✓ {{ __('site.issued') }}</span>
                                                        @elseif($studentCert->isPending())
                                                            <span class="px-3 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">⏳ {{ __('site.pending_time') }}</span>
                                                        @else
                                                            <span class="px-3 py-1 rounded-full text-xs bg-red-500/20 text-red-400">✗ {{ __('site.rejected') }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-luxury-500 text-xs">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-center">
                                                    <a href="{{ route('messages.show', $enrollment->student) }}" 
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-royal-500/20 text-royal-400 text-sm hover:bg-royal-500/30 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                        </svg>
                                                        {{ __('site.message_student') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12 text-luxury-400">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-lg">{{ __('site.no_enrolled_students_yet') }}</p>
                                <p class="text-sm mt-2">{{ __('site.students_will_appear_here') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Issued Certificates --}}
                    @if($certificateRequests->where('status', 'approved')->count() > 0)
                        <div class="bg-luxury-800/50 backdrop-blur-xl border border-green-500/20 rounded-2xl p-6 mt-6">
                            <h4 class="text-lg font-bold text-green-400 mb-4">{{ __('site.issued_certificates') }}</h4>
                            <div class="space-y-2">
                                @foreach($certificateRequests->where('status', 'approved') as $cert)
                                    <div class="flex items-center justify-between p-3 bg-green-500/10 rounded-xl">
                                        <div>
                                            <p class="text-white font-medium">{{ $cert->user->name }}</p>
                                            <p class="text-green-400 text-sm">{{ $cert->certificate_code }}</p>
                                        </div>
                                        <span class="text-luxury-500 text-sm">{{ $cert->issued_at->format('Y/m/d') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
