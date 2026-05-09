<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    @stack('styles')
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { background: #030712; }
        body {
            min-height: 100vh;
            background: #030712;
            color: #f1f5f9;
            font-family: 'Inter', 'Instrument Sans', sans-serif;
            display: flex;
            flex-direction: column;
        }
        /* Mesh gradient */
        .gs-mesh {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                none, transparent),
                none, transparent);
        }
        .gs-grid {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                #2563eb 1px, transparent 1px),
                #2563eb 1px, transparent 1px);
            background-size: 56px 56px;
        }
        /* Glass utility */
        .glass {
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.08);
        }
        /* Navbar */
        #gs-navbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(3,7,18,.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .nav-link {
            position: relative;
            color: rgba(255,255,255,.55);
            font-size: .875rem;
            font-weight: 500;
            padding: .5rem .75rem;
            border-radius: .75rem;
            transition: color .25s, background .25s;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.05); }
        .nav-link.active {
            color: #a78bfa;
            background: rgba(167,139,250,.1);
        }
        .nav-link.active::after {
            content: '';
            position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 60%; height: 2px;
            background: #2563eb;
            border-radius: 9999px;
        }
        /* Logo glow text */
        .logo-glow {
            background: #2563eb;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        /* Avatar gradient */
        .gs-avatar {
            background: #2563eb;
        }
        /* Main content */
        main.gs-main {
            flex: 1;
            position: relative;
            z-index: 10;
        }
        /* Footer */
        footer.gs-footer {
            position: relative; z-index: 10;
            background: rgba(0,0,0,.5);
            border-top: 1px solid rgba(255,255,255,.05);
            backdrop-filter: blur(20px);
        }
        /* Page header strip */
        .gs-page-header {
            background: #2563eb, rgba(139,92,246,.08));
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        /* SweetAlert2 dark override */
        .swal2-popup { background: #0f172a !important; color: #f1f5f9 !important; border: 1px solid rgba(255,255,255,.1) !important; }
        .swal2-title { color: #fff !important; }
        .swal2-html-container { color: rgba(255,255,255,.6) !important; }
        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,.5); }
        /* Popover menu dark */
        [x-data*="popover"] [role="menu"],
        [data-popover-menu] {
            background: rgba(15,23,42,.95) !important;
            border: 1px solid rgba(255,255,255,.1) !important;
            backdrop-filter: blur(20px) !important;
        }
        /* Table dark */
        table { border-collapse: separate; border-spacing: 0; }
        /* Input / select / textarea dark */
        input:not([type=submit]):not([type=button]):not([type=checkbox]):not([type=radio]),
        select, textarea {
            background: rgba(255,255,255,.05) !important;
            border-color: rgba(255,255,255,.1) !important;
            color: #f1f5f9 !important;
        }
        input::placeholder, textarea::placeholder { color: rgba(255,255,255,.3) !important; }
        input:focus, select:focus, textarea:focus {
            border-color: rgba(99,102,241,.5) !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12) !important;
            outline: none !important;
        }
        /* Cards generic */
        .gs-card {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.07);
            backdrop-filter: blur(12px);
            transition: border-color .3s, box-shadow .3s;
        }
        .gs-card:hover {
            border-color: rgba(99,102,241,.25);
            box-shadow: 0 0 30px rgba(99,102,241,.1);
        }
        /* GSAP initial states */
        #gs-navbar, .gsap-fade-up, .gsap-fade-right, .gsap-scale-in { opacity: 0; }
        /* Mobile menu */
        #gs-mobile-menu { display: none; }
        #gs-mobile-menu.open { display: block; }
        /* Magnetic btn */
        .gs-btn-primary {
            display: inline-flex; align-items: center; justify-content: center;
            background: #2563eb;
            border: 1px solid rgba(139,92,246,.3);
            color: #fff; font-weight: 700;
            border-radius: .75rem;
            transition: box-shadow .3s, transform .2s;
            position: relative; overflow: hidden;
        }
        .gs-btn-primary::before {
            content:''; position:absolute; inset:0;
            background: #2563eb;
            opacity:0; transition: opacity .3s;
        }
        .gs-btn-primary:hover::before { opacity:1; }
        .gs-btn-primary:hover { box-shadow: 0 0 30px rgba(139,92,246,.35); transform: translateY(-1px); }
        /* Particle canvas */
        #gs-particles { position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden; }
        .gs-particle {
            position:absolute; border-radius:50%;
            animation: gs-float linear infinite;
        }
        @keyframes gs-float {
            0% { transform: translateY(100vh) scale(0); opacity:0; }
            10% { opacity:.8; }
            90% { opacity:.8; }
            100% { transform: translateY(-100px) scale(1); opacity:0; }
        }
    </style>
</head>
<body>
    <div class="gs-mesh"></div>
    <div class="gs-grid"></div>
    <div id="gs-particles"></div>

    {{-- NAVBAR --}}
    <header id="gs-navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
    <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
</a>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('wishlists.index') }}" class="nav-link {{ request()->routeIs('wishlists.*') ? 'active' : '' }}">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Wishlist
                    </a>
                    <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Pesanan
                    </a>
                    <a href="{{ route('settings.profile.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pengaturan
                    </a>
                </nav>

                {{-- User Menu --}}
                <div class="flex items-center gap-3">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.chat.index') }}" class="relative p-2 rounded-xl text-white/50 hover:text-white hover:bg-white/5 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </a>
                    @endif
                    <x-popover align="top" justify="right">
                        <button type="button" class="flex items-center gap-2 px-3 py-2 rounded-xl glass hover:border-blue-500/30 transition">
                            <div class="w-7 h-7 gs-avatar rounded-lg flex items-center justify-center text-[10px] font-black text-white">
                                {{ auth()->user()->initials() }}
                            </div>
                            <span class="hidden md:block text-sm font-semibold text-white/70">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <x-slot:menu class="w-56 !bg-slate-900/95 !border-white/10">
                            <div class="px-4 py-3 border-b border-white/5">
                                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-white/40 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('settings.profile.edit') }}" class="block px-4 py-2.5 text-sm text-white/60 hover:text-white hover:bg-white/5 transition">Profile Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition font-bold text-[11px] uppercase tracking-widest">Sign Out</button>
                                </form>
                            </div>
                        </x-slot:menu>
                    </x-popover>

                    {{-- Mobile Hamburger --}}
                    <button onclick="document.getElementById('gs-mobile-menu').classList.toggle('open')" class="md:hidden p-2 rounded-xl text-white/50 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Nav --}}
            <div id="gs-mobile-menu" class="md:hidden pb-4 border-t border-white/5 mt-2 pt-3 space-y-1">
                <a href="{{ route('home') }}" class="block nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('wishlists.index') }}" class="block nav-link {{ request()->routeIs('wishlists.*') ? 'active' : '' }}">Wishlist</a>
                <a href="{{ route('orders.index') }}" class="block nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Pesanan</a>
                <a href="{{ route('settings.profile.edit') }}" class="block nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">Pengaturan</a>
            </div>
        </div>
    </header>

    {{-- Page Header --}}
    @isset($pageTitle)
    <div class="gs-page-header gsap-fade-up relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl md:text-3xl font-black text-white">{{ $pageTitle }}</h1>
            @isset($pageSubtitle)
                <p class="mt-1.5 text-white/40 text-sm">{{ $pageSubtitle }}</p>
            @endisset
        </div>
    </div>
    @endisset

    {{-- Main --}}
    <main class="gs-main flex-grow">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    <footer class="gs-footer py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-3">
            <div class="flex items-center gap-2 group mb-2 md:mb-0">
                <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
                <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
            </div>
            <p class="text-xs font-bold text-white/20 uppercase tracking-widest">© {{ date('Y') }} GameShop Dev. All rights reserved.</p>
            <div class="flex gap-4 text-xs font-bold text-white/20 uppercase tracking-widest">
                <a href="#" class="hover:text-white/50 transition">Instagram</a>
                <a href="#" class="hover:text-white/50 transition">X / Twitter</a>
            </div>
        </div>
    </footer>

    {{-- SweetAlert (dark theme) --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                background: '#0f172a',
                color: '#f1f5f9',
                iconColor: '#34d399',
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'OK!',
                customClass: { popup: 'rounded-3xl border border-white/10' }
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                background: '#0f172a',
                color: '#f1f5f9',
                iconColor: '#f87171',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Okay',
                customClass: { popup: 'rounded-3xl border border-white/10' }
            });
        });
    </script>
    @endif

    {{-- GSAP Animations --}}
    <script>
    (function() {
        // Particles
        const pc = document.getElementById('gs-particles');
        if (pc) {
            for (let i = 0; i < 15; i++) {
                const p = document.createElement('div');
                p.className = 'gs-particle';
                const s = Math.random() * 3 + 1.5;
                const colors = ['rgba(99,102,241,.5)', 'rgba(139,92,246,.4)', 'rgba(52,211,153,.3)', 'rgba(96,165,250,.4)'];
                p.style.cssText = `width:${s}px;height:${s}px;left:${Math.random()*100}%;background:${colors[Math.floor(Math.random()*colors.length)]};animation-duration:${Math.random()*20+12}s;animation-delay:${Math.random()*12}s`;
                pc.appendChild(p);
            }
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined') return;
        gsap.registerPlugin(ScrollTrigger);

        // Navbar slide down with elastic bounce
        gsap.fromTo('#gs-navbar',
            { y: -70, opacity: 0 },
            { y: 0, opacity: 1, duration: 1, ease: 'elastic.out(1, 0.6)', delay: 0.1 }
        );

        // Logo pulse on load
        gsap.fromTo('#gs-logo',
            { scale: 0.8, opacity: 0 },
            { scale: 1, opacity: 1, duration: 0.7, ease: 'back.out(2)', delay: 0.4 }
        );

        // Page header
        gsap.utils.toArray('.gsap-fade-up').forEach((el, i) => {
            gsap.fromTo(el,
                { y: 40, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.7, ease: 'power3.out', delay: 0.3 + i * 0.1 }
            );
        });

        // All cards with stagger scroll trigger
        gsap.utils.toArray('.gs-card').forEach((card, i) => {
            gsap.fromTo(card,
                { y: 50, opacity: 0, scale: 0.97 },
                {
                    y: 0, opacity: 1, scale: 1, duration: 0.65, ease: 'power3.out',
                    delay: i * 0.07,
                    scrollTrigger: { trigger: card, start: 'top 90%', toggleActions: 'play none none none' }
                }
            );
        });

        // Table rows stagger
        gsap.utils.toArray('tbody tr').forEach((row, i) => {
            gsap.fromTo(row,
                { x: -30, opacity: 0 },
                {
                    x: 0, opacity: 1, duration: 0.5, ease: 'power2.out',
                    delay: 0.4 + i * 0.06,
                    scrollTrigger: { trigger: row, start: 'top 95%', toggleActions: 'play none none none' }
                }
            );
        });

        // Magnetic effect on primary buttons
        document.querySelectorAll('.gs-btn-primary').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const r = btn.getBoundingClientRect();
                const x = e.clientX - r.left - r.width / 2;
                const y = e.clientY - r.top - r.height / 2;
                gsap.to(btn, { x: x * 0.2, y: y * 0.2, duration: 0.3, ease: 'power2.out' });
            });
            btn.addEventListener('mouseleave', () => {
                gsap.to(btn, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.5)' });
            });
        });

        // Page content fade in
        gsap.fromTo('main.gs-main > div > *:first-child',
            { y: 30, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.7, ease: 'power3.out', delay: 0.5 }
        );
    });
    </script>

    @stack('scripts')
</body>
</html>