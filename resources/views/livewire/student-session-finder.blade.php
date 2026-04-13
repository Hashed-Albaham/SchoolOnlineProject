<div>
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.session_type') }}</label>
            <select wire:model.live="filter_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="all">{{ __('site.all') }}</option>
                <option value="1-on-1">{{ __('site.one_on_one_session') }}</option>
                <option value="group">{{ __('site.group_session') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.course') }}</label>
            <select wire:model.live="filter_course_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="all">{{ __('site.all_available') }}</option>
                @foreach($studentCourses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.price') }}</label>
            <select wire:model.live="filter_price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="all">{{ __('site.all') }}</option>
                <option value="free">{{ __('site.free') }}</option>
                <option value="paid">{{ __('site.paid') }}</option>
            </select>
        </div>
    </div>

    <!-- Messages -->
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

    <!-- grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sessions as $session)
            @php
                $occupied = $session->bookings->filter(fn($b) => $b->status === 'confirmed' || ($b->status === 'pending' && $b->locked_until >= now()))->count();
                $available = $session->max_participants - $occupied;
                $isFull = $available <= 0;
            @endphp
            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden flex flex-col justify-between hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-indigo-50 text-indigo-600">
                                {{ $session->type === '1-on-1' ? __('site.one_on_one_session') : __('site.group_session') }}
                            </span>
                            @if($session->price == 0)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-50 text-green-600">
                                    {{ __('site.free') }}
                                </span>
                            @endif
                        </div>
                        <p class="font-bold text-lg text-gray-900">
                            {{ $session->price > 0 ? $session->price . ' ' . \App\Models\Setting::get('currency_symbol', '$') : '' }}
                        </p>
                    </div>

                    <h3 class="font-semibold text-lg text-gray-800 mb-2">
                        {{ \Carbon\Carbon::parse($session->start_time)->tz(auth()->user()->timezone ?? 'UTC')->format('d M Y, h:i A') }}
                    </h3>
                    
                    <div class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span>{{ $session->tutor->name }}</span>
                    </div>

                    @if($session->course_id)
                        <div class="mb-4">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('site.course') }}</span>
                            <p class="text-sm text-gray-800 truncate">{{ $session->course->title }}</p>
                        </div>
                    @endif

                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4 mb-2">
                      <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($occupied / $session->max_participants) * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 text-right">{{ $available }} {{ __('site.seats_available') }}</p>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <button wire:click="bookSeat({{ $session->id }})" wire:loading.attr="disabled"
                            class="w-full py-2 px-4 rounded-md font-medium text-center transition
                            {{ $isFull ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer' }}"
                            {{ $isFull ? 'disabled' : '' }}>
                        <span wire:loading.remove wire:target="bookSeat({{ $session->id }})">
                            {{ $isFull ? __('site.session_full') : __('site.book_now') }}
                        </span>
                        <span wire:loading wire:target="bookSeat({{ $session->id }})">
                            {{ __('site.processing') }}...
                        </span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-gray-500 bg-white rounded-xl shadow-sm">
                {{ __('site.no_sessions_found') }}
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $sessions->links() }}
    </div>
</div>
