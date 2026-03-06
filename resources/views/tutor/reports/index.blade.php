<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-white">📊 {{ __('site.tutor_reports') }}</h2>
            <p class="text-luxury-400 text-sm mt-1">{{ __('site.tutor_reports_desc') }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Courses --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_courses_label') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $courseStats->total ?? 0 }}</p>
                            <p class="text-xs text-green-400 mt-1">{{ $courseStats->approved ?? 0 }} {{ __('site.status_approved') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-royal-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Students --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_students_enrolled') }}</p>
                            <p class="text-3xl font-bold text-blue-400 mt-1">{{ $totalStudents }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Revenue --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_earnings') }}</p>
                            <p class="text-3xl font-bold text-gold-400 mt-1">${{ number_format($totalEarnings, 2) }}</p>
                            <div class="flex gap-3 mt-1">
                                <span class="text-xs text-green-400">${{ number_format($totalPaidOut, 2) }} {{ __('site.paid') }}</span>
                                <span class="text-xs text-luxury-400">${{ number_format($availableBalance, 2) }} {{ __('site.available') }}</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gold-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Average Rating --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.avg_rating') }}</p>
                            <p class="text-3xl font-bold text-yellow-400 mt-1">
                                @if($avgRating > 0)
                                    {{ number_format($avgRating, 1) }} ⭐
                                @else
                                    —
                                @endif
                            </p>
                            <p class="text-xs text-luxury-400 mt-1">{{ $totalReviews }} {{ __('site.reviews') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Monthly Enrollments Chart --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-6">{{ __('site.monthly_enrollments') }}</h3>
                <div class="h-64 flex items-end gap-3">
                    @php
                        $maxCount = $monthlyEnrollments->max('count') ?: 1;
                    @endphp
                    @forelse($monthlyEnrollments as $month)
                        <div class="flex-1 flex flex-col items-center gap-2" title="{{ $month->month }}: {{ $month->count }} {{ __('site.enrollments') }} — ${{ number_format($month->revenue, 2) }}">
                            <span class="text-xs text-blue-400 font-medium">{{ $month->count }}</span>
                            <div class="w-full rounded-t-lg bg-gradient-to-t from-royal-600 to-blue-400 transition-all duration-500 hover:from-royal-500 hover:to-blue-300"
                                style="height: {{ max(($month->count / $maxCount) * 200, 8) }}px"></div>
                            <span class="text-xs text-luxury-400">{{ \Carbon\Carbon::parse($month->month . '-01')->format('M') }}</span>
                        </div>
                    @empty
                        <div class="flex-1 flex items-center justify-center">
                            <p class="text-luxury-400">{{ __('site.no_data_available') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                {{-- Courses Status Distribution --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <h3 class="font-semibold text-white mb-6">{{ __('site.courses_status') }}</h3>
                    <div class="space-y-4">
                        @php
                            $total = $courseStats->total ?: 1;
                            $statuses = [
                                ['label' => __('site.status_approved'), 'count' => $courseStats->approved ?? 0, 'color' => 'green', 'pct' => round((($courseStats->approved ?? 0) / $total) * 100)],
                                ['label' => __('site.status_pending'), 'count' => $courseStats->pending ?? 0, 'color' => 'yellow', 'pct' => round((($courseStats->pending ?? 0) / $total) * 100)],
                                ['label' => __('site.status_rejected'), 'count' => $courseStats->rejected ?? 0, 'color' => 'red', 'pct' => round((($courseStats->rejected ?? 0) / $total) * 100)],
                            ];
                        @endphp
                        @foreach($statuses as $status)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-luxury-300">{{ $status['label'] }}</span>
                                    <span class="text-sm text-white font-medium">{{ $status['count'] }} ({{ $status['pct'] }}%)</span>
                                </div>
                                <div class="w-full h-2.5 rounded-full bg-white/5">
                                    <div class="h-full rounded-full bg-{{ $status['color'] }}-500 transition-all duration-700" style="width: {{ $status['pct'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Revenue Summary --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <h3 class="font-semibold text-white mb-6">{{ __('site.revenue_summary') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center">💰</div>
                                <span class="text-luxury-300">{{ __('site.total_earnings') }}</span>
                            </div>
                            <span class="text-gold-400 font-bold text-lg">${{ number_format($totalEarnings, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">✅</div>
                                <span class="text-luxury-300">{{ __('site.total_paid_out') }}</span>
                            </div>
                            <span class="text-green-400 font-bold text-lg">${{ number_format($totalPaidOut, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl bg-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center">⏳</div>
                                <span class="text-luxury-300">{{ __('site.pending_payouts') }}</span>
                            </div>
                            <span class="text-yellow-400 font-bold text-lg">${{ number_format($pendingPayout, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-gold-500/10 to-royal-500/10 border border-gold-500/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">💎</div>
                                <span class="text-white font-medium">{{ __('site.available_balance') }}</span>
                            </div>
                            <span class="text-white font-bold text-xl">${{ number_format($availableBalance, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Courses by Students --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.top_courses_students') }}</h3>
                </div>
                @if($topCourses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">#</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.course_name') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.students') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.revenue') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.avg_rating') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($topCourses as $index => $course)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('tutor.courses.show', $course) }}" class="font-medium text-white hover:text-gold-400 transition">
                                                {{ Str::limit($course->title, 40) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-blue-500/20 text-blue-400">{{ $course->enrollments_count }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gold-400 font-semibold">${{ number_format($course->total_revenue, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($course->reviews_avg_rating)
                                                <span class="text-yellow-400">{{ number_format($course->reviews_avg_rating, 1) }} ⭐</span>
                                            @else
                                                <span class="text-luxury-500">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-xs rounded-lg
                                                {{ $course->status === 'approved' ? 'bg-green-500/20 text-green-400' :
                                                   ($course->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                                {{ __('site.status_' . $course->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-luxury-700/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400">{{ __('site.no_courses_yet') }}</p>
                        <a href="{{ route('tutor.courses.create') }}" class="inline-block mt-4 px-6 py-2 rounded-xl bg-gold-500/20 text-gold-400 text-sm font-medium hover:bg-gold-500/30 transition">
                            {{ __('site.create_first_course') }}
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
