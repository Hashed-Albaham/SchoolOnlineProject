<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.enrollment_requests') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.manage_enrollment_requests_desc') }}</p>
            </div>
            @if($pendingCount > 0)
                <span class="px-4 py-2 rounded-xl bg-yellow-500/20 text-yellow-400 font-semibold text-sm">
                    {{ $pendingCount }} {{ __('site.pending') }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($enrollments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.student') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.course') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.payment_status') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.enrollment_status_label') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.date') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($enrollments as $enrollment)
                                    <tr class="hover:bg-white/5 transition {{ $enrollment->enrollment_status === 'pending_approval' ? 'bg-yellow-500/5' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <x-avatar :user="$enrollment->user" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                                <div>
                                                    <p class="text-white font-medium text-sm">{{ $enrollment->user->name ?? '-' }}</p>
                                                    <p class="text-luxury-500 text-xs">{{ $enrollment->user->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-white text-sm font-medium">{{ Str::limit($enrollment->course->title ?? '-', 30) }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($enrollment->payment_status === 'paid')
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-green-500/20 text-green-400">{{ __('site.paid') }}</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-500/20 text-yellow-400">{{ __('site.pending') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($enrollment->enrollment_status === 'approved')
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-green-500/20 text-green-400">{{ __('site.approved') }}</span>
                                            @elseif($enrollment->enrollment_status === 'pending_approval')
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-500/20 text-yellow-400">{{ __('site.pending_approval') }}</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/20 text-red-400">{{ __('site.rejected') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $enrollment->created_at->format('Y/m/d') }}</td>
                                        <td class="px-6 py-4">
                                            @if($enrollment->enrollment_status === 'pending_approval')
                                                <div class="flex items-center gap-2">
                                                    <form action="{{ route('tutor.enrollments.approve', $enrollment) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="p-2 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition" title="{{ __('site.approve') }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('tutor.enrollments.reject', $enrollment) }}" method="POST"
                                                        onsubmit="return confirm('{{ __('site.confirm_reject_enrollment') }}')">
                                                        @csrf
                                                        <button type="submit" class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition" title="{{ __('site.reject') }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($enrollment->enrollment_status === 'approved')
                                                <span class="text-luxury-500 text-xs">{{ __('site.already_approved') }}</span>
                                            @else
                                                <form action="{{ route('tutor.enrollments.approve', $enrollment) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-gold-400 hover:text-gold-300 transition">{{ __('site.re_approve') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-white/5">
                        {{ $enrollments->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-luxury-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-luxury-400">{{ __('site.no_enrollment_requests') }}</h3>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
