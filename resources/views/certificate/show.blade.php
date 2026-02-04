<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('site.certificate') }} - {{ $certificate->user->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Great+Vibes&display=swap');

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                background: white;
                -webkit-print-color-adjust: exact;
            }

            .certificate-container {
                shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="fixed top-4 left-4 no-print flex gap-2">
        <button onclick="window.print()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            {{ __('site.print') }}
        </button>
        <a href="{{ route('home') }}"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">
            {{ __('site.back') }}
        </a>
    </div>

    <!-- Certificate Container -->
    <div
        class="certificate-container w-[1123px] h-[794px] bg-white text-gray-900 relative shadow-2xl overflow-hidden mx-auto">
        <!-- Border & Frame -->
        <div class="absolute inset-4 border-4 border-double border-gold-500 pointer-events-none z-20"></div>
        <div class="absolute inset-8 border border-gold-300 pointer-events-none z-20"></div>

        <!-- Decorative Corners -->
        <div class="absolute top-4 right-4 w-32 h-32 border-t-4 border-r-4 border-gold-600 rounded-tr-3xl z-20"></div>
        <div class="absolute bottom-4 left-4 w-32 h-32 border-b-4 border-l-4 border-gold-600 rounded-bl-3xl z-20"></div>

        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] z-0">
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-white via-gray-50 to-gold-50/30 z-0"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-between h-full py-20 px-24 text-center">

            <!-- Header -->
            <div class="w-full flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 tracking-wider">CERTIFICATE</h1>
                    <p class="text-gold-600 uppercase tracking-[0.3em] text-sm mt-1">OF COMPLETION</p>
                </div>
                <!-- Logo Placehoder -->
                <div
                    class="w-16 h-16 bg-gold-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg border-2 border-white">
                    PO
                </div>
            </div>

            <!-- Main Body -->
            <div class="mt-8">
                <p class="text-xl text-gray-500 italic">{{ __('site.cert_presented_to') }}</p>

                <h2
                    class="text-6xl font-script text-gold-600 my-6 py-2 border-b-2 border-gray-100 inline-block px-12 min-w-[500px]">
                    {{ $certificate->user->name }}
                </h2>

                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    {{ __('site.cert_completion_msg') }}
                </p>

                <h3 class="text-3xl font-bold text-gray-800 mt-4 mb-2">{{ $certificate->course->title }}</h3>

                <div class="text-sm text-gray-400 mt-8">
                    {{ __('site.issue_date') }}: {{ $certificate->issued_at->format('F d, Y') }}
                </div>
            </div>

            <!-- Footer / Signatures -->
            <div class="w-full flex justify-between items-end mt-12 px-12">
                <div class="text-center">
                    <div class="h-16 flex items-end justify-center mb-2">
                        <img src="" alt="Signature" class="h-12 opacity-80" onerror="this.style.display='none'">
                    </div>
                    <div class="w-48 border-t border-gray-400 pt-2">
                        <p class="font-bold text-gray-700">{{ $certificate->course->tutor->name ?? 'Instructor' }}</p>
                        <p class="text-xs text-gray-500 uppercase">{{ __('site.instructor') }}</p>
                    </div>
                </div>

                <!-- QR Verification -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-white border border-gray-200 p-1 mb-2 mx-auto">
                        <!-- Simulated QR Code -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('certificate.verify', $certificate->certificate_code) }}"
                            alt="QR" class="w-full h-full">
                    </div>
                    <p class="text-[10px] text-gray-400 tracking-wider">ID: {{ $certificate->certificate_code }}</p>
                </div>

                <div class="text-center">
                    <div class="h-16 flex items-end justify-center mb-2">
                        <!-- Platform Signature -->
                        <span class="font-script text-2xl text-gold-600">ProSkill Platform</span>
                    </div>
                    <div class="w-48 border-t border-gray-400 pt-2">
                        <p class="font-bold text-gray-700">ProSkill Director</p>
                        <p class="text-xs text-gray-500 uppercase">{{ __('site.director') }}</p>
                    </div>
                </div>
            </div>

            <!-- Link -->
            <div class="absolute bottom-4 left-0 right-0 text-center">
                <p class="text-[10px] text-gray-300">{{ route('certificate.verify', $certificate->certificate_code) }}
                </p>
            </div>
        </div>
    </div>

</body>

</html>