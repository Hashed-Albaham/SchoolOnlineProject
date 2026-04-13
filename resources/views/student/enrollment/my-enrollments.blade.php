<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-luxury-900 mb-6">{{ __('site.my_enrollments') }}</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if($enrollments->count() > 0)
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-luxury-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gold-500 uppercase tracking-wider">
                                            {{ __('site.course') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gold-500 uppercase tracking-wider">
                                            {{ __('site.price') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gold-500 uppercase tracking-wider">
                                            {{ __('site.date') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gold-500 uppercase tracking-wider">
                                            {{ __('site.status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gold-500 uppercase tracking-wider">
                                            {{ __('site.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $enrollment->course->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $enrollment->course->tutor->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if($enrollment->course->price == 0)
                                                        {{ __('site.free') }}
                                                    @else
                                                        {{ $enrollment->course->price }} {{ \App\Models\Setting::get('currency_symbol', '$') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $enrollment->created_at->format('Y-m-d') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($enrollment->enrollment_status === 'approved')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-luxury-100 text-luxury-900 border border-luxury-200">
                                                        {{ __('site.approved') }}
                                                    </span>
                                                @elseif($enrollment->enrollment_status === 'rejected')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                        {{ __('site.rejected') }}
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                        {{ __('site.pending_approval') }}
                                                    </span>
                                                @endif
                                                
                                                <div class="mt-2 text-xs">
                                                @if($enrollment->payment_status === 'paid')
                                                    <span class="text-green-600 font-bold border border-green-200 bg-green-50 px-2 py-0.5 rounded">
                                                        ✅ تم الدفع
                                                    </span>
                                                @elseif($enrollment->payment_status === 'failed')
                                                    <span class="text-red-600 font-bold border border-red-200 bg-red-50 px-2 py-0.5 rounded">
                                                        ❌ فشل الدفع
                                                    </span>
                                                @else
                                                    <span class="text-gray-600 font-bold border border-gray-200 bg-gray-50 px-2 py-0.5 rounded">
                                                        ⏳ بانتظار الدفع
                                                    </span>
                                                @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($enrollment->payment_status === 'pending' && $enrollment->course->price > 0 && $enrollment->enrollment_status !== 'rejected')
                                                    <a href="{{ route('student.enrollment.payment', $enrollment) }}" class="inline-block bg-gold-400 hover:bg-gold-500 text-white px-3 py-1 rounded shadow-sm transition">
                                                        💳 اتمام الدفع
                                                    </a>
                                                @endif
                                                
                                                @if($enrollment->enrollment_status === 'approved' && $enrollment->payment_status === 'paid')
                                                     <a href="{{ route('courses.show', $enrollment->course) }}" class="inline-block bg-luxury-900 hover:bg-luxury-800 text-gold-400 px-3 py-1 rounded shadow-sm transition">
                                                         📺 عرض الكورس
                                                     </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $enrollments->links() }}
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-8 text-center border-dashed border-2 border-gray-200">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <p class="text-gray-500 font-medium">لا توجد اشتراكات حتى الآن.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
