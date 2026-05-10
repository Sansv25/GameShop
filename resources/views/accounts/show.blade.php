<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $gameAccount->title }} | {{ config('app.name', 'GameShop') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo-square.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>tailwind.config={theme:{extend:{colors:{primary:'#6366f1'},fontFamily:{sans:['Inter','sans-serif']}}}}</script>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        html, body { background: #030712; }
        body { color: #f1f5f9; font-family: 'Inter', sans-serif; }
        /* Mesh */
        .gs-mesh { position:fixed;inset:0;z-index:0;pointer-events:none;
            background: none,transparent),
                        none,transparent); }
        .gs-grid { position:fixed;inset:0;z-index:0;pointer-events:none;
            background-image:#2563eb 1px,transparent 1px),#2563eb 1px,transparent 1px);
            background-size:56px 56px; }
        /* Glass */
        .glass { background:rgba(255,255,255,.04);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.08); }
        .glass-dark { background:rgba(255,255,255,.03);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.06); }
        /* Swiper */
        .swiper-button-next, .swiper-button-prev { color:white!important;background:rgba(0,0,0,.4)!important;width:40px!important;height:40px!important;border-radius:50%!important;backdrop-filter:blur(8px)!important;border:1px solid rgba(255,255,255,.1)!important; }
        .swiper-button-next:after,.swiper-button-prev:after { font-size:14px!important;font-weight:bold; }
        .swiper-pagination-bullet { background:rgba(255,255,255,.3)!important; }
        .swiper-pagination-bullet-active { background:#6366f1!important; }
        /* Scrollbar */
        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:rgba(99,102,241,.3);border-radius:10px; }
        /* Logo glow */
        .logo-glow { background:#2563eb;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
        /* Gradient text */
        .glow-text { background:#2563eb;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
        /* Price badge */
        .price-badge { background:#2563eb,rgba(139,92,246,.15));border:1px solid rgba(99,102,241,.25); }
        /* Buttons */
        .btn-primary { background:#2563eb;border:1px solid rgba(139,92,246,.3);color:#fff;transition:all .3s;position:relative;overflow:hidden; }
        .btn-primary:hover { box-shadow:0 0 30px rgba(139,92,246,.4);transform:translateY(-2px); }
        .btn-secondary { background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.7);transition:all .3s; }
        .btn-secondary:hover { background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);color:#fff; }
        /* GSAP initial */
        #show-nav, #show-gallery, #show-detail { opacity:0; }
        /* Input dark */
        select, textarea { background:rgba(255,255,255,.05)!important;border:1px solid rgba(255,255,255,.1)!important;color:#f1f5f9!important;border-radius:.75rem; }
        select option { background:#0f172a; }
    </style>
</head>
<body>
    <div class="gs-mesh"></div>
    <div class="gs-grid"></div>

    <!-- Navbar -->
    <nav id="show-nav" class="glass sticky top-0 z-50" style="background:rgba(3,7,18,.8);border-bottom:1px solid rgba(255,255,255,.07);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
                    <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                </a>
                @auth
                <a href="{{ route('orders.index') }}" class="text-sm font-medium text-white/50 hover:text-white transition">Pesanan Saya</a>
                @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-white/50 hover:text-white transition">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14" x-data="{ lightbox: false, lightboxImg: '' }">
        <!-- Lightbox -->
        <div x-show="lightbox"
             x-data="{ isZoomed: false, x: 0, y: 0 }"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-2xl flex items-center justify-center p-4 md:p-10"
             @click="lightbox = false; isZoomed = false"
             @keydown.escape.window="lightbox = false; isZoomed = false"
             x-cloak>
            
            <div class="relative w-full h-full flex items-center justify-center overflow-hidden cursor-default" @click.stop>
                <img :src="lightboxImg" 
                     class="max-w-full max-h-full object-contain rounded-lg shadow-2xl transition-all duration-500 ease-out select-none"
                     :class="isZoomed ? 'scale-[2.5] cursor-zoom-out' : 'scale-100 cursor-zoom-in'"
                     :style="isZoomed ? `transform-origin: ${x}% ${y}%` : ''"
                     @mousemove="if(isZoomed) { x = ($event.offsetX / $event.target.offsetWidth) * 100; y = ($event.offsetY / $event.target.offsetHeight) * 100 }"
                     @click="isZoomed = !isZoomed; if(isZoomed) { x = ($event.offsetX / $event.target.offsetWidth) * 100; y = ($event.offsetY / $event.target.offsetHeight) * 100 }">
            </div>

            <button class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors z-[110]" @click="lightbox = false">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/90 text-sm font-semibold tracking-wide shadow-2xl">
                Click to <span x-text="isZoomed ? 'Zoom Out' : 'Zoom In'"></span> & Drag to Explore
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <!-- Left: Gallery -->
            <div id="show-gallery" class="space-y-5" x-data="{ activeIndex: 0 }">
                <div class="swiper detailSwiper rounded-3xl overflow-hidden shadow-2xl shadow-blue-500/20 aspect-[4/3]" style="background:rgba(15,23,42,1)">
                    <div class="swiper-wrapper">
                        @if($gameAccount->images && count($gameAccount->images) > 0)
                            @foreach($gameAccount->images as $image)
                            <div class="swiper-slide cursor-pointer group/slide" @click="lightbox = true; lightboxImg = '{{ Storage::url($image) }}'">
                                <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/slide:opacity-100 transition-opacity flex items-center justify-center">
                                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-full">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="swiper-slide cursor-pointer group/slide" @click="lightbox = true; lightboxImg = '{{ Storage::url($gameAccount->image_path) }}'">
                                <img src="{{ Storage::url($gameAccount->image_path) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/slide:opacity-100 transition-opacity flex items-center justify-center">
                                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-full">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                @if($gameAccount->images && count($gameAccount->images) > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($gameAccount->images as $index => $image)
                    <button @click="detailSwiper.slideTo({{ $index }}); activeIndex = {{ $index }}"
                            :class="activeIndex === {{ $index }} ? 'ring-2 ring-blue-500 scale-105' : 'opacity-50 hover:opacity-80'"
                            class="aspect-square rounded-xl overflow-hidden transition-all duration-300 focus:outline-none"
                            style="border:1px solid rgba(255,255,255,.08)">
                        <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Details -->
            <div id="show-detail" class="space-y-6 rounded-3xl p-7 md:p-9" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);backdrop-filter:blur(16px)">
                <div>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4" style="background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.25);color:#a78bfa">
                        {{ $gameAccount->category }}
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-white leading-tight mb-4">
                        {{ $gameAccount->title }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm" style="color:rgba(255,255,255,.4)">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $gameAccount->created_at->diffForHumans() }}
                        </div>
                        <div class="w-1 h-1 rounded-full" style="background:rgba(255,255,255,.15)"></div>
                        <div class="font-bold uppercase tracking-widest text-[10px] text-blue-400">{{ $gameAccount->status }}</div>
                    </div>
                </div>

                <div class="p-6 rounded-2xl price-badge">
                    <div class="text-[10px] uppercase font-bold tracking-widest mb-1" style="color:rgba(255,255,255,.4)">Harga</div>
                    <div class="text-4xl font-black text-white">Rp {{ number_format($gameAccount->price, 0, ',', '.') }}</div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="p-5 rounded-2xl flex items-center gap-4" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold tracking-widest" style="color:rgba(255,255,255,.35)">Keamanan</div>
                            <div class="text-sm font-bold text-white">100% Terverifikasi</div>
                        </div>
                    </div>
                    <div class="p-5 rounded-2xl flex items-center gap-4" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)">
                        <div class="w-12 h-12 rounded-xl bg-blue-400/20 flex items-center justify-center text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold tracking-widest" style="color:rgba(255,255,255,.35)">Proses</div>
                            <div class="text-sm font-bold text-white">Instan & Otomatis</div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Deskripsi
                    </h3>
                    <div class="leading-relaxed text-sm whitespace-pre-wrap p-5 rounded-2xl italic" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);color:rgba(255,255,255,.55)">
                        "{{ $gameAccount->description }}"
                    </div>
                </div>



                @if($gameAccount->accounts && count($gameAccount->accounts) > 0)
                <div class="space-y-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Bundle Contents ({{ count($gameAccount->accounts) }} Items)
                    </h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($gameAccount->accounts as $index => $acc)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 group">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-primary font-bold text-xs ring-4 ring-slate-100/50">
                                {{ $index + 1 }}
                            </div>
                            <div class="text-sm font-medium text-slate-700">Account Type / Game ID No. {{ $index + 1 }}</div>
                            <div class="ml-auto flex gap-1">
                                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if (session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-100 text-blue-400 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="pt-4 flex flex-col gap-3">
                    @auth
                        @if($gameAccount->status === 'available')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="game_account_id" value="{{ $gameAccount->id }}">
                                <button type="submit" class="btn-primary w-full inline-flex items-center justify-center gap-2 py-4 px-6 rounded-2xl font-black text-sm active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    BELI SEKARANG
                                </button>
                            </form>
                            <form action="{{ route('wishlists.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="game_account_id" value="{{ $gameAccount->id }}">
                                <button type="submit" class="btn-secondary w-full inline-flex items-center justify-center gap-2 py-4 px-6 rounded-2xl font-bold text-sm active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    WISHLIST
                                </button>
                            </form>
                        </div>
                        <a href="{{ route('chat.show', \App\Models\User::where('role','admin')->first()??1) }}?account_id={{ $gameAccount->id }}"
                           class="btn-secondary w-full inline-flex items-center justify-center gap-2 py-4 px-6 rounded-2xl font-bold text-sm active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            TANYA ADMIN
                        </a>
                        @else
                        <div class="py-4 px-6 rounded-2xl text-center font-black uppercase tracking-widest text-sm cursor-not-allowed" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.25)">
                            SUDAH TERJUAL
                        </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center justify-center gap-2 py-4 px-8 rounded-2xl font-bold text-sm">Login untuk Membeli</a>
                    @endauth
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-8 mt-8" style="background:rgba(0,0,0,.5);border-top:1px solid rgba(255,255,255,.05);backdrop-filter:blur(20px)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center gap-2 group mb-2">
                <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-8 transition-transform group-hover:scale-105">
                <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
            </div>
            <div class="text-[10px] uppercase tracking-widest font-bold" style="color:rgba(255,255,255,.2)">&copy; {{ date('Y') }} GameShop Dev.</div>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script>
        var detailSwiper = new Swiper(".detailSwiper", {
            spaceBetween: 0,
            loop: false,
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            on: {
                slideChange: function () {
                    const container = document.querySelector('[x-data^="{ activeIndex"]');
                    if (container && container.__x) {
                        container.__x.$data.activeIndex = this.activeIndex;
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            gsap.registerPlugin(ScrollTrigger);

            // Navbar elastic drop
            gsap.fromTo('#show-nav',
                { y: -70, opacity: 0 },
                { y: 0, opacity: 1, duration: 1, ease: 'elastic.out(1, 0.6)' }
            );

            // Gallery slides in from left with spring
            gsap.fromTo('#show-gallery',
                { x: -80, opacity: 0, rotationY: 15 },
                { x: 0, opacity: 1, rotationY: 0, duration: 1, ease: 'power3.out', delay: 0.3, transformPerspective: 1000 }
            );

            // Detail panel slides in from right
            gsap.fromTo('#show-detail',
                { x: 80, opacity: 0 },
                { x: 0, opacity: 1, duration: 1, ease: 'power3.out', delay: 0.4 }
            );

            // Children of detail panel stagger in
            gsap.fromTo('#show-detail > *',
                { y: 25, opacity: 0 },
                { y: 0, opacity: 1, stagger: 0.1, duration: 0.6, ease: 'power2.out', delay: 0.7 }
            );

            // Magnetic effect on buttons
            document.querySelectorAll('.btn-primary, .btn-secondary').forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const r = btn.getBoundingClientRect();
                    const x = (e.clientX - r.left - r.width/2) * 0.25;
                    const y = (e.clientY - r.top - r.height/2) * 0.25;
                    gsap.to(btn, { x, y, duration: 0.3, ease: 'power2.out' });
                });
                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn, { x: 0, y: 0, duration: 0.6, ease: 'elastic.out(1, 0.5)' });
                });
            });

            // Scroll reveal for reviews
            gsap.utils.toArray('.rounded-xl.p-4, .rounded-2xl.p-5').forEach((el, i) => {
                gsap.fromTo(el,
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out', delay: i * 0.08,
                      scrollTrigger: { trigger: el, start: 'top 92%', toggleActions: 'play none none none' } }
                );
            });

        });
    </script>
</body>
</html>
