<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.enrollments_management') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.manage_enrollments_desc') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-8">
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.total_enrollments') }}</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $counts->total ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.paid') }}</p>
                    <p class="text-3xl font-bold text-green-400 mt-1">{{ $counts->paid ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.approved') }}</p>
                    <p class="text-3xl font-bold text-green-400 mt-1">{{ $counts->approved_enrollments ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.pending_approval') }}</p>
                    <p class="text-3xl font-bold text-yellow-400 mt-1">{{ $counts->pending_enrollments ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.total_revenue') }}</p>
                    <p class="text-3xl font-bold text-gold-400 mt-1">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="mb-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.enrollments.index') }}" class="px-4 py-2 rounded-xl text-sm {{ !$status && (!isset($enrollmentStatus) || !$enrollmentStatus) ? 'bg-gold-500/20 text-gold-400 border border-gold-500/30' : 'bg-white/5 text-luxury-400 border border-white/10' }} transition">
                    {{ __('site.all') }} ({{ $counts->total ?? 0 }})
                </a>
                <a href="{{ route('admin.enrollments.index', ['status' => 'paid']) }}" class="px-4 py-2 rounded-xl text-sm {{ $status == 'paid' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-white/5 text-luxury-400 border border-white/10' }} transition">
                    {{ __('site.paid') }} ({{ $counts->paid ?? 0 }})
                </a>
                <a href="{{ route('admin.enrollments.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-sm {{ $status == 'pending' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-white/5 text-luxury-400 border border-white/10' }} transition">
                    {{ __('site.pending') }} ({{ $counts->pending ?? 0 }})
                </a>
                <div class="h-6 w-px bg-white/10 mx-2 hidden sm:block"></div>
                <a href="{{ route('admin.enrollments.index', ['enrollment_status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-sm {{ (isset($enrollmentStatus) && $enrollmentStatus == 'approved') ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-white/5 text-luxury-400 border border-white/10' }} transition">
                    {{ __('site.approved') }} ({{ $counts->approved_enrollments ?? 0 }})
                </a>
                <a href="{{ route('admin.enrollments.index', ['enrollment_status' => 'pending_approval']) }}" class="px-4 py-2 rounded-xl text-sm {{ (isset($enrollmentStatus) && $enrollmentStatus == 'pending_approval') ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-white/5 text-luxury-400 border border-white/10' }} transition">
                    {{ __('site.pending_approval') }} ({{ $counts->pending_enrollments ?? 0 }})
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif

            {{-- Table --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($enrollments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.student') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.course') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.price') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.payment_status') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.enrollment_status_label') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.date') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($enrollments as $enrollment)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-white text-sm">{{ $enrollment->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">{{ Str::limit($enrollment->course->title ?? '-', 30) }}</td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">${{ $enrollment->course->price ?? 0 }}</td>
                                        <td class="px-6 py-4">
                                            @if($enrollment->payment_status === 'paid')
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">{{ __('site.paid') }}</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">{{ __('site.pending') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($enrollment->enrollment_status === 'approved')
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">{{ __('site.approved') }}</span>
                                            @elseif($enrollment->enrollment_status === 'pending_approval')
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">{{ __('site.pending_approval') }}</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs rounded-lg bg-red-500/20 text-red-400">{{ __('site.rejected') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $enrollment->created_at->format('Y/m/d') }}</td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" class="flex items-center gap-2">
                                                @csrf @method('PATCH')
                                                
                                                <select name="payment_status" class="bg-luxury-900 border border-white/10 text-white text-xs rounded-lg focus:ring-gold-500 focus:border-gold-500 block p-1.5">
                                                    <option value="pending" {{ $enrollment->payment_status === 'pending' ? 'selected' : '' }}>{{ __('site.pending') }} ({{ __('site.payment') }})</option>
                                                    <option value="paid" {{ $enrollment->payment_status === 'paid' ? 'selected' : '' }}>{{ __('site.paid') }}</option>
                                                </select>

                                                <select name="enrollment_status" class="bg-luxury-900 border border-white/10 text-white text-xs rounded-lg focus:ring-gold-500 focus:border-gold-500 block p-1.5">
                                                    <option value="pending_approval" {{ $enrollment->enrollment_status === 'pending_approval' ? 'selected' : '' }}>{{ __('site.pending_approval') }}</option>
                                                    <option value="approved" {{ $enrollment->enrollment_status === 'approved' ? 'selected' : '' }}>{{ __('site.approved') }}</option>
                                                    <option value="rejected" {{ $enrollment->enrollment_status === 'rejected' ? 'selected' : '' }}>{{ __('site.rejected') }}</option>
                                                </select>

                                                <button type="submit" class="p-1.5 rounded-lg bg-gold-500/20 text-gold-400 hover:bg-gold-500/30 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($enrollments->hasPages())
                        <div class="p-6 border-t border-white/5">{{ $enrollments->links() }}</div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.no_enrollments_found') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
