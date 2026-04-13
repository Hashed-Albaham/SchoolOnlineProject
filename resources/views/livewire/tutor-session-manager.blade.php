<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">{{ __('site.my_sessions') }}</h2>
        <button wire:click="toggleForm" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
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
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $editId ? __('site.edit_session') : __('site.create_new_session') }}</h3>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- النوع -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.session_type') }}</label>
                        <select wire:model.live="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1-on-1">{{ __('site.one_on_one_session') }}</option>
                            <option value="group">{{ __('site.group_session') }}</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- الارتباط بكورس معين -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.link_to_course') }} ({{ __('site.optional') }})</label>
                        <select wire:model="course_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('site.public_session') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- السعر -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.price') }} (0 = {{ __('site.free') }})</label>
                        <input type="number" step="0.01" wire:model="price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- السعة -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.max_participants') }}</label>
                        <input type="number" wire:model="max_participants" {{ $type === '1-on-1' ? 'disabled' : '' }} class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $type === '1-on-1' ? 'bg-gray-100' : '' }}">
                        @error('max_participants') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- التوقيت -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.start_time') }}</label>
                        <input type="datetime-local" wire:model="start_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.end_time') }}</label>
                        <input type="datetime-local" wire:model="end_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- رابط الاجتماع -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('site.meeting_link') }}</label>
                        <input type="url" wire:model="meeting_link" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://zoom.us/...">
                        @error('meeting_link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('site.save') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('site.date') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('site.session_type') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('site.course') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('site.booked') }} / {{ __('site.capacity') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('site.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('site.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sessions as $session)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($session->start_time)->tz(auth()->user()->timezone ?? 'UTC')->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $session->type === '1-on-1' ? __('site.one_on_one_session') : __('site.group_session') }}
                                <br>
                                <span class="text-xs text-green-600 font-semibold">{{ $session->price > 0 ? $session->price . ' ' . \App\Models\Setting::get('currency_symbol', '$') : __('site.free') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $session->course_id ? $session->course->title : __('site.public_session') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $session->bookings->count() }} / {{ $session->max_participants }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($session->status === 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($session->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ __('site.'.$session->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="delete({{ $session->id }})" wire:confirm="{{ __('site.are_you_sure') }}" class="text-red-600 hover:text-red-900 ml-3">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                @if($session->meeting_link)
                                    <a href="{{ $session->meeting_link }}" target="_blank" class="text-blue-600 hover:text-blue-900 inline-block">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
