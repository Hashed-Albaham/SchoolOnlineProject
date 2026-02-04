<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}"
    class="{{ app()->getLocale() === 'ar' ? 'font-arabic' : 'font-english' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ProSkill') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * {
            font-family: 'Noto Sans Arabic', 'Inter', sans-serif;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1E293B;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #D4AF37 0%, #F5D461 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #D4AF37;
        }

        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Premium Button */
        .btn-premium {
            background: linear-gradient(135deg, #D4AF37 0%, #F5D461 50%, #D4AF37 100%);
            background-size: 200% auto;
            color: #0F172A;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            background-position: right center;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            transform: translateY(-2px);
        }

        /* Card Hover Effect */
        .card-luxury {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-luxury:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }

        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #F5D461 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="antialiased bg-luxury-900 text-white min-h-screen">
    <!-- Top Decorative Line -->
    <div class="fixed top-0 left-0 right-0 h-1 bg-gold-gradient z-50"></div>

    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-luxury-800/50 backdrop-blur-xl border-b border-white/5">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-luxury-950 border-t border-white/5 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gold-gradient flex items-center justify-center">
                            <span class="text-luxury-900 font-bold">P</span>
                        </div>
                        <span class="text-sm text-luxury-400">© {{ date('Y') }} ProSkill. جميع الحقوق محفوظة.</span>
                    </div>
                    <div class="flex items-center gap-6 text-sm text-luxury-400">
                        <a href="#" class="hover:text-gold-400 transition">سياسة الخصوصية</a>
                        <a href="#" class="hover:text-gold-400 transition">الشروط والأحكام</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>