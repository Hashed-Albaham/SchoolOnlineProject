@php use Mcamara\LaravelLocalization\Facades\LaravelLocalization; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}"
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

            /* Gradient Text */
            .text-gradient {
                background: linear-gradient(135deg, #D4AF37 0%, #F5D461 50%, #D4AF37 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Scrollbar Styling */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #1E293B;
            }

            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #D4AF37, #F5D461);
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-luxury-900 text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
