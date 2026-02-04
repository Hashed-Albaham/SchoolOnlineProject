<div class="bg-luxury-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mt-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-white mb-1">تقييمات الطلاب</h3>
            <div class="flex items-center gap-2">
                <div class="flex text-gold-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-600' }}"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                    @endfor
                </div>
                <span class="text-white font-bold">{{ number_format($averageRating, 1) }}</span>
                <span class="text-luxury-400 text-sm">({{ $totalReviews }} تقييم)</span>
            </div>
        </div>
    </div>

    <!-- Review Form -->
    @if($canReview)
        <div class="bg-white/5 rounded-xl p-6 mb-8 border border-white/5">
            <h4 class="text-lg font-semibold text-white mb-4">أضف تقييمك</h4>
            <form wire:submit.prevent="submitReview">
                <div class="mb-4">
                    <label class="block text-luxury-300 text-sm mb-2">التقييم</label>
                    <div class="flex gap-2 text-gold-400">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})"
                                class="focus:outline-none transition transform hover:scale-110">
                                <svg class="w-8 h-8 {{ $i <= $rating ? 'fill-current' : 'text-gray-600' }}" viewBox="0 0 24 24">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-luxury-300 text-sm mb-2">تعليقك (اختياري)</label>
                    <textarea wire:model="comment" rows="3"
                        class="w-full bg-luxury-900 border border-white/10 rounded-lg text-white placeholder-luxury-500 focus:border-gold-500/50 focus:ring-0"
                        placeholder="ما رأيك في هذا الكورس؟"></textarea>
                    @error('comment') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-gold-gradient text-luxury-900 font-bold rounded-lg hover:shadow-glow transition">
                        نشر التقييم
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Reviews List -->
    <div class="space-y-6">
        @forelse($reviews as $review)
            <div class="border-b border-white/5 pb-6 last:border-0 last:pb-0">
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-royal-500 to-royal-700 flex items-center justify-center text-white font-bold flex-shrink-0">
                        {{ substr($review->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-white font-medium">{{ $review->user->name }}</h5>
                                <div class="flex text-gold-400 text-xs mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-600' }}"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-luxury-500 text-xs">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)
                            <p class="text-luxury-300 text-sm mt-3 leading-relaxed">{{ $review->comment }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-luxury-400">
                لا توجد تقييمات حتى الآن. كن أول من يقيم!
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>