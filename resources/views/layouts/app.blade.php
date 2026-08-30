<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'উপলব্ধি সমবায় সমিতি | Upolobdi Somobay Somiti (USS)')</title>
    <meta name="description" content="৬ বন্ধুর আন্তরিক ঐক্য ও সমৃদ্ধ ভবিষ্যৎ গড়ার লক্ষ্যে প্রতিষ্ঠিত সমবায় সমিতি — স্থাপিত ২০২০। সত্যের পথে স্বপ্নের অভিযান।">
    
    <!-- Favicons -->
    <link rel="icon" type="image/jpeg" href="/assets/images/user-logo.jpg">
    <link rel="shortcut icon" href="/assets/images/user-logo.jpg">

    <!-- Google Fonts: Hind Siliguri (Bengali), Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#0A2540',
                            navyLight: '#14385C',
                            navyDark: '#061729',
                            green: '#00875A',
                            greenLight: '#10B981',
                            greenDark: '#046A47',
                            gold: '#F59E0B',
                            goldLight: '#FBBF24',
                            goldDark: '#D97706',
                            goldAccent: '#FEF3C7',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Hind Siliguri"', 'sans-serif'],
                        bangla: ['"Hind Siliguri"', '"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif;
        }
        .font-bangla {
            font-family: 'Hind Siliguri', sans-serif;
        }

        /* 3D Animations & Floating */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(1deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 25px rgba(245, 158, 11, 0.35); }
            50% { box-shadow: 0 0 45px rgba(16, 185, 129, 0.5); }
        }
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        @keyframes rotateSlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .animate-float {
            animation: floatSlow 5s ease-in-out infinite;
        }
        .animate-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }
        .animate-spin-slow {
            animation: rotateSlow 20s linear infinite;
        }

        /* Infinite Smooth Auto Moving Carousel */
        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 25s linear infinite;
        }
        .marquee-track:hover {
            animation-play-state: paused;
        }

        /* Masonry Grid CSS */
        .masonry-grid {
            column-count: 1;
            column-gap: 1.5rem;
        }
        @media (min-width: 640px) {
            .masonry-grid {
                column-count: 2;
            }
        }
        @media (min-width: 1024px) {
            .masonry-grid {
                column-count: 3;
            }
        }
        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #061729;
        }
        ::-webkit-scrollbar-thumb {
            background: #00875A;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
        }

        .hero-bg {
            background-color: #061729;
            background-image: 
                radial-gradient(at 10% 15%, rgba(0, 135, 90, 0.3) 0px, transparent 50%),
                radial-gradient(at 90% 20%, rgba(245, 158, 11, 0.25) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(10, 37, 64, 0.8) 0px, transparent 60%);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-brand-gold selection:text-brand-navy antialiased">

    <!-- Royal Global Preloader Overlay -->
    <div id="global-preloader" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/90 backdrop-blur-xl transition-all duration-300">
        <div class="flex flex-col items-center justify-center text-center p-6 space-y-4 max-w-sm">
            <div class="relative w-24 h-24 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-brand-gold/20 border-t-brand-gold border-r-brand-green animate-spin"></div>
                <div class="absolute inset-2 rounded-full border-4 border-emerald-500/20 border-b-emerald-400 animate-spin-slow"></div>
                <div class="w-16 h-16 rounded-full bg-slate-900 border-2 border-brand-gold/60 p-1 shadow-2xl overflow-hidden animate-pulse">
                    <img src="/assets/images/user-logo.jpg" alt="USS Emblem" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="space-y-1">
                <h4 id="preloader-title" class="text-base font-extrabold text-white font-bangla tracking-wide">অনুগ্রহ করে অপেক্ষা করুন...</h4>
                <p id="preloader-subtitle" class="text-xs text-brand-gold font-bangla animate-pulse">উপলব্ধি সমবায় সমিতি (USS)</p>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div id="flash-toast" class="fixed top-24 right-6 z-50 flex items-center gap-3 bg-emerald-600/95 text-white px-5 py-3.5 rounded-2xl shadow-2xl backdrop-blur-lg border border-emerald-400/40 animate-bounce">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-toast').remove()" class="ml-2 hover:text-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div id="flash-toast-info" class="fixed top-24 right-6 z-50 flex items-center gap-3 bg-blue-600/95 text-white px-5 py-3.5 rounded-2xl shadow-2xl backdrop-blur-lg border border-blue-400/40">
            <i class="fa-solid fa-circle-info text-xl"></i>
            <span class="font-medium text-sm">{{ session('info') }}</span>
            <button onclick="document.getElementById('flash-toast-info').remove()" class="ml-2 hover:text-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div id="flash-toast-error" class="fixed top-24 right-6 z-50 flex items-start gap-3 bg-rose-600/95 text-white px-5 py-3.5 rounded-2xl shadow-2xl backdrop-blur-lg border border-rose-400/40">
            <i class="fa-solid fa-triangle-exclamation text-xl mt-0.5"></i>
            <div>
                @foreach($errors->all() as $err)
                    <p class="font-medium text-sm">{{ $err }}</p>
                @endforeach
            </div>
            <button onclick="document.getElementById('flash-toast-error').remove()" class="ml-2 hover:text-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Main Navigation Bar -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-slate-950/90 backdrop-blur-xl border-b border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-2">
            
            <!-- Logo & Brand Title -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group flex-shrink-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full p-0.5 bg-gradient-to-tr from-brand-gold to-brand-green group-hover:scale-105 transition-transform duration-300 shadow-lg overflow-hidden bg-white flex-shrink-0">
                    <img src="/assets/images/user-logo.jpg" alt="USS Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-extrabold tracking-tight text-white group-hover:text-brand-gold transition-colors font-bangla">
                        <!-- Mobile view: Only show Upolobdi / উপলব্ধি -->
                        <span class="sm:hidden font-black text-amber-300" data-lang-text="brand_short">উপলব্ধি</span>
                        <!-- Tablet & Desktop view: Full somiti name -->
                        <span class="hidden sm:inline" data-lang-text="brand_title_bn">উপলব্ধি সমবায় সমিতি</span>
                        <span data-lang-text="brand_title_en" class="hidden text-xs font-sans tracking-wide text-brand-gold">USS Somobay</span>
                    </span>
                    <span class="hidden sm:flex text-xs text-emerald-400 font-medium tracking-wider items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span data-lang-text="est_badge">স্থাপিত ২০২০</span>
                    </span>
                </div>
            </a>

            <!-- Desktop Menu Links -->
            <nav class="hidden lg:flex items-center gap-5 text-sm font-semibold">
                <a href="{{ route('home') }}#overview" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_overview">একনজরে</a>
                <a href="{{ route('home') }}#members-carousel" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_members">সদস্যবৃন্দ</a>
                <a href="{{ route('home') }}#projects" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_projects">প্রকল্পসমূহ</a>
                <a href="{{ route('home') }}#highlights" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_highlights">হাইলাইটস</a>
                <a href="{{ route('home') }}#events" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_events">ইভেন্ট গ্যালারি</a>
                <a href="{{ route('home') }}#calculator" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_calc">ক্যালকুলেটর</a>
                <a href="{{ route('home') }}#rules" class="text-slate-300 hover:text-brand-gold transition-colors" data-lang-text="nav_rules">নীতিমালা</a>
            </nav>

            <!-- Right Controls: Language Switcher, Auth & Mobile Hamburger -->
            <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                <!-- Language Toggle Button -->
                <button id="lang-toggle-btn" onclick="toggleLanguage()" class="flex items-center gap-1 sm:gap-2 px-2 py-1.5 sm:px-3 sm:py-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 text-xs font-bold text-slate-200 transition-all cursor-pointer" title="Toggle Language">
                    <i class="fa-solid fa-globe text-brand-gold text-xs sm:text-sm"></i>
                    <span id="current-lang-label" class="hidden sm:inline">English</span>
                    <span id="current-lang-label-mobile" class="sm:hidden font-mono text-[11px]">EN</span>
                </button>

                @auth
                    <!-- Authenticated User Dashboard CTA -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-gradient-to-r from-brand-green to-emerald-600 hover:from-emerald-500 hover:to-emerald-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-900/40 hover:scale-105 transition-all">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span class="hidden sm:inline" data-lang-text="btn_dashboard">সদস্য পোর্টাল</span>
                        <span class="sm:hidden text-xs">পোর্টাল</span>
                    </a>
                    
                    <!-- Logout button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 sm:p-2.5 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 text-xs transition-colors flex items-center justify-center cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @else
                    <!-- Clean Login Trigger -->
                    <button onclick="openLoginModal()" class="flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-600 text-brand-navyDark font-extrabold text-xs sm:text-sm shadow-lg shadow-amber-900/30 hover:scale-105 transition-all cursor-pointer">
                        <i class="fa-solid fa-user-lock"></i>
                        <span data-lang-text="btn_login">লগইন</span>
                    </button>
                @endauth

                <!-- Mobile Menu Hamburger Button (Always visible on mobile/tablet) -->
                <button onclick="toggleMobileMenu()" aria-label="Toggle Menu" class="lg:hidden p-2 sm:p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 hover:text-brand-gold border border-white/10 transition-colors flex items-center justify-center flex-shrink-0 cursor-pointer">
                    <i class="fa-solid fa-bars text-base sm:text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-slate-900/98 backdrop-blur-2xl border-b border-white/15 px-5 py-4 space-y-3 font-semibold text-sm max-h-[80vh] overflow-y-auto shadow-2xl">
            @auth
            <!-- Logged-in Quick Links on Mobile Drawer -->
            <div class="p-3 rounded-2xl bg-white/[0.04] border border-white/10 space-y-2 mb-3">
                <div class="flex items-center justify-between pb-2 border-b border-white/10">
                    <span class="text-xs text-brand-gold font-bangla font-bold">
                        <i class="fa-solid fa-user-circle mr-1"></i>
                        {{ Auth::user()->get('bangla_name') ?: Auth::user()->get('name') ?: Auth::user()->email() }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-mono">লগইনকৃত</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                    <a href="{{ route('dashboard') }}" onclick="toggleMobileMenu()" class="p-2 rounded-xl bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-200 border border-emerald-500/30 flex items-center gap-1.5 font-bold font-bangla">
                        <i class="fa-solid fa-gauge-high"></i> ড্যাশবোর্ড
                    </a>
                    <a href="{{ route('dashboard') }}#payment-ledger" onclick="toggleMobileMenu()" class="p-2 rounded-xl bg-amber-500/20 hover:bg-amber-500/40 text-amber-200 border border-amber-500/30 flex items-center gap-1.5 font-bold font-bangla">
                        <i class="fa-solid fa-receipt"></i> পেমেন্ট লেজার
                    </a>
                </div>
            </div>
            @endauth

            <div class="space-y-2 text-xs sm:text-sm">
                <a href="{{ route('home') }}#overview" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_overview">একনজরে</a>
                <a href="{{ route('home') }}#members-carousel" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_members">সদস্যবৃন্দ</a>
                <a href="{{ route('home') }}#projects" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_projects">প্রকল্পসমূহ</a>
                <a href="{{ route('home') }}#highlights" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_highlights">হাইলাইটস</a>
                <a href="{{ route('home') }}#events" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_events">ইভেন্ট গ্যালারি</a>
                <a href="{{ route('home') }}#calculator" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_calc">ক্যালকুলেটর</a>
                <a href="{{ route('home') }}#rules" onclick="toggleMobileMenu()" class="block py-1.5 text-slate-200 hover:text-brand-gold" data-lang-text="nav_rules">নীতিমালা</a>
            </div>
        </div>
    </header>

    <!-- Main View Content Area -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Facebook Messenger-Style Fullscreen Lightbox & Swipe Modal with Blurred Low-Opacity Backdrop -->
    <div id="lightboxModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 select-none overflow-hidden">
        <!-- Ambient Blurred Low-Opacity Background Image (Messenger Style) -->
        <div id="lightboxBackdropImg" class="absolute inset-0 bg-cover bg-center filter blur-3xl scale-125 opacity-30 transition-all duration-700 pointer-events-none"></div>
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl"></div>

        <button onclick="closeLightbox()" class="absolute top-5 right-5 text-slate-300 hover:text-white text-2xl z-50 p-2.5 rounded-full bg-white/10 hover:bg-white/20 transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <button onclick="prevLightbox()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white bg-slate-900/80 hover:bg-slate-800 border border-white/15 p-4 rounded-full text-xl z-50 transition-all hover:scale-110 shadow-2xl">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <button onclick="nextLightbox()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white bg-slate-900/80 hover:bg-slate-800 border border-white/15 p-4 rounded-full text-xl z-50 transition-all hover:scale-110 shadow-2xl">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <div class="relative z-10 max-w-5xl max-h-[85vh] flex flex-col items-center justify-center">
            <img id="lightboxImg" src="" alt="Full View" class="max-h-[75vh] max-w-full object-contain rounded-2xl shadow-2xl border border-white/20">
            <div class="mt-4 text-center bg-slate-950/80 px-6 py-2 rounded-2xl border border-white/10 backdrop-blur-md">
                <h4 id="lightboxTitle" class="text-base font-bold text-white font-bangla"></h4>
                <p id="lightboxCounter" class="text-xs text-brand-gold font-mono mt-0.5"></p>
            </div>
        </div>
    </div>

    <!-- Clean, Sleek Professional Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
        <div class="bg-slate-900 border border-white/15 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative animate-float">
            <button onclick="closeLoginModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full p-1 bg-gradient-to-tr from-brand-gold to-brand-green shadow-xl overflow-hidden bg-white">
                    <img src="/assets/images/user-logo.jpg" alt="USS Logo" class="w-full h-full object-contain">
                </div>
                <h3 class="text-xl font-black text-white font-bangla" data-lang-text="login_modal_title">সমিতি সদস্য পোর্টাল</h3>
                <p class="text-xs text-slate-400 mt-1" data-lang-text="login_modal_subtitle">আপনার কিস্তির হিসাব ও কার্যক্রম পরিচালনা করুন</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" onsubmit="showPreloader('অনুমোদন ও লগইন যাচাই করা হচ্ছে...', 'উপলব্ধি সমবায় সমিতি (USS)')" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1" data-lang-text="login_email_label">ইমেইল ঠিকানা</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-500 text-sm"></i>
                        <input type="email" id="login_email" name="email" required placeholder="your@email.com" class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:border-brand-gold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1" data-lang-text="login_pass_label">পাসওয়ার্ড</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-500 text-sm"></i>
                        <input type="password" id="login_password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:border-brand-gold">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded bg-slate-800 border-white/20 text-brand-gold focus:ring-0">
                        <span data-lang-text="login_remember">লগইন মনে রাখুন</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-600 text-brand-navyDark font-extrabold text-sm shadow-lg hover:scale-[1.02] transition-all cursor-pointer">
                    <span data-lang-text="login_submit_btn">প্রবেশ করুন (Sign In)</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-white/10 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full p-0.5 bg-white overflow-hidden shadow-lg">
                            <img src="/assets/images/user-logo.jpg" alt="USS Logo" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-white font-bangla">উপলব্ধি সমবায় সমিতি (USS)</h4>
                            <p class="text-xs text-brand-gold font-bangla">সত্যের পথে স্বপ্নের অভিযান — স্থাপিত ২০২০</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed pr-6" data-lang-text="footer_desc">
                        {{ $settings['footer_desc_bn'] ?? '৬ বন্ধুর আন্তরিকতা ও পারস্পরিক আর্থিক সহযোগিতায় ভবিষ্যতের বড় কোনো স্বপ্ন বাস্তবায়নে আমাদের এই সমবায় পদযাত্রা। স্বচ্ছতা, ভ্রাতৃত্ব ও অটুট বন্ধুত্বই আমাদের মূল শক্তি।' }}
                    </p>
                    <div class="flex items-center gap-3 text-slate-400 text-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs text-slate-300 font-mono">Laravel 11 &bull; Statamic Flat-File Architecture</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4" data-lang-text="footer_links_title">দ্রুত লিংক</h5>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-400 font-bangla">
                        <li><a href="#overview" class="hover:text-brand-gold transition-colors" data-lang-text="nav_overview">একনজরে সমিতি</a></li>
                        <li><a href="#members-carousel" class="hover:text-brand-gold transition-colors" data-lang-text="nav_members">সদস্যবৃন্দ ক্যারোসেল</a></li>
                        <li><a href="#projects" class="hover:text-brand-gold transition-colors" data-lang-text="nav_projects">বিনিয়োগ প্রকল্পসমূহ</a></li>
                        <li><a href="#highlights" class="hover:text-brand-gold transition-colors" data-lang-text="nav_highlights">হাইলাইটস মোমেন্টস</a></li>
                        <li><a href="#events" class="hover:text-brand-gold transition-colors" data-lang-text="nav_events">ইভেন্ট গ্যালারি</a></li>
                        <li><a href="#rules" class="hover:text-brand-gold transition-colors" data-lang-text="nav_rules">সমিতির বর্তমান নীতিমালা</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4" data-lang-text="footer_contact_title">যোগাযোগ ও জমা</h5>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-brand-gold"></i>
                            <span>{{ $settings['contact_phone'] ?? '+880 1712-345678' }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-brand-green"></i>
                            <span>{{ $settings['contact_email'] ?? 'info@upolobdi-somiti.org' }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-rose-400"></i>
                            <span>{{ $settings['contact_address'] ?? 'ঢাকা, বাংলাদেশ (Dhaka, Bangladesh)' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} উপলব্ধি সমবায় সমিতি (USS). সর্বস্বত্ব সংরক্ষিত।</p>
                <p class="font-bangla text-slate-400">{{ $settings['motto_bn'] ?? 'বন্ধুত্বের বন্ধনে স্বপ্নের পথে এগিয়ে চলা' }}</p>
            </div>
        </div>
    </footer>

    <!-- Bilingual Dictionary Script & Lightbox Logic -->
    <script>
        const translations = {
            bn: {
                brand_short: "উপলব্ধি",
                brand_title_bn: "উপলব্ধি সমবায় সমিতি",
                brand_title_en: "USS Somobay",
                est_badge: "স্থাপিত ২০২০",
                nav_overview: "একনজরে",
                nav_vision: "আমাদের স্বপ্ন",
                nav_members: "সদস্যবৃন্দ",
                nav_projects: "প্রকল্পসমূহ",
                nav_highlights: "হাইলাইটস",
                nav_events: "ইভেন্ট গ্যালারি",
                nav_calc: "ক্যালকুলেটর",
                nav_rules: "নীতিমালা",
                btn_dashboard: "সদস্য পোর্টাল",
                btn_login: "লগইন",
                login_modal_title: "সমিতি সদস্য পোর্টাল",
                login_modal_subtitle: "আপনার কিস্তির হিসাব ও কার্যক্রম পরিচালনা করুন",
                login_email_label: "ইমেইল ঠিকানা",
                login_pass_label: "পাসওয়ার্ড",
                login_remember: "লগইন মনে রাখুন",
                login_submit_btn: "প্রবেশ করুন (Sign In)",
                footer_desc: "{{ addslashes($settings['footer_desc_bn'] ?? '৬ বন্ধুর আন্তরিকতা ও পারস্পরিক আর্থিক সহযোগিতায় ভবিষ্যতের বড় কোনো স্বপ্ন বাস্তবায়নে আমাদের এই সমবায় পদযাত্রা। স্বচ্ছতা, ভ্রাতৃত্ব ও অটুট বন্ধুত্বই আমাদের মূল শক্তি।') }}",
                footer_links_title: "দ্রুত লিংক",
                footer_contact_title: "যোগাযোগ ও জমা"
            },
            en: {
                brand_short: "Upolobdi",
                brand_title_bn: "Upolobdi Somobay Somiti",
                brand_title_en: "USS Cooperative",
                est_badge: "Est. 2020",
                nav_overview: "Overview",
                nav_vision: "Our Vision",
                nav_members: "Members",
                nav_projects: "Projects",
                nav_highlights: "Highlights",
                nav_events: "Events Gallery",
                nav_calc: "Calculator",
                nav_rules: "Bylaws",
                btn_dashboard: "Member Portal",
                btn_login: "Sign In",
                login_modal_title: "Member Portal Login",
                login_modal_subtitle: "Manage your monthly installments and records",
                login_email_label: "Email Address",
                login_pass_label: "Password",
                login_remember: "Remember me",
                login_submit_btn: "Sign In",
                footer_desc: "{{ addslashes($settings['footer_desc_en'] ?? 'A cooperative initiative of 6 lifelong friends pooling monthly installments towards ambitious future investments. Trust, transparency, and brotherhood are our core pillars.') }}",
                footer_links_title: "Quick Links",
                footer_contact_title: "Contact & Deposit"
            }
        };

        // Standard Cookie Helper Functions
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + encodeURIComponent(value || "") + expires + "; path=/; SameSite=Lax";
        }

        function getCookie(name) {
            let nameEQ = name + "=";
            let ca = document.cookie.split(';');
            for(let i=0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        }

        // 24-Hour Modal Dismissal Tracking via Cookies
        function dismissModalFor24Hours(modalKey) {
            setCookie('uss_dismiss_' + modalKey, '1', 1); // 1 day = 24 hours
            try { localStorage.setItem('uss_dismiss_' + modalKey, Date.now() + (24 * 60 * 60 * 1000)); } catch(e) {}
        }

        function isModalDismissed(modalKey) {
            if (getCookie('uss_dismiss_' + modalKey)) return true;
            try {
                const exp = localStorage.getItem('uss_dismiss_' + modalKey);
                if (exp && parseInt(exp, 10) > Date.now()) return true;
            } catch(e) {}
            return false;
        }

        // Read language from Cookies first, then localStorage, default 'bn'
        let currentLang = getCookie('uss_lang') || localStorage.getItem('uss_lang') || 'bn';

        function applyLanguage(lang) {
            currentLang = lang;
            setCookie('uss_lang', lang, 365);
            try { localStorage.setItem('uss_lang', lang); } catch(e) {}
            document.documentElement.lang = lang;
            
            const btnLabel = document.getElementById('current-lang-label');
            if (btnLabel) {
                btnLabel.innerText = (lang === 'bn') ? 'English' : 'বাংলা';
            }

            document.querySelectorAll('[data-lang-text]').forEach(el => {
                const key = el.getAttribute('data-lang-text');
                if (translations[lang] && translations[lang][key]) {
                    el.innerText = translations[lang][key];
                }
            });

            document.querySelectorAll('[data-lang-bn]').forEach(el => {
                el.style.display = (lang === 'bn') ? '' : 'none';
            });
            document.querySelectorAll('[data-lang-en]').forEach(el => {
                el.style.display = (lang === 'en') ? '' : 'none';
            });
        }

        function toggleLanguage() {
            const nextLang = (currentLang === 'bn') ? 'en' : 'bn';
            applyLanguage(nextLang);
        }


        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Messenger-style Lightbox with swipe & backdrop support
        let activeGallery = [];
        let activeIndex = 0;
        let activeTitle = '';

        function openLightbox(imagesJson, startIndex, title) {
            try {
                activeGallery = (typeof imagesJson === 'string') ? JSON.parse(imagesJson) : imagesJson;
            } catch(e) {
                activeGallery = [imagesJson];
            }
            if (!Array.isArray(activeGallery) || activeGallery.length === 0) {
                activeGallery = [imagesJson];
            }
            activeIndex = startIndex || 0;
            activeTitle = title || '';
            updateLightboxView();

            const modal = document.getElementById('lightboxModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function updateLightboxView() {
            if (activeGallery.length === 0) return;
            const currentImgUrl = activeGallery[activeIndex];
            const img = document.getElementById('lightboxImg');
            const backdropImg = document.getElementById('lightboxBackdropImg');
            const titleEl = document.getElementById('lightboxTitle');
            const counterEl = document.getElementById('lightboxCounter');

            img.src = currentImgUrl;
            if (backdropImg) {
                backdropImg.style.backgroundImage = `url('${currentImgUrl}')`;
            }
            titleEl.innerText = activeTitle;
            counterEl.innerText = `${activeIndex + 1} / ${activeGallery.length} Photos (Swipe or use Arrows)`;
        }

        function nextLightbox() {
            if (activeGallery.length === 0) return;
            activeIndex = (activeIndex + 1) % activeGallery.length;
            updateLightboxView();
        }

        function prevLightbox() {
            if (activeGallery.length === 0) return;
            activeIndex = (activeIndex - 1 + activeGallery.length) % activeGallery.length;
            updateLightboxView();
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Keyboard & Swipe support
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextLightbox();
            if (e.key === 'ArrowLeft') prevLightbox();
        });

        // Touch Swipe
        let touchStartX = 0;
        let touchEndX = 0;
        const lightboxContainer = document.getElementById('lightboxModal');
        if (lightboxContainer) {
            lightboxContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);
            lightboxContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 40) nextLightbox();
                if (touchEndX > touchStartX + 40) prevLightbox();
            }, false);
        }

        function showPreloader(title, subtitle) {
            const preloader = document.getElementById('global-preloader');
            if (preloader) {
                if (title) document.getElementById('preloader-title').innerText = title;
                if (subtitle) document.getElementById('preloader-subtitle').innerText = subtitle;
                preloader.classList.remove('hidden');
                preloader.classList.add('flex');
            }
        }

        function hidePreloader() {
            const preloader = document.getElementById('global-preloader');
            if (preloader) {
                preloader.classList.add('hidden');
                preloader.classList.remove('flex');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyLanguage(currentLang);
            hidePreloader();
        });
    </script>
    @stack('scripts')
</body>
</html>
