<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('الملف الشخصي للمعلم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Verification Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">حالة التحقق</h3>
                    @if($tutorDetail->is_verified ?? false)
                        <div class="flex items-center text-green-600">
                            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">حسابك تم التحقق منه</span>
                        </div>
                    @else
                        <div class="flex items-center text-yellow-600">
                            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">بانتظار التحقق من الإدارة</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">أكمل ملفك الشخصي وارفع سيرتك الذاتية لتسريع عملية التحقق.</p>
                    @endif
                </div>
            </div>

            <!-- Profile Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('tutor.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name (read-only) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                            <input type="text" value="{{ $user->name }}" disabled
                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                        </div>

                        <!-- Email (read-only) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                        </div>

                        <!-- Specialization -->
                        <div class="mb-6">
                            <label for="specialization"
                                class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                            <input type="text" name="specialization" id="specialization"
                                value="{{ old('specialization', $tutorDetail->specialization ?? '') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="مثال: البرمجة، الرياضيات، اللغة الإنجليزية...">
                            @error('specialization')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="mb-6">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">نبذة عنك</label>
                            <textarea name="bio" id="bio" rows="4"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="اكتب نبذة مختصرة عن خبراتك ومؤهلاتك...">{{ old('bio', $tutorDetail->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CV Upload -->
                        <div class="mb-6">
                            <label for="cv" class="block text-sm font-medium text-gray-700 mb-2">السيرة الذاتية
                                (CV)</label>
                            @if($tutorDetail->cv_path ?? false)
                                <div class="mb-2 flex items-center text-green-600">
                                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">تم رفع السيرة الذاتية</span>
                                    <a href="{{ route('tutor.profile.cv') }}"
                                        class="mr-2 text-indigo-600 hover:text-indigo-800 text-sm">تحميل</a>
                                </div>
                            @endif
                            <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx"
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2">
                            <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX - الحد الأقصى 5MB</p>
                            @error('cv')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>