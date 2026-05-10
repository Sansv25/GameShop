<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GameShop | Premium AI-Powered Game Accounts</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo-square.png') }}">
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
        .glow-text-hero{background:#2563eb;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .glass{background:rgba(255,255,255,.04);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.08)}
        .glass-card{background:rgba(255,255,255,.03);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.06);transition:all .4s ease}
        .glass-card:hover{background:rgba(99,102,241,.08);border-color:rgba(99,102,241,.3);transform:translateY(-8px);box-shadow:0 20px 60px rgba(99,102,241,.2)}
        .ai-badge{background:#2563eb,rgba(52,211,153,.15));border:1px solid rgba(99,102,241,.3);animation:pulse-badge 2s ease-in-out infinite}
        @keyframes pulse-badge{0%,100%{box-shadow:0 0 0 0 rgba(99,102,241,.3)}50%{box-shadow:0 0 20px 4px rgba(99,102,241,.15)}}
        .mesh-bg{position:fixed;inset:0;z-index:0;background:none,transparent),none,transparent);pointer-events:none}
        .grid-pattern{position:fixed;inset:0;z-index:0;background-image:#2563eb 1px,transparent 1px),#2563eb 1px,transparent 1px);background-size:60px 60px;pointer-events:none}
        .particles{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none}
        .particle{position:absolute;border-radius:50%;animation:float linear infinite}
        @keyframes float{0%{transform:translateY(100vh) scale(0);opacity:0}10%{opacity:1}90%{opacity:1}100%{transform:translateY(-100px) scale(1);opacity:0}}
        .nav-link{position:relative;color:rgba(255,255,255,.6);transition:color .3s}
        .nav-link:hover{color:#fff}
        .nav-link::after{content:'';position:absolute;bottom:-4px;left:0;width:0;height:1px;background:#2563eb;transition:width .3s}
        .nav-link:hover::after{width:100%}
        .btn-primary{background:#2563eb;border:1px solid rgba(139,92,246,.3);color:#fff;transition:all .3s;position:relative;overflow:hidden}
        .btn-primary::before{content:'';position:absolute;inset:0;background:#2563eb;opacity:0;transition:opacity .3s}
        .btn-primary:hover::before{opacity:1}
        .btn-primary:hover{box-shadow:0 0 30px rgba(139,92,246,.4);transform:translateY(-2px)}
        .search-box{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#fff;transition:all .3s}
        .search-box:focus{background:rgba(255,255,255,.08);border-color:rgba(99,102,241,.5);box-shadow:0 0 0 4px rgba(99,102,241,.1);outline:none}
        .search-box::placeholder{color:rgba(255,255,255,.3)}
        .cat-btn{border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.5);background:rgba(255,255,255,.03);transition:all .3s}
        .cat-btn:hover,.cat-btn.active{background:rgba(99,102,241,.2);border-color:rgba(99,102,241,.4);color:#a78bfa;box-shadow:0 0 20px rgba(99,102,241,.15)}
        .price-tag{background:#2563eb,rgba(139,92,246,.15));border:1px solid rgba(99,102,241,.2)}
        .stat-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);transition:all .4s}
        .stat-card:hover{border-color:rgba(99,102,241,.3);background:rgba(99,102,241,.06)}
        footer{background:rgba(0,0,0,.6);border-top:1px solid rgba(255,255,255,.06);backdrop-filter:blur(20px)}
        .scrollbar-thin::-webkit-scrollbar{height:3px}
        .scrollbar-thin::-webkit-scrollbar-track{background:transparent}
        .scrollbar-thin::-webkit-scrollbar-thumb{background:rgba(99,102,241,.4);border-radius:10px}
        #hero-badge,#hero-h1,#hero-p,#hero-search,#hero-visual,.product-card,.stat-item,#navbar{opacity:0}
    </style>
</head>
<body x-data="{ unreadCount: 0, isLoading: true, activeCategory: 'Semua', searchQuery: '{{ request('search') }}' }"
      x-init="setTimeout(() => isLoading = false, 400);
               @auth @if(auth()->user()->role === 'admin')
               const fetchUnread = () => fetch('{{ route('api.unread-count') }}').then(r=>r.json()).then(d=>unreadCount=d.count);
               fetchUnread(); setInterval(fetchUnread,15000);
               @endif @endauth">

    <div class="mesh-bg"></div>
    <div class="grid-pattern"></div>
    <div class="particles" id="particles"></div>

    {{-- NAVBAR --}}
    <nav id="navbar" class="glass sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-4">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
    <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
</a>
                </div>
                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    <a href="#products" class="nav-link text-sm font-medium">Katalog</a>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.chat.index') }}" class="relative p-2 text-white/50 hover:text-white transition">
                                <i class="far fa-comment-dots text-xl"></i>
                                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 bg-red-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center" x-cloak></span>
                            </a>
                        @endif
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl glass hover:border-blue-500/30 transition group">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-[9px] font-black text-white">{{ auth()->user()->initials() }}</div>
                                <span class="text-sm font-semibold text-white/80">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-white/40 text-[10px]"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-52 glass rounded-2xl py-2 z-[60]">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-chart-line w-4"></i> Dashboard</a>
                                    <a href="{{ route('admin.accounts.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-gamepad-alt w-4"></i> Kelola Akun</a>
                                @else
                                    <a href="{{ route('wishlists.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-heart w-4"></i> Wishlist</a>
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-shopping-bag w-4"></i> Riwayat Pesanan</a>
                                @endif
                                <a href="{{ route('settings.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-cog w-4"></i> Pengaturan</a>
                                <div class="border-t border-white/5 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition font-bold uppercase text-[10px] tracking-widest"><i class="far fa-sign-out-alt w-4"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold relative z-10">Daftar Gratis</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white/70 hover:text-white p-2">
                        <i class="far fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="far fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition x-cloak class="sm:hidden glass border-t border-white/10 absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="#products" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-gamepad-alt w-5"></i> Katalog</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.chat.index') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-comment-dots w-5"></i> Chat <span class="bg-red-500 px-2 py-0.5 rounded-full text-[10px] ml-1" x-show="unreadCount > 0" x-text="unreadCount"></span></a>
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-chart-line w-5"></i> Dashboard</a>
                        <a href="{{ route('admin.accounts.index') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-gamepad-alt w-5"></i> Kelola Akun</a>
                    @else
                        <a href="{{ route('wishlists.index') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-heart w-5"></i> Wishlist</a>
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-shopping-bag w-5"></i> Riwayat Pesanan</a>
                    @endif
                    <a href="{{ route('settings.profile.edit') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-cog w-5"></i> Pengaturan</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">@csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-red-400 font-bold"><i class="far fa-sign-out-alt w-5"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-white/70 hover:text-white"><i class="far fa-sign-in-alt w-5"></i> Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-blue-500 font-bold"><i class="far fa-user-plus w-5"></i> Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <main class="relative z-10">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left space-y-8">
                    <div id="hero-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full ai-badge text-sm font-semibold text-blue-400">
                        <span class="flex h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
                        AI-Powered Marketplace · Live
                    </div>
                    <h1 id="hero-h1" class="text-5xl md:text-7xl font-black leading-[1.05] tracking-tight">
                        <span class="text-white">Temukan Akun</span><br>
                        <span class="glow-text-hero">Game Impianmu</span>
                    </h1>
                    <p id="hero-p" class="text-lg text-white/50 max-w-lg leading-relaxed">Platform jual beli akun game premium dengan keamanan berlapis dan <span class="text-blue-500 font-semibold">AI Intelligent Assistant</span> siap membantu 24/7.</p>
                    <form id="hero-search" action="{{ route('accounts.catalog') }}" method="GET" class="relative group max-w-xl w-full">
                        {{-- Glowing Backdrop --}}
                        <div class="absolute -inset-0.5 bg-blue-600 rounded-full blur opacity-20 group-focus-within:opacity-60 transition duration-500"></div>
                        
                        {{-- Search Container --}}
                        <div class="relative flex items-center p-1.5 bg-gray-900/60 backdrop-blur-2xl rounded-full border border-white/10 group-focus-within:border-blue-500/50 transition-all duration-300 shadow-2xl">
                            <div class="pl-6 pr-3 text-white/40 group-focus-within:text-blue-500 transition-colors duration-300 flex items-center justify-center">
                                <i class="far fa-search text-lg"></i>
                            </div>
                            
                            <input type="text" name="search" placeholder="Cari skin, tier, atau game favoritmu..." class="w-full bg-transparent border-none focus:ring-0 px-2 py-3 text-sm text-white placeholder-white/40 font-medium">
                            
                            <button type="submit" class="flex-shrink-0 flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:from-blue-500 hover:to-blue-500 text-white rounded-full text-sm font-bold transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.02] active:scale-[0.98]">
                                <span>Cari</span>
                                <i class="far fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="hero-visual" class="relative hidden lg:block">
                    <div class="relative z-10 grid grid-cols-2 gap-5">
                        <div class="space-y-5 pt-10">
                            <div class="glass-card rounded-3xl overflow-hidden aspect-[4/3]">
                                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=400&h=300&auto=format&fit=crop" class="w-full h-full object-cover opacity-80">
                            </div>
                            <div class="glass-card rounded-3xl p-6 glow-blue">
                                <div class="text-3xl font-black text-white">2.4k+</div>
                                <div class="text-xs font-bold text-blue-500 uppercase tracking-widest mt-1">Akun Terjual</div>
                                <div class="mt-3 h-1.5 rounded-full bg-white/5"><div class="h-full w-3/4 rounded-full bg-blue-600"></div></div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="glass-card rounded-3xl p-6 text-center glow-blue">
                                <div class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-2">Pilihan Terbaik</div>
                                <div class="text-3xl font-black text-white">Top Tier</div>
                                <div class="text-xs text-white/40 mt-2 font-medium italic">Akun Berkualitas Tinggi</div>
                            </div>
                            <div class="glass-card rounded-3xl overflow-hidden aspect-[4/3]">
                                <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=400&h=350&auto=format&fit=crop" class="w-full h-full object-cover opacity-80">
                            </div>
                        </div>
                    </div>
                    <div class="absolute -inset-10 rounded-full bg-blue-500/10 blur-3xl -z-10"></div>
                </div>
            </div>
        </section>

        {{-- CATEGORY FILTER --}}
        <section id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
            <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-thin">
                @foreach(['Semua', 'MLBB', 'Genshin Impact', 'Valorant', 'PUBG Mobile'] as $cat)
                    <button @click="activeCategory = '{{ $cat }}'; $nextTick(() => searchQuery = '{{ $cat === 'Semua' ? '' : $cat }}')"
                        class="cat-btn flex-shrink-0 px-5 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all"
                        :class="activeCategory === '{{ $cat }}' ? 'active' : ''">{{ $cat }}</button>
                @endforeach
            </div>
        </section>

        {{-- PRODUCTS GRID --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            @php
                $query = \App\Models\GameAccount::where('status', 'available');
                $totalAvailable = $query->count();
                $accounts = $query->latest()->take(4)->get();
            @endphp

            <div x-show="isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-cloak>
                @for($i=0;$i<8;$i++)
                <div class="animate-pulse glass-card rounded-3xl p-4">
                    <div class="aspect-[4/3] bg-white/5 rounded-2xl mb-4"></div>
                    <div class="h-4 bg-white/5 rounded-full w-3/4 mb-2"></div>
                    <div class="h-3 bg-white/5 rounded-full w-1/2"></div>
                </div>
                @endfor
            </div>

            <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
                <div class="col-span-full py-24 text-center">
                    <div class="text-6xl mb-4 text-white/20"><i class="fad fa-search"></i></div>
                    <p class="text-white/40 font-bold">Tidak ada akun ditemukan.</p>
                </div>
                @endforelse
            </div>

            @if($totalAvailable > 4)
            <div class="mt-16 text-center">
                <a href="{{ route('accounts.catalog') }}" class="btn-primary inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-sm font-black uppercase tracking-widest transition-all hover:gap-5">
                    Lihat Semua Akun
                    <i class="far fa-arrow-right"></i>
                </a>
            </div>
            @endif
        </section>

        {{-- TENTANG GAMESHOP & FITUR --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 pt-10 border-t border-white/5 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 scroll-anim">
                <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Masa Depan <span class="glow-text">Jual Beli Akun</span></h2>
                <p class="text-white/50 text-lg leading-relaxed">GameShop hadir sebagai solusi modern untuk para gamers. Kami mengamankan setiap transaksi dengan teknologi canggih dan menghadirkan pengalaman berbelanja akun game yang belum pernah Anda rasakan sebelumnya.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Fitur 1 --}}
                <div class="glass-card rounded-3xl p-8 scroll-anim">
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 border border-blue-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Keamanan Berlapis</h3>
                    <p class="text-white/40 text-sm leading-relaxed">Setiap akun telah diverifikasi. Kami menahan dana hingga Anda memastikan akun 100% aman dan sesuai deskripsi.</p>
                </div>

                {{-- Fitur 2 --}}
                <div class="glass-card rounded-3xl p-8 scroll-anim">
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 border border-blue-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Transaksi Instan</h3>
                    <p class="text-white/40 text-sm leading-relaxed">Sistem otomatis yang mengamankan kredensial akun. Dapatkan detail akun Anda dalam hitungan detik setelah pembayaran.</p>
                </div>

                {{-- Fitur 3 --}}
                <div class="glass-card rounded-3xl p-8 scroll-anim">
                    <div class="w-14 h-14 rounded-2xl bg-blue-400/10 flex items-center justify-center text-blue-400 mb-6 border border-blue-400/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Banyak Metode Bayar</h3>
                    <p class="text-white/40 text-sm leading-relaxed">Mendukung berbagai e-wallet populer seperti QRIS, GoPay, OVO, dan DANA untuk kemudahan Anda bertransaksi.</p>
                </div>
            </div>
        </section>

        {{-- AI SECTION --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-10 scroll-anim">
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 overflow-hidden relative border-blue-500/20">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass text-xs font-bold text-blue-500 uppercase tracking-widest border-blue-500/30">
                            <i class="fas fa-robot text-lg"></i> Meet Your AI Assistant
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">Bingung Cari <br><span class="glow-text">Akun Yang Pas?</span></h2>
                        <p class="text-white/50 text-lg leading-relaxed">Jangan khawatir! Asisten AI cerdas kami siap membantu Anda 24/7. Tanyakan rekomendasi, panduan keamanan, atau negosiasi harga secara langsung.</p>
                        <div class="pt-4 flex flex-wrap gap-4">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.chat.index') }}" class="btn-primary px-8 py-4 rounded-xl text-sm font-bold flex items-center gap-3">
                                        Lihat Pesan Masuk
                                        <i class="far fa-arrow-right"></i>
                                    </a>
                                @else
                                    <a href="{{ route('chat.show', \App\Models\User::where('role','admin')->value('id') ?? 1) }}" class="btn-primary px-8 py-4 rounded-xl text-sm font-bold flex items-center gap-3">
                                        Mulai Chat dengan AI
                                        <i class="far fa-arrow-right"></i>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-primary px-8 py-4 rounded-xl text-sm font-bold flex items-center gap-3">
                                    Login untuk Chat AI
                                    <i class="far fa-arrow-right"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                    
                    <div class="relative flex justify-center md:justify-end">
                        <div class="w-full max-w-sm glass rounded-2xl p-4 border border-white/10 shadow-2xl relative">
                            {{-- Decorative elements for chat UI mockup --}}
                            <div class="flex items-center gap-3 border-b border-white/5 pb-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-blue-600 p-0.5">
                                    <div class="w-full h-full bg-gray-900 rounded-full flex items-center justify-center text-lg"><i class="fas fa-robot text-blue-500 text-sm"></i></div>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white">GameShop AI</div>
                                    <div class="text-[10px] text-blue-400 font-medium flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Online</div>
                                </div>
                            </div>
                            
                            <div class="space-y-4 mb-4">
                                <div class="flex gap-3 justify-end">
                                    <div class="bg-blue-500/80 text-white text-xs p-3 rounded-2xl rounded-tr-sm max-w-[80%]">
                                        Halo min, ada akun MLBB dengan skin Legend Miya? Budget 500k nih.
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-6 h-6 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center text-[10px]"><i class="fas fa-robot text-white text-[10px]"></i></div>
                                    <div class="glass text-white/80 text-xs p-3 rounded-2xl rounded-tl-sm max-w-[80%] border-white/5">
                                        Tentu! Kami punya beberapa akun yang cocok. Ini salah satu rekomendasinya: Akun MLBB Mythic, Skin Legend Miya, harga pas di budget kamu! <i class="fas fa-sparkles text-amber-400"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <input type="text" disabled placeholder="Tanya AI sekarang..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs text-white/50">
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-500">
                                    <i class="fas fa-paper-plane text-sm"></i>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Floating elements --}}
                        <div class="absolute -top-6 -right-6 w-16 h-16 glass rounded-2xl border-white/10 flex items-center justify-center animate-pulse"><i class="fas fa-stars text-amber-400 text-2xl"></i></div>
                        <div class="absolute -bottom-8 -left-8 w-20 h-20 glass rounded-full border-white/10 flex items-center justify-center animate-pulse delay-150"><i class="fas fa-rocket-launch text-blue-500 text-3xl"></i></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="relative z-10 pt-16 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12 pb-12 border-b border-white/5">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 group mb-4">
                        <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
                        <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                    </div>
                    <p class="text-white/40 text-sm leading-relaxed max-w-sm">Platform jual beli akun game premium dengan perlindungan AI. Aman, cepat, dan terpercaya.</p>
                </div>
                <div>
                    <h4 class="text-white/30 font-bold text-[10px] uppercase tracking-widest mb-4">Metode Pembayaran</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['QRIS','GOPAY','OVO','DANA'] as $m)
                        <span class="px-3 py-1 glass rounded-lg text-[10px] font-bold text-white/40 uppercase">{{ $m }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
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
// Particles
(function(){
    const c=document.getElementById('particles');
    if(!c)return;
    for(let i=0;i<20;i++){
        const p=document.createElement('div');
        p.className='particle';
        const s=Math.random()*4+2;
        p.style.cssText=`width:${s}px;height:${s}px;left:${Math.random()*100}%;background:rgba(${Math.random()>0.5?'99,102,241':'139,92,246'},.4);animation-duration:${Math.random()*15+10}s;animation-delay:${Math.random()*10}s`;
        c.appendChild(p);
    }
})();

// GSAP Animations
document.addEventListener('DOMContentLoaded', function(){
    if(typeof gsap === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // Navbar
    gsap.to('#navbar',{opacity:1,y:0,duration:.8,ease:'power3.out',from:{y:-80}});

    // Hero sequence
    const tl = gsap.timeline({delay:.3});
    tl.to('#hero-badge',{opacity:1,y:0,duration:.7,ease:'power3.out',from:{y:30}})
      .to('#hero-h1',{opacity:1,y:0,duration:.8,ease:'power3.out',from:{y:40}},'<.2')
      .to('#hero-p',{opacity:1,y:0,duration:.6,ease:'power3.out',from:{y:30}},'<.2')
      .to('#hero-search',{opacity:1,y:0,duration:.6,ease:'power3.out',from:{y:20}},'<.15')
      .to('#hero-visual',{opacity:1,x:0,duration:1,ease:'power3.out',from:{x:60}},'<.3');

    // Product cards on scroll
    gsap.utils.toArray('.product-card').forEach((card,i) => {
        gsap.fromTo(card,
            {opacity:0,y:50},
            {opacity:1,y:0,duration:.7,ease:'power3.out',delay:i*0.08,
             scrollTrigger:{trigger:card,start:'top 90%',toggleActions:'play none none none'}}
        );
    });

    // Content sections on scroll
    gsap.utils.toArray('.scroll-anim').forEach((el) => {
        gsap.fromTo(el,
            {opacity:0,y:40},
            {opacity:1,y:0,duration:.8,ease:'power3.out',
             scrollTrigger:{trigger:el,start:'top 85%',toggleActions:'play none none none'}}
        );
    });
});
</script>
</body>
</html>
