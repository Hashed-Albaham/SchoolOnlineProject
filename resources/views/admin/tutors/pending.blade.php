<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">المعلمون بانتظار التحقق</h2>
                <p class="text-luxury-400 text-sm mt-1">مراجعة طلبات المعلمين الجدد</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tutors.index') }}"
                    class="px-4 py-2 rounded-xl text-sm font-medium border border-white/10 text-luxury-300 hover:bg-white/5 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Tutors Table -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">المعلمون بانتظار التحقق</h3>
                </div>

                @if(isset($tutors) && $tutors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">
                                        المعلم</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">
                                        التخصص</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">
                                        الكورسات</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">
                                        تاريخ الانضمام</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">
                                        إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($tutors as $tutor)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                                                    <span
                                                        class="text-white font-semibold">{{ substr($tutor->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-white">{{ $tutor->name }}</p>
                                                    <p class="text-sm text-luxury-400">{{ $tutor->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-luxury-300">
                                            {{ $tutor->tutorDetails->specialization ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-luxury-300">
                                            {{ $tutor->courses->count() }} كورس
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-luxury-400 text-sm">
                                            {{ $tutor->created_at->format('Y/m/d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg bg-green-500/20 text-green-400 text-sm font-medium hover:bg-green-500/30 transition">
                                                        ✓ توثيق
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.tutors.show', $tutor) }}"
                                                    class="px-3 py-1.5 rounded-lg bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                                    التفاصيل
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($tutors->hasPages())
                        <div class="p-6 border-t border-white/5">
                            {{ $tutors->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400">لا يوجد معلمون بانتظار التحقق 🎉</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>