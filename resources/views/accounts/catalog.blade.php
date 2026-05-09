<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Akun | GameShop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/aquawolf04/font-awesome-pro@5cd1511/css/all.css">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>tailwind.config={theme:{extend:{colors:{primary:'#2563eb'},fontFamily:{sans:['Inter','sans-serif']}}}}</script>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <style>
        *{box-sizing:border-box}
        [x-cloak]{display:none!important}
        body{background:#030712;color:#f1f5f9;font-family:'Inter',sans-serif}
        .glow-blue{box-shadow:0 0 60px rgba(99,102,241,.25),0 0 120px rgba(99,102,241,.1)}
        .glow-text{background:#2563eb;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .glass{background:rgba(255,255,255,.04);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.08)}
        .glass-card{background:rgba(255,255,255,.03);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.06);transition:all .4s ease}
        .glass-card:hover{background:rgba(99,102,241,.08);border-color:rgba(99,102,241,.3);transform:translateY(-8px);box-shadow:0 20px 60px rgba(99,102,241,.2)}
        .mesh-bg{position:fixed;inset:0;z-index:0;background:none,transparent),none,transparent);pointer-events:none}
        .grid-pattern{position:fixed;inset:0;z-index:0;background-image:#2563eb 1px,transparent 1px),#2563eb 1px,transparent 1px);background-size:60px 60px;pointer-events:none}
        .nav-link{position:relative;color:rgba(255,255,255,.6);transition:color .3s}
        .nav-link:hover{color:#fff}
        .btn-primary{background:#2563eb;border:1px solid rgba(139,92,246,.3);color:#fff;transition:all .3s;position:relative;overflow:hidden}
        .btn-primary::before{content:'';position:absolute;inset:0;background:#2563eb;opacity:0;transition:opacity .3s}
        .btn-primary:hover::before{opacity:1}
        .btn-primary:hover{box-shadow:0 0 30px rgba(139,92,246,.4);transform:translateY(-2px)}
        .search-box{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#fff;transition:all .3s}
        .search-box:focus{background:rgba(255,255,255,.08);border-color:rgba(99,102,241,.5);box-shadow:0 0 0 4px rgba(99,102,241,.1);outline:none}
        .price-tag{background:#2563eb,rgba(139,92,246,.15));border:1px solid rgba(99,102,241,.2)}
        footer{background:rgba(0,0,0,.6);border-top:1px solid rgba(255,255,255,.06);backdrop-filter:blur(20px)}
        #navbar,.catalog-title,.search-section,.product-card{opacity:0}
    </style>
</head>
<body x-data="{ mobileMenuOpen: false, unreadCount: 0 }" class="min-h-screen">
    <div class="mesh-bg"></div>
    <div class="grid-pattern"></div>

    {{-- NAVBAR --}}
    <nav id="navbar" class="glass sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-4">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
                        <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                    </a>
                </div>
                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    <a href="{{ route('home') }}" class="nav-link text-sm font-medium">Beranda</a>
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl glass hover:border-blue-500/30 transition">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-[9px] font-black text-white">{{ auth()->user()->initials() }}</div>
                                <span class="text-sm font-semibold text-white/80">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-white/40 text-[10px]"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-52 glass rounded-2xl py-2 z-[60]">
                                <a href="{{ route('wishlists.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-heart w-4"></i> Wishlist</a>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-shopping-bag w-4"></i> Riwayat</a>
                                <div class="border-t border-white/5 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition font-bold uppercase text-[10px] tracking-widest"><i class="far fa-sign-out-alt w-4"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold relative z-10">Daftar</a>
                    @endauth
                </div>
                
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white/70 hover:text-white p-2">
                        <i class="far fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="far fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition x-cloak class="sm:hidden glass border-t border-white/10 absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-white/70 hover:text-white">Beranda</a>
                @auth
                    <a href="{{ route('wishlists.index') }}" class="block px-3 py-2 text-white/70 hover:text-white">Wishlist</a>
                    <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-white/70 hover:text-white">Riwayat</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">@csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-red-400 font-bold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-white/70 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-blue-500 font-bold">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="relative z-10 pt-16 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- TITLE SECTION --}}
            <div class="catalog-title mb-12 text-center sm:text-left">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Semua <span class="glow-text">Akun Game</span></h1>
                <p class="text-white/50 max-w-2xl">Jelajahi koleksi akun game premium kami. Gunakan fitur pencarian untuk menemukan akun dengan spesifikasi impianmu.</p>
            </div>

            {{-- SEARCH SECTION --}}
            <div class="search-section mb-12">
                <form action="{{ route('accounts.catalog') }}" method="GET" class="relative group max-w-2xl mx-auto sm:mx-0">
                    {{-- Glowing Backdrop --}}
                    <div class="absolute -inset-0.5 bg-blue-600 rounded-full blur opacity-10 group-focus-within:opacity-50 transition duration-500"></div>
                    
                    {{-- Search Container --}}
                    <div class="relative flex items-center p-1.5 bg-gray-900/60 backdrop-blur-2xl rounded-full border border-white/10 group-focus-within:border-blue-500/50 transition-all duration-300 shadow-2xl">
                        <div class="pl-6 pr-3 text-white/40 group-focus-within:text-blue-500 transition-colors duration-300 flex items-center justify-center">
                            <i class="far fa-search text-lg"></i>
                        </div>
                        
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari skin, tier, atau game favoritmu..." class="w-full bg-transparent border-none focus:ring-0 px-2 py-3 text-sm text-white placeholder-white/40 font-medium">
                        
                        <button type="submit" class="flex-shrink-0 flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:from-blue-500 hover:to-blue-500 text-white rounded-full text-sm font-bold transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.02] active:scale-[0.98]">
                            <span>Cari</span>
                            <i class="far fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @forelse($accounts as $account)
                <div class="product-card glass-card rounded-3xl overflow-hidden group">
                    <a href="{{ route('accounts.show', $account) }}" class="block relative aspect-[4/3] overflow-hidden">
                        <img src="{{ Storage::url($account->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider glass text-blue-300">{{ $account->category }}</div>
                    </a>
                    <div class="p-5">
                        <h3 class="font-bold text-white line-clamp-1 mb-3 group-hover:text-blue-300 transition">{{ $account->title }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="price-tag px-3 py-1.5 rounded-xl">
                                <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest">Harga</p>
                                <p class="text-sm font-black text-blue-300">Rp {{ number_format($account->price, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('accounts.show', $account) }}" class="w-9 h-9 rounded-xl glass flex items-center justify-center text-white/40 hover:text-blue-500 hover:border-blue-500/30 transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center glass rounded-3xl">
                    <div class="text-6xl mb-6 text-white/10"><i class="fad fa-search"></i></div>
                    <h3 class="text-xl font-bold text-white mb-2">Tidak ada akun ditemukan</h3>
                    <p class="text-white/40">Coba kata kunci lain atau periksa kategori Anda.</p>
                    <a href="{{ route('accounts.catalog') }}" class="inline-block mt-8 text-blue-500 font-bold text-sm uppercase tracking-widest border-b border-blue-500/30 pb-1">Reset Pencarian</a>
                </div>
                @endforelse
            </div>

            <div class="mt-12 custom-pagination">
                {{ $accounts->links() }}
            </div>
        </div>
    </main>

    <footer class="mt-auto py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <div class="flex justify-between items-center text-[10px] font-bold text-white/20 uppercase tracking-widest">
                <span>&copy; {{ date('Y') }} GameShop Dev.</span>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white/50 transition">Instagram</a>
                    <a href="#" class="hover:text-white/50 transition">X / Twitter</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            if(typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            gsap.to('#navbar', {opacity:1, y:0, duration:.8, ease:'power3.out'});
            gsap.to('.catalog-title', {opacity:1, y:0, duration:.8, ease:'power3.out', delay:.2});
            gsap.to('.search-section', {opacity:1, y:0, duration:.8, ease:'power3.out', delay:.3});
            
            gsap.utils.toArray('.product-card').forEach((card, i) => {
                gsap.to(card, {
                    opacity: 1, 
                    y: 0, 
                    duration: .7, 
                    ease: 'power3.out', 
                    delay: .4 + (i * 0.05),
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 95%'
                    }
                });
            });
        });
    </script>
</body>
</html>
