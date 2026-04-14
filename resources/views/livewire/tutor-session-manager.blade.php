<div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-3xl font-extrabold text-luxury-900">{{ __('site.my_sessions') ?? 'إدارة الجلسات' }}</h2>
        <button wire:click="toggleForm" class="bg-gold-500 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-gold-600 transition">
            {{ $showForm ? __('site.cancel') : __('site.add_new_session') }}
        </button>
    </div>

    <!-- رسائل النجاح أو الفشل -->
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($showForm)
        <div class="bg-luxury-800/50 backdrop-blur-xl rounded-2xl shadow-md p-6 mb-8 border border-white/5">
            <h3 class="text-lg font-medium text-white mb-4">{{ $editId ? __('site.edit_session') : __('site.create_new_session') }}</h3>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- النوع -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.session_type') }}</label>
                        <select wire:model.live="type" class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500">
                            <option value="1-on-1">{{ __('site.one_on_one_session') }}</option>
                            <option value="group">{{ __('site.group_session') }}</option>
                        </select>
                        @error('type') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- الارتباط بكورس معين -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.link_to_course') }} ({{ __('site.optional') }})</label>
                        <select wire:model="course_id" class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500">
                            <option value="">{{ __('site.public_session') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- السعر -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.price') }} (0 = {{ __('site.free') }})</label>
                        <input type="number" step="0.01" wire:model="price" class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500">
                        @error('price') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- السعة -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.max_participants') }}</label>
                        <input type="number" wire:model="max_participants" {{ $type === '1-on-1' ? 'disabled' : '' }} class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500 {{ $type === '1-on-1' ? 'opacity-50 cursor-not-allowed' : '' }}">
                        @error('max_participants') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- التوقيت -->
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.start_time') }}</label>
                        <input type="datetime-local" wire:model="start_time" class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500">
                        @error('start_time') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.end_time') }}</label>
                        <input type="datetime-local" wire:model="end_time" class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500">
                        @error('end_time') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- رابط الاجتماع -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.meeting_link') }}</label>
                        <input type="url" wire:model="meeting_link" class="w-full bg-luxury-900 border-white/10 text-white rounded-xl shadow-sm focus:border-gold-500 focus:ring-gold-500" placeholder="https://zoom.us/...">
                        @error('meeting_link') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-gold-500 text-luxury-900 font-bold px-8 py-2.5 rounded-xl hover:bg-gold-600 focus:outline-none shadow-md transition-all border border-gold-500/20">
                        {{ __('site.save') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($pendingBookings->isNotEmpty())
        <div class="bg-luxury-800/50 backdrop-blur-xl rounded-2xl shadow-md overflow-hidden border border-white/5 mb-8">
            <div class="px-6 py-4 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">حجوزات بانتظار الموافقة</h3>
                <span class="bg-yellow-500/20 text-yellow-500 text-xs font-bold px-2 py-1 rounded-full">{{ $pendingBookings->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5 text-left border-collapse">
                    <thead class="bg-luxury-900/50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-luxury-400 uppercase">الطالب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-luxury-400 uppercase">الجلسة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-luxury-400 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-luxury-400 uppercase">الرسوم</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-luxury-400 uppercase">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($pendingBookings as $booking)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 text-sm text-white">
                                    {{ $booking->student->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-luxury-300">
                                    {{ $booking->sessionSlot->type === '1-on-1' ? __('site.one_on_one_session') : __('site.group_session') }}
                                    @if($booking->sessionSlot->course)
                                        <div class="text-xs text-luxury-500">{{ $booking->sessionSlot->course->title }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-white">
                                    {{ \Carbon\Carbon::parse($booking->sessionSlot->start_time)->tz(auth()->user()->timezone ?? 'UTC')->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($booking->sessionSlot->price > 0)
                                        <span class="text-green-400 font-semibold border border-green-400/20 bg-green-400/10 px-2 py-1 rounded-md text-xs">
                                            مدفوع: {{ $booking->sessionSlot->price }}
                                        </span>
                                    @else
                                        <span class="text-blue-400 font-semibold border border-blue-400/20 bg-blue-400/10 px-2 py-1 rounded-md text-xs">
                                            مجانية
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex justify-center gap-2">
                                    <button wire:click="approveBooking({{ $booking->id }})" wire:confirm="تأكيد الموافقة؟" class="bg-green-500/20 text-green-400 hover:bg-green-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        قبول
                                    </button>
                                    <button wire:click="rejectBooking({{ $booking->id }})" wire:confirm="تأكيد الرفض والإلغاء؟" class="bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        رفض
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="bg-luxury-800/50 backdrop-blur-xl rounded-2xl shadow-md overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5 text-left border-collapse">
                <thead class="bg-luxury-900/50 border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">{{ __('site.date') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">{{ __('site.session_type') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">{{ __('site.course') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">{{ __('site.booked') }} / {{ __('site.capacity') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">{{ __('site.status') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">{{ __('site.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ \Carbon\Carbon::parse($session->start_time)->tz(auth()->user()->timezone ?? 'UTC')->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-luxury-300">
                                {{ $session->type === '1-on-1' ? __('site.one_on_one_session') : __('site.group_session') }}
                                <br>
                                <span class="text-xs text-green-400 font-semibold">{{ $session->price > 0 ? $session->price . ' ' . \App\Models\Setting::get('currency_symbol', '$') : __('site.free') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-luxury-300">
                                {{ $session->course_id ? $session->course->title : __('site.public_session') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-luxury-300">
                                {{ $session->bookings->count() }} / {{ $session->max_participants }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border {{ $session->status === 'scheduled' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : ($session->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20') }}">
                                    {{ __('site.'.$session->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="delete({{ $session->id }})" wire:confirm="{{ __('site.are_you_sure') }}" class="text-red-400 hover:text-red-300 ml-3 transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                @if($session->meeting_link)
                                    <a href="{{ $session->meeting_link }}" target="_blank" class="text-gold-400 hover:text-gold-300 inline-block transition">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-luxury-500">
                                {{ __('site.no_sessions_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
