@props([
    'user',
    'sizeClasses' => 'w-10 h-10',
    'iconClasses' => 'w-5 h-5',
    'textClasses' => 'text-white' // default for icon color
])

@php
    $hasAvatar = !empty($user->avatar);
    
    // Fallback: Generate a consistent color based on user ID
    $colors = [
        'from-blue-500 to-blue-700',
        'from-purple-500 to-purple-700',
        'from-green-500 to-green-700',
        'from-red-500 to-red-700',
        'from-yellow-500 to-yellow-700',
        'from-pink-500 to-pink-700',
        'from-indigo-500 to-indigo-700',
        'from-teal-500 to-teal-700',
        'from-orange-500 to-orange-700',
        'from-royal-500 to-royal-700',
    ];
    $colorIndex = $user->id % count($colors);
    $bgGradient = $colors[$colorIndex];
    
    $isAdmin = $user->role === 'admin';
    $isTutor = $user->role === 'tutor';
@endphp

@if($hasAvatar)
    <div class="{{ $sizeClasses }} rounded-[inherit] overflow-hidden flex-shrink-0">
        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
    </div>
@else
    <div class="{{ $sizeClasses }} rounded-[inherit] bg-gradient-to-br {{ $bgGradient }} flex items-center justify-center flex-shrink-0">
        @if($isAdmin)
            {{-- Shield icon for admin --}}
            <svg class="{{ $iconClasses }} {{ $textClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        @elseif($isTutor)
            {{-- Briefcase icon for tutor --}}
            <svg class="{{ $iconClasses }} {{ $textClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        @else
            {{-- Academic cap for student --}}
            <svg class="{{ $iconClasses }} {{ $textClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
            </svg>
        @endif
    </div>
@endif
