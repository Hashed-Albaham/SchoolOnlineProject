<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ProSkill - منصة التعلم الإلكتروني الفاخرة</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Noto Sans Arabic', 'Inter', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 30%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .text-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #F5D461 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-premium {
            background: linear-gradient(135deg, #D4AF37 0%, #F5D461 50%, #D4AF37 100%);
            background-size: 200% auto;
            color: #0F172A;
            font-weight: 600;
            transition: all 0.4s ease;
        }

        .btn-premium:hover {
            background-position: right center;
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.5);
            transform: translateY(-3px);
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-glass:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0F172A;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #D4AF37, #F5D461);
            border-radius: 10px;
        }
    </style>
</head>

<body class="antialiased bg-[#0F172A] text-white">
    <!-- Decorative Top Bar -->
    <div class="fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 via-royal-500 to-gold-500 z-50"></div>

    <!-- Header -->
    <header class="fixed w-full z-40 bg-[#0F172A]/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center shadow-lg shadow-gold-500/20">
                        <span class="text-[#0F172A] font-bold text-2xl">P</span>
                    </div>
                    <span class="text-2xl font-bold text-gradient">ProSkill</span>
                </div>

                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('courses.index') }}"
                        class="text-gray-300 hover:text-gold-400 font-medium transition-colors">الكورسات</a>
                    <a href="#features"
                        class="text-gray-300 hover:text-gold-400 font-medium transition-colors">المميزات</a>
                    <a href="#stats"
                        class="text-gray-300 hover:text-gold-400 font-medium transition-colors">إحصائيات</a>
                </nav>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-premium px-6 py-2.5 rounded-xl font-semibold">
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-300 hover:text-white font-medium transition-colors hidden sm:block">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="btn-premium px-6 py-2.5 rounded-xl font-semibold">
                            ابدأ الآن
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-right">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gold-500/10 border border-gold-500/20 text-gold-400 text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-gold-400 rounded-full animate-pulse"></span>
                        منصة التعلم الأولى في العالم العربي
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        استثمر في نفسك مع
                        <span class="text-gradient block mt-2">أفضل الخبراء</span>
                    </h1>

                    <p class="text-lg md:text-xl text-gray-400 mb-8 max-w-xl mx-auto lg:mx-0">
                        انضم إلى آلاف المتعلمين واحصل على كورسات احترافية في البرمجة،
                        التصميم، والذكاء الاصطناعي من معلمين معتمدين.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="btn-premium px-8 py-4 rounded-xl text-lg font-bold inline-flex items-center justify-center gap-2">
                            <span>ابدأ مجاناً</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="{{ route('courses.index') }}"
                            class="px-8 py-4 rounded-xl text-lg font-semibold border border-white/20 hover:border-gold-500/50 hover:bg-white/5 transition-all inline-flex items-center justify-center gap-2">
                            <span>تصفح الكورسات</span>
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="flex items-center gap-6 justify-center lg:justify-start mt-10">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gold-400">+1000</p>
                            <p class="text-sm text-gray-500">طالب مسجل</p>
                        </div>
                        <div class="w-px h-10 bg-white/10"></div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gold-400">+50</p>
                            <p class="text-sm text-gray-500">معلم خبير</p>
                        </div>
                        <div class="w-px h-10 bg-white/10"></div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gold-400">4.9⭐</p>
                            <p class="text-sm text-gray-500">تقييم</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Illustration -->
                <div class="hidden lg:flex justify-center">
                    <div class="relative">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-gold-500/20 to-royal-500/20 rounded-3xl blur-3xl">
                        </div>
                        <div class="relative w-96 h-96 floating">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-[#1E293B] to-[#0F172A] rounded-3xl border border-white/10 p-8">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-gold-500/20 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold">كورسات احترافية</p>
                                            <p class="text-sm text-gray-500">+100 كورس</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-royal-500/20 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-royal-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold">فيديوهات عالية الجودة</p>
                                            <p class="text-sm text-gray-500">HD & 4K</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold">شهادات معتمدة</p>
                                            <p class="text-sm text-gray-500">عند إتمام الكورس</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 p-4 rounded-xl bg-gold-500/10 border border-gold-500/20">
                                        <p class="text-sm text-gold-400 font-medium">🎉 عرض خاص!</p>
                                        <p class="text-xs text-gray-400 mt-1">خصم 50% على الكورسات المميزة</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-[#0F172A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-gold-400 font-semibold mb-3">لماذا تختارنا؟</p>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">مميزات تجعلنا <span class="text-gradient">الأفضل</span>
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto">نقدم لك تجربة تعليمية فريدة مع أحدث التقنيات وأفضل المعلمين
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-glass rounded-2xl p-8">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center mb-6 shadow-lg shadow-gold-500/20">
                        <svg class="w-7 h-7 text-[#0F172A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">محتوى احترافي</h3>
                    <p class="text-gray-400">كورسات مُعدّة بعناية من خبراء في مجالاتهم مع شرح مفصل وتطبيقات عملية</p>
                </div>

                <!-- Feature 2 -->
                <div class="card-glass rounded-2xl p-8">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-royal-500 to-royal-600 flex items-center justify-center mb-6 shadow-lg shadow-royal-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">تعلم بمرونة</h3>
                    <p class="text-gray-400">تعلم في أي وقت ومن أي مكان. وصول مدى الحياة لجميع الكورسات</p>
                </div>

                <!-- Feature 3 -->
                <div class="card-glass rounded-2xl p-8">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-6 shadow-lg shadow-green-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">دعم مباشر</h3>
                    <p class="text-gray-400">تواصل مباشر مع المعلمين للإجابة على أسئلتك ومتابعة تقدمك</p>
                </div>

                <!-- Feature 4 -->
                <div class="card-glass rounded-2xl p-8">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mb-6 shadow-lg shadow-pink-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">شهادات معتمدة</h3>
                    <p class="text-gray-400">احصل على شهادة إتمام معتمدة تضيفها إلى سيرتك الذاتية</p>
                </div>

                <!-- Feature 5 -->
                <div class="card-glass rounded-2xl p-8">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">تطبيقات عملية</h3>
                    <p class="text-gray-400">مشاريع حقيقية وتطبيقات عملية لتثبيت المعلومات</p>
                </div>

                <!-- Feature 6 -->
                <div class="card-glass rounded-2xl p-8">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mb-6 shadow-lg shadow-orange-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">تحديثات مستمرة</h3>
                    <p class="text-gray-400">محتوى محدث باستمرار ليواكب أحدث التقنيات والتطورات</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-24 bg-gradient-to-b from-[#0F172A] to-[#1E293B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-glass rounded-3xl p-8 md:p-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <p class="text-4xl md:text-5xl font-bold text-gradient mb-2">+100</p>
                        <p class="text-gray-400">كورس متاح</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl md:text-5xl font-bold text-gradient mb-2">+50</p>
                        <p class="text-gray-400">معلم خبير</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl md:text-5xl font-bold text-gradient mb-2">+1K</p>
                        <p class="text-gray-400">طالب مسجل</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl md:text-5xl font-bold text-gradient mb-2">4.9</p>
                        <p class="text-gray-400">تقييم المنصة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-[#0F172A] relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-gold-500/5 to-royal-500/5"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                جاهز لبدء رحلة التعلم؟
            </h2>
            <p class="text-xl text-gray-400 mb-10">
                انضم إلى مجتمعنا اليوم واستثمر في مستقبلك المهني
            </p>
            <a href="{{ route('register') }}"
                class="btn-premium px-10 py-5 rounded-xl text-xl font-bold inline-flex items-center gap-3">
                <span>سجل مجاناً الآن</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                    </path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0a0f1a] border-t border-white/5 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center">
                            <span class="text-[#0F172A] font-bold text-2xl">P</span>
                        </div>
                        <span class="text-xl font-bold text-gradient">ProSkill</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        منصة التعلم الإلكتروني الرائدة في العالم العربي. نقدم كورسات احترافية لمساعدتك على تطوير
                        مهاراتك.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-4">روابط سريعة</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('courses.index') }}"
                                class="text-gray-400 hover:text-gold-400 transition">الكورسات</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-gold-400 transition">المعلمون</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-gold-400 transition">من نحن</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-4">الدعم</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-gold-400 transition">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-gold-400 transition">اتصل بنا</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-gold-400 transition">سياسة الخصوصية</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-4">تواصل معنا</h4>
                    <p class="text-gray-400 text-sm mb-4">info@proskill.com</p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-gold-500/20 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-gold-500/20 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/5 mt-12 pt-8 text-center">
                <p class="text-gray-400 text-sm">© {{ date('Y') }} ProSkill. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>
</body>

</html>