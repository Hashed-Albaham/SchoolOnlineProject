<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">{{ __('site.tutor_profile') }}</h2>
        <p class="text-luxury-400 text-sm mt-1">{{ __('site.tutor_profile_desc') }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Verification Status -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('site.verification_status') }}</h3>
                @if($tutorDetail->is_verified ?? false)
                    <div class="flex items-center gap-3 text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ __('site.account_verified') }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ __('site.awaiting_verification') }}</span>
                    </div>
                    <p class="text-sm text-luxury-400 mt-2">{{ __('site.complete_profile_hint') }}</p>
                @endif
            </div>

            <!-- Profile Form -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 sm:p-8">
                <form action="{{ route('tutor.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Name (read-only) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.full_name') }}</label>
                        <input type="text" value="{{ $user->name }}" disabled
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-luxury-400 cursor-not-allowed">
                    </div>

                    <!-- Email (read-only) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.email') }}</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-luxury-400 cursor-not-allowed">
                    </div>

                    <!-- Specialization -->
                    <div class="mb-6">
                        <label for="specialization" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.specialization') }}</label>
                        <input type="text" name="specialization" id="specialization"
                            value="{{ old('specialization', $tutorDetail->specialization ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.specialization_placeholder') }}">
                        @error('specialization')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="mb-6">
                        <label for="bio" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.bio_label') }}</label>
                        <textarea name="bio" id="bio" rows="4"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ __('site.bio_placeholder') }}">{{ old('bio', $tutorDetail->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- [REQ] Qualifications Section -->
                    <div class="border-t border-white/10 pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            🎓 {{ __('site.qualifications') }}
                        </h3>

                        <!-- University -->
                        <div class="mb-4">
                            <label for="university" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.university') }}</label>
                            <input type="text" name="university" id="university"
                                value="{{ old('university', $tutorDetail->university ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ __('site.university_placeholder') }}">
                            @error('university')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Graduation Year -->
                        <div class="mb-4">
                            <label for="graduation_year" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.graduation_year') }}</label>
                            <input type="number" name="graduation_year" id="graduation_year"
                                value="{{ old('graduation_year', $tutorDetail->graduation_year ?? '') }}"
                                min="2000" max="{{ date('Y') + 1 }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ date('Y') }}">
                            @error('graduation_year')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Degree Certificate Upload -->
                        <div class="mb-4">
                            <label for="degree_certificate" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.degree_certificate') }}</label>
                            @if($tutorDetail->degree_certificate_path ?? false)
                                <div class="mb-3 flex items-center gap-2 text-green-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ __('site.certificate_uploaded') }}</span>
                                </div>
                            @endif
                            <input type="file" name="degree_certificate" id="degree_certificate" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-luxury-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gold-500/20 file:text-gold-400 hover:file:bg-gold-500/30 transition">
                            <p class="text-sm text-luxury-500 mt-2">{{ __('site.degree_certificate_hint') }}</p>
                            @error('degree_certificate')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Skills -->
                        <div class="mb-4">
                            <label for="skills" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.skills_label') }}</label>
                            <textarea name="skills" id="skills" rows="3"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="{{ __('site.skills_placeholder') }}">{{ old('skills', $tutorDetail->skills ?? '') }}</textarea>
                            @error('skills')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Portfolio URL -->
                        <div class="mb-4">
                            <label for="portfolio_url" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.portfolio_url') }}</label>
                            <input type="url" name="portfolio_url" id="portfolio_url"
                                value="{{ old('portfolio_url', $tutorDetail->portfolio_url ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-2 focus:ring-gold-500/20 transition"
                                placeholder="https://github.com/username">
                            @error('portfolio_url')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- CV Upload -->
                    <div class="mb-8">
                        <label for="cv" class="block text-sm font-medium text-luxury-300 mb-2">{{ __('site.cv_label') }}</label>
                        @if($tutorDetail->cv_path ?? false)
                            <div class="mb-3 flex items-center gap-2 text-green-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">{{ __('site.cv_uploaded') }}</span>
                                <a href="{{ route('tutor.profile.cv') }}" class="text-gold-400 hover:text-gold-300 text-sm font-medium transition">
                                    {{ __('site.download') }}
                                </a>
                            </div>
                        @endif
                        <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-luxury-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gold-500/20 file:text-gold-400 hover:file:bg-gold-500/30 transition">
                        <p class="text-sm text-luxury-500 mt-2">{{ __('site.cv_file_hint') }}</p>
                        @error('cv')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-premium px-8 py-3 rounded-xl font-semibold">
                            {{ __('site.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>