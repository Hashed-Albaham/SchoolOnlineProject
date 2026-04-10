<nav x-data="{ open: false }" class="bg-luxury-900/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 gap-2">
            <!-- Left: Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div
                        class="w-10 h-10 rounded-xl bg-gold-gradient flex items-center justify-center shadow-glow group-hover:scale-110 transition-transform duration-300">
                        <span class="text-luxury-900 font-bold text-xl">P</span>
                    </div>
                    <span class="text-xl font-bold text-gradient hidden sm:block">ProSkill</span>
                </a>
            </div>

            <!-- Center: Navigation Links (scrollable) -->
            <div class="hidden sm:flex items-center min-w-0 flex-1 mx-2">
                <div class="overflow-x-auto whitespace-nowrap custom-scrollbar flex items-center gap-1 w-full">
                    @auth
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                            {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard')
        ? 'bg-white/10 text-gold-400'
        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>

                        {{ __('site.dashboard') }}
                    </a>

                    <a href="{{ route('messages.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                        {{ request()->routeIs('messages.*')
        ? 'bg-white/10 text-gold-400'
        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        {{ __('site.messages') }}
                    </a>

                    @if(auth()->user()->role === 'student')
                                <a href="{{ route('student.courses.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('student.courses.*') && !request()->routeIs('student.courses.my')
                        ? 'bg-white/10 text-gold-400'
                        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.courses') }}
                                </a>
                                <a href="{{ route('student.courses.my') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('student.courses.my')
                        ? 'bg-white/10 text-gold-400'
                        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.my_courses') }}
                                </a>
                                <a href="{{ route('student.certificates') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('student.certificates')
                        ? 'bg-white/10 text-gold-400'
                        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.my_certificates') }}
                                </a>
                    @elseif(auth()->user()->role === 'tutor')
                                <a href="{{ route('tutor.courses.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('tutor.courses.*')
                        ? 'bg-white/10 text-gold-400'
                        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.my_courses') }}
                                </a>
                                <a href="{{ route('tutor.profile.edit') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('tutor.profile.*')
                        ? 'bg-white/10 text-gold-400'
                        : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.profile') }}
                                </a>
                                <a href="{{ route('tutor.payouts.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('tutor.payouts.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    💰 {{ __('site.my_earnings') }}
                                </a>
                                <a href="{{ route('tutor.reports.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('tutor.reports.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    📊 {{ __('site.tutor_reports') }}
                                </a>
                    @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.manage_users') }}
                                </a>
                                <a href="{{ route('admin.tutors.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.tutors.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.tutors') }}
                                </a>
                                <a href="{{ route('admin.courses.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.courses.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.courses') }}
                                </a>
                                <a href="{{ route('admin.enrollments.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.enrollments.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.enrollments_management') }}
                                </a>
                                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.reports.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    {{ __('site.reports_analytics') }}
                                </a>
                                <a href="{{ route('admin.payment_methods.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.payment_methods.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    💳 {{ __('site.payment_methods') }}
                                </a>
                                <a href="{{ route('admin.payouts.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.payouts.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    💰 {{ __('site.payout_management') }}
                                </a>
                                <a href="{{ route('admin.chat.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.chat.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    👁 {{ __('site.chat_oversight') }}
                                </a>
                                {{-- [v8.0] Settings - Super Admin Only --}}
                                @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('admin.settings.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex-shrink-0
                                    {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:text-white hover:bg-white/5' }}">
                                    ⚙️ {{ __('site.settings') }}
                                </a>
                                @endif
                    @endif
                    @endif
                </div>
            </div>

            <!-- Right: Language + Notifications + User (fixed, never overflows) -->
            <div class="hidden sm:flex sm:items-center sm:gap-2 flex-shrink-0">
                @auth
                    <!-- Language Switcher -->
                    <x-language-switcher />

                    <!-- Notifications -->
                    <livewire:notifications-dropdown />

                    <!-- User Dropdown -->
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-2 px-2 py-1.5 rounded-xl text-sm font-medium text-luxury-300 hover:text-white hover:bg-white/5 transition-all duration-200 border border-white/5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                    <x-avatar :user="Auth::user()" sizeClasses="w-full h-full" iconClasses="w-5 h-5" />
                                </div>
                                <div class="text-right hidden lg:block">
                                    <p class="text-white text-sm font-medium leading-tight">{{ Auth::user()->name ?? '' }}</p>
                                    <p class="text-xs text-luxury-400 leading-tight">
                                        @if((Auth::user()->role ?? '') === 'admin') {{ __('site.admin') }}
                                        @elseif((Auth::user()->role ?? '') === 'tutor') {{ __('site.role_tutor') }}
                                        @else {{ __('site.role_student') }}
                                        @endif
                                    </p>
                                </div>
                                <svg class="w-4 h-4 text-luxury-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-luxury-800 rounded-xl border border-white/10 shadow-luxury overflow-hidden py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-luxury-300 hover:text-white hover:bg-white/5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ __('site.profile') }}
                                </a>

                                <div class="border-t border-white/5 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        {{ __('site.logout') }}
                                    </button>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <x-language-switcher />
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-luxury-300 hover:text-white transition">{{ __('site.login') }}</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 text-sm font-bold bg-gold-gradient text-luxury-900 rounded-lg hover:shadow-glow transition-all duration-300 transform hover:scale-105">
                        {{ __('site.register') }}
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger & Notifications -->
            <div class="flex items-center gap-1 sm:hidden">
                @auth
                    <livewire:notifications-dropdown />
                @endauth
                <button @click="open = !open"
                    class="p-2 rounded-lg text-luxury-400 hover:text-white hover:bg-white/5 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu (Scrollable Horizontally) -->
    <div :class="{'block': open, 'hidden': !open}"
        class="hidden sm:hidden bg-luxury-800/95 backdrop-blur-xl border-t border-white/5">

        <!-- Language Switcher (Mobile) -->
        <div class="flex justify-center py-2 border-b border-white/5">
            <x-language-switcher />
        </div>

        <!-- Horizontally Scrollable Links Container -->
        <div class="overflow-x-auto whitespace-nowrap custom-scrollbar pb-2">
            <div class="flex gap-2 px-4 pt-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                            {{ request()->routeIs('dashboard') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                        {{ __('site.dashboard') }}
                    </a>

                    @if(auth()->user()->role === 'student')
                        <a href="{{ route('student.courses.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('student.courses.index') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.courses') }}
                        </a>
                        <a href="{{ route('student.courses.my') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('student.courses.my') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.my_courses') }}
                        </a>
                        <a href="{{ route('student.certificates') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('student.certificates') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.my_certificates') }}
                        </a>
                    @elseif(auth()->user()->role === 'tutor')
                        <a href="{{ route('tutor.courses.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('tutor.courses.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.my_courses') }}
                        </a>
                        <a href="{{ route('tutor.enrollments.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('tutor.enrollments.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.enrollment_requests') }}
                        </a>
                        <a href="{{ route('tutor.profile.edit') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('tutor.profile.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.profile') }}
                        </a>
                        <a href="{{ route('tutor.payouts.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('tutor.payouts.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            💰 {{ __('site.my_earnings') }}
                        </a>
                        <a href="{{ route('tutor.reports.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('tutor.reports.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            📊 {{ __('site.tutor_reports') }}
                        </a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.manage_users') }}
                        </a>
                        <a href="{{ route('admin.tutors.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.tutors.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.tutors') }}
                        </a>
                        <a href="{{ route('admin.courses.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.courses.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.courses') }}
                        </a>
                        <a href="{{ route('admin.enrollments.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.enrollments.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.enrollments_management') }}
                        </a>
                        <a href="{{ route('admin.reports.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.reports.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            {{ __('site.reports_analytics') }}
                        </a>
                        <a href="{{ route('admin.payment_methods.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.payment_methods.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            💳 {{ __('site.payment_methods') }}
                        </a>
                        <a href="{{ route('admin.payouts.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.payouts.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            💰 {{ __('site.payout_management') }}
                        </a>
                        <a href="{{ route('admin.chat.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.chat.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            👁 {{ __('site.chat_oversight') }}
                        </a>
                        <a href="{{ route('admin.transactions.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.transactions.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            💳 {{ __('site.fin_transactions') }}
                        </a>
                        {{-- [v8.0] Settings - Super Admin Only --}}
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.settings.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0
                                {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-gold-400' : 'text-luxury-300 hover:bg-white/5' }}">
                            ⚙️ {{ __('site.settings') }}
                        </a>
                        @endif
                    @endif
                @endauth
            </div>
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-3 border-t border-white/5">
            @auth
                <div class="flex items-center px-4 gap-3">
                    <x-avatar :user="Auth::user()" sizeClasses="w-10 h-10" iconClasses="w-5 h-5" />
                    <div>
                        <p class="text-white font-medium">{{ Auth::user()->name ?? '' }}</p>
                        <p class="text-sm text-luxury-400">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>

                <div class="mt-3 space-y-1 px-4">
                    <a href="{{ route('messages.index') }}"
                        class="block px-4 py-3 rounded-lg text-base font-medium text-luxury-300 hover:bg-white/5 transition">
                        {{ __('site.messages') }}
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-3 rounded-lg text-base font-medium text-luxury-300 hover:bg-white/5 transition">
                        {{ __('site.profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-right px-4 py-3 rounded-lg text-base font-medium text-red-400 hover:bg-red-500/10 transition">
                            {{ __('site.logout') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="px-4 space-y-3">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center px-4 py-3 rounded-lg text-luxury-300 bg-white/5 hover:bg-white/10 transition font-medium">
                        {{ __('site.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center px-4 py-3 rounded-lg bg-gold-gradient text-luxury-900 font-bold hover:shadow-glow transition">
                        {{ __('site.register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>