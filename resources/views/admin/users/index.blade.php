<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('site.manage_users') }}</h2>
                <p class="text-luxury-400 text-sm mt-1">{{ __('site.manage_all_users_desc') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('site.search_users') }}..."
                        class="px-4 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-white text-sm focus:outline-none focus:border-gold-500/50 placeholder-luxury-500 w-48">
                    <select name="role" onchange="this.form.submit()"
                        class="px-3 py-2 rounded-xl bg-luxury-700/50 border border-white/10 text-white text-sm focus:outline-none">
                        <option value="">{{ __('site.all_roles') }}</option>
                        <option value="admin" {{ ($roleFilter ?? '') == 'admin' ? 'selected' : '' }}>{{ __('site.admin') }}</option>
                        <option value="tutor" {{ ($roleFilter ?? '') == 'tutor' ? 'selected' : '' }}>{{ __('site.tutor') }}</option>
                        <option value="student" {{ ($roleFilter ?? '') == 'student' ? 'selected' : '' }}>{{ __('site.student') }}</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gold-500/20 text-gold-400 text-sm font-medium hover:bg-gold-500/30 transition">
                        {{ __('site.search') }}
                    </button>
                </form>
                {{-- [v8.0] Create User Button --}}
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 rounded-xl bg-green-500/20 text-green-400 text-sm font-medium hover:bg-green-500/30 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('site.create_user') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.total_users') }}</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $roleCounts->total ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.admins') }}</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $roleCounts->admins ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.tutors') }}</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $roleCounts->tutors ?? 0 }}</p>
                </div>
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <p class="text-luxury-400 text-sm">{{ __('site.students') }}</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $roleCounts->students ?? 0 }}</p>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">{{ session('error') }}</div>
            @endif

            {{-- Users Table --}}
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">{{ __('site.all_users') }}</h3>
                </div>

                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">#</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.name') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.email') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.role') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.join_date') }}</th>
                                    <th class="px-6 py-4 text-start text-xs font-medium text-luxury-400 uppercase">{{ __('site.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($users as $user)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $user->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <x-avatar :user="$user" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                                                <div>
                                                    <span class="font-medium text-white">{{ $user->name }}</span>
                                                    @if($user->is_super_admin)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-lg bg-purple-500/20 text-purple-400 mr-2">🛡️ {{ __('site.super_admin') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-luxury-300 text-sm">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $roleColors = ['admin' => 'red', 'tutor' => 'blue', 'student' => 'green'];
                                                $c = $roleColors[$user->role] ?? 'gray';
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs rounded-lg bg-{{ $c }}-500/20 text-{{ $c }}-400">
                                                {{ __('site.' . $user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-luxury-400 text-sm">{{ $user->created_at->format('Y/m/d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- [v8.0] Hide actions for admin rows if current user is not super admin --}}
                                            @if($user->role !== 'admin' || auth()->user()->isSuperAdmin())
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1.5 rounded-lg bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                                        {{ __('site.edit') }}
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('{{ __('site.confirm_delete_user') }}')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 text-sm font-medium hover:bg-red-500/30 transition">
                                                                {{ __('site.delete') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-luxury-500 text-xs">{{ __('site.super_admin_required') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($users->hasPages())
                        <div class="p-6 border-t border-white/5">{{ $users->links() }}</div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <p class="text-luxury-400">{{ __('site.no_users_found') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
