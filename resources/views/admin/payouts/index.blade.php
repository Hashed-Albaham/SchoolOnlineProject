<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.payout_management') }}</h2>
        <p class="text-luxury-400 text-sm mt-1">{{ __('site.payout_management_desc') }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
                <div class="bg-luxury-800/50 border border-white/5 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.total_requests') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-yellow-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.pending') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-blue-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['approved'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.approved') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-green-500/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-green-400">{{ $stats['paid'] }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.paid') }}</p>
                </div>
                <div class="bg-luxury-800/50 border border-gold-500/20 rounded-2xl p-5 text-center">
                    <p class="text-2xl font-bold text-gold-400">{{ number_format($stats['total_paid_amount'], 2) }} {{ \App\Models\Setting::get('currency_symbol', 'ر.س') }}</p>
                    <p class="text-luxury-400 text-xs mt-1">{{ __('site.total_paid_out') }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-6">
                <form action="{{ route('admin.payouts.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-luxury-400 text-xs mb-1">الحالة</label>
                        <select name="status" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                            <option value="">{{ __('site.all') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('site.pending') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('site.approved') }}</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('site.paid') }}</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('site.rejected') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-luxury-400 text-xs mb-1">المعلم</label>
                        <select name="tutor_id" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500 min-w-[150px]">
                            <option value="">كل المعلمين</option>
                            @foreach($tutors ?? [] as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>{{ $tutor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-luxury-400 text-xs mb-1">من تاريخ</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-luxury-400 text-xs mb-1">إلى تاريخ</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-luxury-900 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-gold-500 focus:ring-1 focus:ring-gold-500">
                    </div>
                    <div>
                        <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-luxury-900 font-bold py-2.5 px-6 rounded-xl transition-all shadow-md">
                            تصفية
                        </button>
                    </div>
                    @if(request()->anyFilled(['status', 'tutor_id', 'date_from', 'date_to']))
                        <div>
                            <a href="{{ route('admin.payouts.index') }}" class="bg-white/5 hover:bg-white/10 text-white font-bold py-2.5 px-4 rounded-xl transition-colors inline-block border border-white/10">
                                إلغاء الفلاتر
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Hidden form for bulk actions -->
            <form id="bulk-action-form" action="{{ route('admin.payouts.bulk') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="action" id="bulk-action-type">
            </form>

            @if(request('status') === 'pending' || !request()->has('status'))
            <!-- Bulk Action Buttons -->
            <div class="mb-4 flex gap-2" id="bulk-buttons-container" style="display: none;">
                <button type="button" onclick="submitBulkAction('approve')" class="px-4 py-2 rounded-lg bg-green-500/20 text-green-400 text-sm font-bold hover:bg-green-500/30 transition shadow-md border border-green-500/30">
                    ✓ موافقة على المحدد
                </button>
                <button type="button" onclick="submitBulkAction('reject')" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 text-sm font-bold hover:bg-red-500/30 transition shadow-md border border-red-500/30">
                    ✗ رفض المحدد
                </button>
            </div>
            @endif

            @if(request('status') === 'approved')
            <div class="mb-4 flex gap-2" id="bulk-buttons-container" style="display: none;">
                <button type="button" onclick="submitBulkAction('mark_paid')" class="px-4 py-2 rounded-lg bg-gold-500/20 text-gold-400 text-sm font-bold hover:bg-gold-500/30 transition shadow-md border border-gold-500/30">
                    💰 تحديد كمدفوع (للمحدد)
                </button>
            </div>
            @endif

            <!-- Requests Table -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                @if($payoutRequests->isEmpty())
                    <div class="p-12 text-center text-luxury-400">{{ __('site.no_payout_requests') }}</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="p-4 w-10">
                                        <input type="checkbox" id="select-all-checkbox" class="rounded border-white/20 bg-white/5 text-gold-500 focus:ring-gold-500/20">
                                    </th>
                                    <th class="text-right text-luxury-400 text-sm font-medium p-4">{{ __('site.tutor') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.amount') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.payment_type') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.status') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.fin_transaction_details') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.date') }}</th>
                                    <th class="text-center text-luxury-400 text-sm font-medium p-4">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($payoutRequests as $payout)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="p-4">
                                            @if($payout->isPending() || $payout->isApproved())
                                                <input type="checkbox" value="{{ $payout->id }}" class="payout-checkbox rounded border-white/20 bg-white/5 text-gold-500 focus:ring-gold-500/20">
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($payout->tutor->name ?? 'T', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-white text-sm font-medium">{{ $payout->tutor->name ?? '-' }}</p>
                                                    <p class="text-luxury-500 text-xs">{{ $payout->tutor->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="text-gold-400 font-bold">{{ number_format($payout->amount, 2) }} {{ \App\Models\Setting::get('currency_symbol', 'ر.س') }}</span>
                                        </td>
                                        <td class="p-4 text-center text-luxury-300 text-sm">
                                            @if($payout->paymentMethod)
                                                {{ $payout->paymentMethod->icon }} {{ $payout->paymentMethod->localized_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="text-xs px-2.5 py-1 rounded-full
                                                {{ $payout->isPaid() ? 'bg-green-500/20 text-green-400' : ($payout->isPending() ? 'bg-yellow-500/20 text-yellow-400' : ($payout->isApproved() ? 'bg-blue-500/20 text-blue-400' : 'bg-red-500/20 text-red-400')) }}">
                                                @switch($payout->status)
                                                    @case('pending') {{ __('site.pending') }} @break
                                                    @case('approved') {{ __('site.approved') }} @break
                                                    @case('rejected') {{ __('site.rejected') }} @break
                                                    @case('paid') {{ __('site.paid') }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="p-4 text-center text-luxury-400 text-sm">
                                            @php $tx = $payout->transactions()->where('type', 'payout')->first(); @endphp
                                            @if($tx)
                                                <a href="{{ route('admin.transactions.show', $tx) }}" class="text-blue-400 hover:text-blue-300">
                                                    {{ $tx->reference_number }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-4 text-center text-luxury-400 text-sm">
                                            {{ $payout->created_at->format('Y/m/d') }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                @if($payout->isPending())
                                                    <form action="{{ route('admin.payouts.approve', $payout) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-500/20 text-green-400 text-xs font-medium hover:bg-green-500/30 transition">
                                                            ✓ {{ __('site.approve') }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.payouts.reject', $payout) }}" method="POST"
                                                        onsubmit="event.preventDefault(); let n=prompt('{{ __('site.rejection_reason') }}'); if(n!==null){this.querySelector('[name=admin_notes]').value=n; this.submit();}">
                                                        @csrf
                                                        <input type="hidden" name="admin_notes" value="">
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 text-xs font-medium hover:bg-red-500/30 transition">
                                                            ✗ {{ __('site.reject') }}
                                                        </button>
                                                    </form>
                                                @elseif($payout->isApproved())
                                                    <form action="{{ route('admin.payouts.markPaid', $payout) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-gold-500/20 text-gold-400 text-xs font-medium hover:bg-gold-500/30 transition"
                                                            onclick="return confirm('{{ __('site.confirm_mark_paid') }}')">
                                                            💰 {{ __('site.mark_as_paid') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-luxury-500 text-xs">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-white/5">
                        {{ $payoutRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCb = document.getElementById('select-all-checkbox');
        const payoutCbs = document.querySelectorAll('.payout-checkbox');
        const bulkContainer = document.getElementById('bulk-buttons-container');

        function updateBulkButtonsVisibility() {
            const anyChecked = Array.from(payoutCbs).some(cb => cb.checked);
            if (bulkContainer) {
                bulkContainer.style.display = anyChecked ? 'flex' : 'none';
            }
        }

        if (selectAllCb) {
            selectAllCb.addEventListener('change', function(e) {
                payoutCbs.forEach(cb => cb.checked = e.target.checked);
                updateBulkButtonsVisibility();
            });
        }

        payoutCbs.forEach(cb => {
            cb.addEventListener('change', updateBulkButtonsVisibility);
        });
    });

    function submitBulkAction(action) {
        const selected = document.querySelectorAll('.payout-checkbox:checked');
        if (selected.length === 0) {
            alert('يرجى تحديد طلب واحد على الأقل');
            return;
        }
        
        let notes = '';
        if (action === 'reject') {
            notes = prompt('أدخل سبب الرفض الموحد (اختياري):') || '';
        } else {
            if (!confirm('هل أنت متأكد من تنفيذ هذا الإجراء على ' + selected.length + ' طلبات؟')) return;
        }

        const form = document.getElementById('bulk-action-form');
        document.getElementById('bulk-action-type').value = action;
        
        // Remove old hidden inputs if any exist
        form.querySelectorAll('input[name="payout_ids[]"]').forEach(el => el.remove());
        const oldNotes = form.querySelector('input[name="admin_notes"]');
        if(oldNotes) oldNotes.remove();
        
        // Append selected IDs
        selected.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payout_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        // Append admin notes for rejection
        if (action === 'reject') {
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'admin_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
        }
        
        form.submit();
    }
</script>
