<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.reports_analytics') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.reports_analytics_desc') }}</p>
            </div>
            {{-- Date Range Filter --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                        class="px-3 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-white text-sm focus:outline-none focus:border-gold-500/50">
                    <span class="text-luxury-400 text-sm">→</span>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        class="px-3 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-white text-sm focus:outline-none focus:border-gold-500/50">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-gold-500/20 text-gold-400 text-sm font-medium hover:bg-gold-500/30 transition">
                    {{ __('site.filter') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_revenue') }}</p>
                            <p class="text-3xl font-bold text-gold-400 mt-1">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gold-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_enrollments_label') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $totalEnrollments }}</p>
                            <p class="text-xs text-green-400 mt-1">{{ $paidEnrollments }} {{ __('site.paid') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_users') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $usersStats['total'] }}</p>
                            <p class="text-xs text-luxury-400 mt-1">+{{ $usersStats['new_this_month'] }} {{ __('site.this_month') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">{{ __('site.total_courses_label') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $coursesStats['total'] }}</p>
                            <p class="text-xs text-green-400 mt-1">{{ $coursesStats['approved'] }} {{ __('site.status_approved') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-royal-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-royal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue Chart --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-6">{{ __('site.monthly_revenue') }}</h3>
                <div class="h-64 flex items-end gap-2">
                    @php
                        $maxRevenue = $monthlyRevenue->max('revenue') ?: 1;
                    @endphp
                    @forelse($monthlyRevenue as $month)
                        <div class="flex-1 flex flex-col items-center gap-2" title="{{ $month->month }}: ${{ number_format($month->revenue, 2) }}">
                            <span class="text-xs text-gold-400 font-medium">${{ number_format($month->revenue, 0) }}</span>
                            <div class="w-full rounded-t-lg bg-gradient-to-t from-gold-600 to-gold-400 transition-all duration-500 hover:from-gold-500 hover:to-gold-300"
                                style="height: {{ max(($month->revenue / $maxRevenue) * 200, 8) }}px"></div>
                            <span class="text-xs text-luxury-400">{{ \Carbon\Carbon::parse($month->month . '-01')->format('M') }}</span>
                        </div>
                    @empty
                        <div class="flex-1 flex items-center justify-center">
                            <p class="text-luxury-400">{{ __('site.no_revenue_data') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                {{-- Users Distribution --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <h3 class="font-semibold text-white mb-6">{{ __('site.users_distribution') }}</h3>
                    <div class="space-y-4">
                        @php
                            $roles = [
                                ['label' => __('site.role_student'), 'count' => $usersStats['students'], 'color' => 'blue', 'pct' => $usersStats['total'] > 0 ? round(($usersStats['students'] / $usersStats['total']) * 100) : 0],
                                ['label' => __('site.role_tutor'), 'count' => $usersStats['tutors'], 'color' => 'royal', 'pct' => $usersStats['total'] > 0 ? round(($usersStats['tutors'] / $usersStats['total']) * 100) : 0],
                                ['label' => __('site.admin'), 'count' => $usersStats['admins'], 'color' => 'gold', 'pct' => $usersStats['total'] > 0 ? round(($usersStats['admins'] / $usersStats['total']) * 100) : 0],
                            ];
                        @endphp
                        @foreach($roles as $role)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-luxury-300">{{ $role['label'] }}</span>
                                    <span class="text-sm text-white font-medium">{{ $role['count'] }} ({{ $role['pct'] }}%)</span>
                                </div>
                                <div class="w-full h-2.5 rounded-full bg-white/5">
                                    <div class="h-full rounded-full bg-{{ $role['color'] }}-500 transition-all duration-700" style="width: {{ $role['pct'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Courses Status --}}
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <h3 class="font-semibold text-white mb-6">{{ __('site.courses_status_dist') }}</h3>
                    <div class="space-y-4">
                        @php
                            $statuses = [
                                ['label' => __('site.status_approved'), 'count' => $coursesStats['approved'], 'color' => 'green', 'pct' => $coursesStats['total'] > 0 ? round(($coursesStats['approved'] / $coursesStats['total']) * 100) : 0],
                                ['label' => __('site.status_pending'), 'count' => $coursesStats['pending'], 'color' => 'yellow', 'pct' => $coursesStats['total'] > 0 ? round(($coursesStats['pending'] / $coursesStats['total']) * 100) : 0],
                                ['label' => __('site.status_rejected'), 'count' => $coursesStats['rejected'], 'color' => 'red', 'pct' => $coursesStats['total'] > 0 ? round(($coursesStats['rejected'] / $coursesStats['total']) * 100) : 0],
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
            </div>

            {{-- Top Courses by Revenue --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.top_courses_revenue') }}</h3>
                </div>
                @if($topCourses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">#</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.course_name') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.tutor_label') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.enrollments') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.revenue') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($topCourses as $index => $course)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="font-medium text-white hover:text-gold-400 transition">
                                                {{ Str::limit($course->title, 40) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">{{ $course->tutor->name ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-blue-500/20 text-blue-400">{{ $course->enrollments_count }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gold-400 font-semibold">${{ number_format($course->total_revenue, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.no_revenue_data') }}</p>
                    </div>
                @endif
            </div>

            {{-- Category Distribution --}}
            @if($categoryStats->count() > 0)
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-6">{{ __('site.courses_by_category') }}</h3>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categoryStats as $cat)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                            @if($cat->icon)
                                <span class="text-2xl">{{ $cat->icon }}</span>
                            @else
                                <div class="w-10 h-10 rounded-lg bg-royal-500/20 flex items-center justify-center">
                                    <span class="text-royal-400 font-bold">{{ mb_substr($cat->localized_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-white">{{ $cat->localized_name }}</p>
                                <p class="text-sm text-luxury-400">{{ $cat->courses_count }} {{ __('site.course_unit') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Monthly New Users --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-6">{{ __('site.monthly_new_users') }}</h3>
                <div class="h-48 flex items-end gap-2">
                    @php
                        $maxUsers = $monthlyUsers->max('count') ?: 1;
                    @endphp
                    @forelse($monthlyUsers as $month)
                        <div class="flex-1 flex flex-col items-center gap-2" title="{{ $month->month }}: {{ $month->count }}">
                            <span class="text-xs text-blue-400 font-medium">{{ $month->count }}</span>
                            <div class="w-full rounded-t-lg bg-gradient-to-t from-blue-600 to-blue-400 transition-all duration-500 hover:from-blue-500 hover:to-blue-300"
                                style="height: {{ max(($month->count / $maxUsers) * 160, 8) }}px"></div>
                            <span class="text-xs text-luxury-400">{{ \Carbon\Carbon::parse($month->month . '-01')->format('M') }}</span>
                        </div>
                    @empty
                        <div class="flex-1 flex items-center justify-center">
                            <p class="text-luxury-400">{{ __('site.no_data_available') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
