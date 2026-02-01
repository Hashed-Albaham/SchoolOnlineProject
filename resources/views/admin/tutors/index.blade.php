<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">إدارة المعلمين</h2>
                <p class="text-luxury-400 text-sm mt-1">عرض ومراجعة جميع المعلمين على المنصة</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tutors.pending') }}" 
                    class="px-4 py-2 rounded-xl text-sm font-medium border border-yellow-500/30 text-yellow-400 hover:bg-yellow-500/10 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    بانتظار التحقق ({{ $pendingCount ?? 0 }})
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">إجمالي المعلمين</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $allCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">معلمون موثقون</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $verifiedCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-luxury-400 text-sm">بانتظار التحقق</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $pendingCount ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tutors Table -->
            <div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="font-semibold text-white">جميع المعلمين</h3>
                </div>
                
                @if(isset($tutors) && $tutors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">المعلم</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">التخصص</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">الكورسات</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">تاريخ الانضمام</th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-luxury-400 uppercase tracking-wider">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($tutors as $tutor)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center">
                                                    <span class="text-white font-semibold">{{ substr($tutor->name, 0, 1) }}</span>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tutor->tutorDetails && $tutor->tutorDetails->is_verified)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-green-500/20 text-green-400">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    موثق
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-yellow-500/20 text-yellow-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    بانتظار
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-luxury-400 text-sm">
                                            {{ $tutor->created_at->format('Y/m/d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.tutors.show', $tutor) }}" 
                                                class="px-3 py-1.5 rounded-lg bg-royal-500/20 text-royal-400 text-sm font-medium hover:bg-royal-500/30 transition">
                                                عرض التفاصيل
                                            </a>
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
                        <div class="w-16 h-16 rounded-full bg-luxury-700/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-luxury-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <p class="text-luxury-400">لا يوجد معلمون مسجلون حالياً</p>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</x-app-layout>
