<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/aquawolf04/font-awesome-pro@5cd1511/css/all.css">
        <style>
            .glow-text{background:#2563eb;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
            .glass{background:rgba(255,255,255,.04);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.08)}
            .nav-link{position:relative;color:rgba(255,255,255,.6);transition:color .3s}
            .nav-link:hover{color:#fff}
            .nav-link::after{content:'';position:absolute;bottom:-4px;left:0;width:0;height:1px;background:#2563eb;transition:width .3s}
            .nav-link:hover::after{width:100%}
            .btn-primary{background:#2563eb;border:1px solid rgba(139,92,246,.3);color:#fff;transition:all .3s;position:relative;overflow:hidden}
            .btn-primary::before{content:'';position:absolute;inset:0;background:#2563eb;opacity:0;transition:opacity .3s}
            .btn-primary:hover::before{opacity:1}
            .btn-primary:hover{box-shadow:0 0 30px rgba(139,92,246,.4);transform:translateY(-2px)}
        </style>
        @stack('styles')
    </head>
    <body class="layout min-h-screen bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
        {{-- TOP NAVBAR --}}
        <nav class="[grid-area:header] glass sticky top-0 z-50 border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-18 py-4">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 transition-transform group-hover:scale-105">
    <span class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
</a>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:space-x-6">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.chat.index') }}" class="relative p-2 text-white/50 hover:text-white transition">
                                <i class="far fa-comment-dots text-xl"></i>
                            </a>
                        @endif
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl glass hover:border-blue-500/30 transition group">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-[9px] font-black text-white">{{ auth()->user()->initials() }}</div>
                                <span class="text-sm font-semibold text-white/80">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-white/40 text-[10px]"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-52 glass rounded-2xl py-2 z-[60]">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="fal fa-home w-4"></i> Dashboard</a>
                                <a href="{{ route('wishlists.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-heart w-4"></i> Wishlist</a>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-shopping-bag w-4"></i> Riwayat Pesanan</a>
                                <a href="{{ route('settings.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-cog w-4"></i> Pengaturan</a>
                                <div class="border-t border-white/5 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition font-bold uppercase text-[10px] tracking-widest"><i class="far fa-sign-out-alt w-4"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile right-side controls -->
                    <div class="flex items-center gap-2 sm:hidden">
                        <!-- Mobile user-menu (avatar dropdown) -->
                        <div x-data="{ mopen: false }" class="relative">
                            <button @click="mopen = !mopen" class="flex items-center gap-1.5 px-2 py-1.5 rounded-xl glass transition">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-[9px] font-black text-white">{{ auth()->user()->initials() }}</div>
                                <i class="fas fa-chevron-down text-white/40 text-[9px]"></i>
                            </button>
                            <div x-show="mopen" @click.away="mopen = false" x-transition x-cloak
                                 class="absolute right-0 mt-2 w-52 glass rounded-2xl py-2 z-[60]">
                                <div class="px-4 py-2 text-xs font-bold text-white/40 uppercase tracking-widest">{{ Auth::user()->name }}</div>
                                <div class="border-t border-white/5 my-1"></div>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-comment-dots w-4"></i> Messages</a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="fal fa-home w-4"></i> Dashboard</a>
                                <a href="{{ route('settings.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/5 transition"><i class="far fa-cog w-4"></i> Pengaturan</a>
                                <div class="border-t border-white/5 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition font-bold uppercase text-[10px] tracking-widest"><i class="far fa-sign-out-alt w-4"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                        <!-- Sidebar toggle -->
                        <button onclick="document.getElementById('sidebarToggle').click()" class="text-white/70 hover:text-white p-2">
                            <i class="far fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <x-sidebar sticky stashable class="border-r border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
            <x-sidebar.toggle id="sidebarToggle" class="lg:hidden w-10 p-0">
                <x-phosphor-x aria-hidden="true" width="20" height="20" />
            </x-sidebar.toggle>



            <x-navlist>
                <x-navlist.group :heading="__('Platform')">
                    <x-navlist.item before="phosphor-house-line" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-navlist.item>
                    <x-navlist.item before="phosphor-heart" :href="route('wishlists.index')" :current="request()->routeIs('wishlists.*')">
                        {{ __('Wishlist') }}
                    </x-navlist.item>
                    <x-navlist.item before="phosphor-shopping-cart-simple" :href="route('orders.index')" :current="request()->routeIs('orders.*')">
                        {{ __('Riwayat Pesanan') }}
                    </x-navlist.item>
                </x-navlist.group>

                @if(auth()->user()->role === 'admin')
                    <x-navlist.group :heading="__('Admin')" x-data="{ sidebarUnreadCount: 0 }" x-init="
                        const fetchSidebarUnread = () => {
                            fetch('{{ route('api.unread-count') }}')
                                .then(res => res.json())
                                .then(data => sidebarUnreadCount = data.count)
                                .catch(err => console.error('Error fetching unread count:', err));
                        };
                        fetchSidebarUnread();
                        setInterval(fetchSidebarUnread, 10000); /* Poll every 10 seconds */
                    ">
                        <x-navlist.item before="phosphor-users" :href="route('admin.accounts.index')" :current="request()->routeIs('admin.accounts.*')">
                            {{ __('Accounts') }}
                        </x-navlist.item>
                        <x-navlist.item before="phosphor-chat-circle-text" :href="route('admin.chat.index')" :current="request()->routeIs('admin.chat.*')">
                            {{ __('Messages') }}
                            <x-slot:after>
                                <span x-show="sidebarUnreadCount > 0" x-text="sidebarUnreadCount" class="ml-auto bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center shrink-0" x-cloak></span>
                            </x-slot:after>
                        </x-navlist.item>
                        <x-navlist.item before="phosphor-bell-ringing" :href="route('admin.broadcast.create')" :current="request()->routeIs('admin.broadcast.*')">
                            {{ __('Broadcast') }}
                        </x-navlist.item>
                        <x-navlist.item before="phosphor-sparkle" :href="route('admin.settings.ai.edit')" :current="request()->routeIs('admin.settings.ai.*')">
                            {{ __('AI Configuration') }}
                        </x-navlist.item>
                    </x-navlist.group>
                @endif
            </x-navlist>

            <x-spacer />

            <x-navlist>
                <x-navlist.item before="phosphor-git-pull-request" href="https://github.com/imacrayon/blade-starter-kit" target="_blank">
                {{ __('Repository') }}
                </x-navlist.item>

                <x-navlist.item before="phosphor-book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </x-navlist.item>
            </x-navlist>

            <x-popover align="bottom" justify="left">
                <button type="button" class="w-full group flex items-center rounded-lg p-1 hover:bg-gray-800/5 dark:hover:bg-white/10">
                    <span class="shrink-0 size-8 bg-gray-200 rounded-sm overflow-hidden dark:bg-gray-700">
                        <span class="w-full h-full flex items-center justify-center text-sm">
                            {{ auth()->user()->initials() }}
                        </span>
                    </span>
                    <span class="ml-2 text-sm text-gray-500 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white font-medium truncate">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                        <x-phosphor-caret-up-down aria-hidden="true" width="16" height="16" class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white" />
                    </span>
                </button>
                <x-slot:menu class="w-max">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-gray-200 text-black dark:bg-gray-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-left text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <x-popover.separator />
                    <x-popover.item before="phosphor-gear-fine" href="/settings/profile">{{ __('Settings') }}</x-popover.item>
                    <x-popover.separator />
                    <x-form method="post" action="{{ route('logout') }}" class="w-full flex">
                        <x-popover.item before="phosphor-sign-out">{{ __('Log Out') }}</x-popover.item>
                    </x-form>
                </x-slot:menu>
            </x-popover>
        </x-sidebar>


        {{ $slot }}

        @stack('scripts')
    </body>
</html>
