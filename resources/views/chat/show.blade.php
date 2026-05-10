<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token for file upload if needed --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Chat - {{ config('app.name', 'GameShop') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo-square.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: '#0ea5e9', 
                        },
                         fontFamily: {
                            sans: ['Instrument Sans', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        [x-cloak] { display: none !important; }
        /* FilePond Custom Styling for Chat */
        .filepond--root {
            margin-bottom: 0;
            font-family: inherit;
        }
        .filepond--panel-root {
            background-color: transparent;
        }
        .chat-filepond .filepond--list-scroller {
            margin-top: 0;
        }
        .chat-filepond .filepond--drop-label {
            display: none;
        }
        /* AI Typing dots animation */
        @keyframes typingDot {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-6px); opacity: 1; }
        }
        .typing-dot {
            animation: typingDot 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    </style>

</head>
<body class="bg-slate-50 dark:bg-slate-900 h-screen flex flex-col font-sans" 
      x-data="chatApp('{{ $user->hash_id }}')"
      @alpine:init="window.chatAppInstance = $data">
    
    <!-- Lightbox Modal -->
    <div x-show="zoomedImage" 
         x-data="{ isZoomed: false, x: 0, y: 0 }"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center p-4"
         @click="zoomedImage = null; isZoomed = false"
         @keydown.escape.window="zoomedImage = null; isZoomed = false"
         x-cloak>
        
        <div class="relative w-full h-full flex items-center justify-center overflow-hidden cursor-default" @click.stop>
            <img :src="zoomedImage" 
                 class="max-w-full max-h-full rounded-lg shadow-2xl transition-all duration-500 ease-out select-none"
                 :class="isZoomed ? 'scale-[2.5] cursor-zoom-out' : 'scale-100 cursor-zoom-in'"
                 :style="isZoomed ? `transform-origin: ${x}% ${y}%` : ''"
                 @mousemove="if(isZoomed) { x = ($event.offsetX / $event.target.offsetWidth) * 100; y = ($event.offsetY / $event.target.offsetHeight) * 100 }"
                 @click="isZoomed = !isZoomed; if(isZoomed) { x = ($event.offsetX / $event.target.offsetWidth) * 100; y = ($event.offsetY / $event.target.offsetHeight) * 100 }">
        </div>

        <button class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors z-[10000]" @click="zoomedImage = null">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white/80 text-xs font-medium tracking-wide">
            Click image to <span x-text="isZoomed ? 'zoom out' : 'zoom in'"></span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 h-16 flex-none flex items-center justify-between px-4 sm:px-6 z-10 relative">
        <div class="flex items-center">
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.chat.index') : route('home') }}" class="mr-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg"
                         :class="isBotHandling ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300'">
                        <template x-if="isBotHandling">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                        </template>
                        <template x-if="!isBotHandling">
                            <span>{{ substr($user->name, 0, 1) }}</span>
                        </template>
                    </div>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800 dark:text-white text-sm sm:text-base">
                        <span x-show="isBotHandling">AI Assistant</span>
                        <span x-show="!isBotHandling">{{ $user->name }}</span>
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        <template x-if="isBotHandling">
                            <span class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span>
                                AI Assistant aktif
                            </span>
                        </template>
                        <template x-if="!isBotHandling">
                            <span>{{ $user->role === 'admin' ? 'Admin Support' : 'Customer' }}</span>
                        </template>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right side: Toggle AI / Human -->
        @if(auth()->user()->role !== 'admin')
        <div class="flex items-center gap-2">
            <button @click="toggleChatMode()"
                    :disabled="isTogglingMode"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 border-2"
                    :class="isBotHandling 
                        ? 'border-amber-400 bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-500 dark:hover:bg-amber-900/50' 
                        : 'border-blue-400 bg-violet-50 text-violet-700 hover:bg-violet-100 dark:bg-violet-900/30 dark:text-blue-400 dark:border-violet-500 dark:hover:bg-violet-900/50'">
                <template x-if="isBotHandling">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Chat Admin
                    </span>
                </template>
                <template x-if="!isBotHandling">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        AI Assistant
                    </span>
                </template>
            </button>
        </div>
        @else
        <!-- Admin Price Offer Trigger Button -->
        <div class="flex items-center gap-2">
            <button @click="showPricePanel = !showPricePanel"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-300 border border-blue-200 bg-blue-50 text-emerald-700 hover:bg-blue-100 hover:border-emerald-300 hover:shadow-md hover:shadow-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-emerald-800 dark:hover:bg-emerald-900/40 dark:hover:shadow-emerald-900/20 active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
                <span>Price Offer</span>
            </button>
        </div>
        @endif
    </header>

    <!-- Discussing Account Banner (Fixed below header) -->
    <div x-show="activeAccount" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm border-b border-slate-200 dark:border-slate-700 w-full z-20 relative" 
         x-cloak>
        <div class="max-w-4xl mx-auto p-2 sm:px-6">
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-2 flex items-center justify-between group cursor-pointer" @click="isMinimized = !isMinimized">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="flex-none transition-all duration-300 overflow-hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" 
                         :class="isMinimized ? 'w-10 h-10 rounded-lg' : 'w-14 h-14 rounded-xl'">
                        <img :src="activeAccount?.image_url" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] uppercase tracking-wider font-bold text-blue-400">Discussing Account</span>
                            <span x-show="activeAccount?.has_offer" class="text-[9px] px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded font-bold uppercase animate-pulse">Offer Active</span>
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white truncate text-sm" x-text="activeAccount?.title"></h3>
                        <div x-show="!isMinimized" class="flex items-center gap-2 mt-0.5">
                            <span class="text-blue-400 dark:text-blue-400 font-bold text-xs" x-text="activeAccount?.formatted_price"></span>
                            <span x-show="activeAccount?.has_offer" class="text-[10px] text-slate-400 line-through" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activeAccount?.original_price || 0)"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if(auth()->user()->role !== 'admin')
                    <button @click.stop="proceedToCheckout()"
                            :disabled="isCheckingOut"
                            class="px-4 py-1.5 bg-blue-400 hover:bg-blue-400 text-white rounded-lg font-bold text-xs shadow-md transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2 min-w-[80px] justify-center">
                        <span x-show="!isCheckingOut" class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m10 0a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            BUY
                        </span>
                        <svg x-show="isCheckingOut" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                    </button>
                    @endif

                    <div class="p-1 text-slate-400 group-hover:text-blue-400 transition-colors">
                        <svg class="w-4 h-4 transition-transform duration-300" :class="isMinimized ? '' : 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Area -->
    <main class="flex-1 overflow-y-auto p-4 space-y-4 relative" id="messagesContainer">


        <!-- Price Offer Modal Overlay (Admin Only) -->
        @if(auth()->user()->role === 'admin')
        <div x-show="showPricePanel" 
             x-cloak
             class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
             @keydown.escape.window="showPricePanel = false">
            
            <!-- Backdrop -->
            <div x-show="showPricePanel"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
                 @click="showPricePanel = false"></div>

            <!-- Modal Card -->
            <div x-show="showPricePanel"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative w-full max-w-sm bg-white dark:bg-slate-800 rounded-2xl shadow-2xl shadow-slate-900/20 dark:shadow-black/40 overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="relative px-5 pt-5 pb-4">
                    <!-- Close Button -->
                    <button @click="showPricePanel = false" 
                            class="absolute top-4 right-4 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-slate-200 dark:hover:bg-slate-700 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Title -->
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-blue-400 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Price Offer</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Send special pricing to customer</p>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-100 dark:bg-slate-700"></div>

                <!-- Modal Body -->
                <div class="px-5 py-4 space-y-4 max-h-[60vh] overflow-y-auto">
                    
                    <!-- Account Card -->
                    <template x-if="activeAccount">
                        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-200 dark:bg-slate-600 flex-none">
                                <img :src="activeAccount?.image_url" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate" x-text="activeAccount?.title"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Original: <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="activeAccount?.formatted_price"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                    <template x-if="!activeAccount">
                        <div class="flex items-center gap-2.5 p-3 bg-red-50 dark:bg-red-900/15 rounded-xl border border-red-100 dark:border-red-900/30">
                            <svg class="w-4 h-4 text-red-400 flex-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <p class="text-sm text-red-600 dark:text-red-400">No account selected</p>
                        </div>
                    </template>

                    <!-- Offer Price -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                            Offer Price
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" 
                                   x-model.number="offerPrice" 
                                   placeholder="0"
                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 pl-9 pr-3 py-2.5 text-sm font-medium text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400 dark:focus:border-blue-400 transition-all">
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                            Message <span class="font-normal normal-case text-slate-400 dark:text-slate-500">(optional)</span>
                        </label>
                        <textarea x-model="offerMessage" 
                                  placeholder="e.g., Special discount for you!"
                                  rows="2"
                                  class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400 dark:focus:border-blue-400 transition-all resize-none"></textarea>
                    </div>

                    <!-- Valid For -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                            Valid For
                        </label>
                        <div class="grid grid-cols-3 gap-1.5">
                            <template x-for="opt in [{v:1,l:'1h'},{v:6,l:'6h'},{v:12,l:'12h'},{v:24,l:'24h'},{v:48,l:'48h'},{v:72,l:'72h'}]" :key="opt.v">
                                <button type="button"
                                        @click="validHours = opt.v"
                                        class="py-2 rounded-lg text-xs font-semibold transition-all duration-200 border"
                                        :class="validHours === opt.v 
                                            ? 'bg-blue-400 text-white border-blue-400 shadow-md shadow-blue-400/25' 
                                            : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:border-emerald-300 dark:hover:border-blue-400'"
                                        x-text="opt.l">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Savings Display -->
                    <template x-if="offerPrice && activeAccount && activeAccount.price > offerPrice">
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-emerald-800/40">
                            <div>
                                <p class="text-[11px] font-semibold text-blue-400/70 dark:text-blue-400/70 uppercase tracking-wide">Customer Saves</p>
                                <p class="text-lg font-bold text-blue-400 dark:text-blue-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(Math.floor(activeAccount.price - offerPrice))"></p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-emerald-800/40 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                </svg>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="px-5 pb-5 pt-2 flex gap-2.5">
                    <button @click="showPricePanel = false"
                            class="flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                        Cancel
                    </button>
                    <button @click="sendPriceOffer()"
                            :disabled="!activeAccount || !offerPrice || isSendingOffer"
                            class="flex-[2] py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-blue-400 hover:bg-blue-400 dark:bg-blue-400 dark:hover:bg-blue-400 shadow-lg shadow-blue-400/25 hover:shadow-blue-400/40 transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2">
                            <span x-show="!isSendingOffer" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                Send Offer
                            </span>
                            <span x-show="isSendingOffer" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <template x-for="(msg, index) in messages" :key="msg.id ? 'msg-' + msg.id : (msg.tempId ? 'temp-' + msg.tempId : 'idx-' + index)">
            <div class="w-full">
                <!-- Date Divider -->
                <div x-show="shouldShowDate(msg, index)" class="flex justify-center my-6">
                    <span class="px-3 py-1 bg-slate-200/50 dark:bg-slate-700/50 backdrop-blur-sm rounded-full text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest border border-slate-200/50 dark:border-slate-600/50" 
                          x-text="formatDateLabel(msg.created_at)">
                    </span>
                </div>

                <div class="flex w-full mb-4" :class="msg.sender_id == {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                    
                    <!-- AI Avatar (shown for bot messages, on the left side) -->
                    <template x-if="msg.sender_id != {{ auth()->id() }} && msg.is_auto_message">
                        <div class="flex-none mr-2 self-end">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shadow-md"
                                 :class="msg.is_error_message ? 'bg-red-500' : 'bg-blue-600'">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                </svg>
                            </div>
                        </div>
                    </template>

                    <div class="max-w-[75%] rounded-2xl px-4 py-2 shadow-sm group text-sm relative"
                         :class="msg.sender_id == {{ auth()->id() }} 
                            ? 'bg-blue-600 border border-blue-500 rounded-tr-none text-white' 
                            : (msg.is_error_message 
                                ? 'bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-tl-none text-red-800 dark:text-red-200'
                                : (msg.is_auto_message 
                                    ? 'bg-blue-700 dark:bg-blue-900/40 border border-blue-800 rounded-tl-none text-white'
                                    : 'bg-slate-800 dark:bg-slate-950 text-white border border-slate-700 dark:border-slate-800 rounded-tl-none'))">
                        
                        <!-- AI Label -->
                        <template x-if="msg.sender_id != {{ auth()->id() }} && msg.is_auto_message">
                            <div class="flex items-center gap-1 mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider"
                                      :class="msg.is_error_message ? 'text-red-500 dark:text-red-400' : 'text-blue-500 dark:text-blue-400'">
                                    ✨ <span x-text="msg.is_error_message ? 'AI System Error' : 'AI Assistant'"></span>
                                </span>
                            </div>
                        </template>

                        <!-- Image content -->
                        <template x-if="msg.image_path">
                            <div class="mb-1 rounded-lg overflow-hidden cursor-pointer">
                                <img :src="msg.fileProxy || '/storage/' + msg.image_path" 
                                     class="max-w-full h-auto object-cover hover:opacity-90 transition" 
                                     style="max-height: 300px;"
                                     @click="zoomedImage = msg.fileProxy || '/storage/' + msg.image_path">
                            </div>
                        </template>

                        <!-- Text content -->
                        <p x-html="formatMessage(msg.message)" class="leading-relaxed whitespace-pre-wrap mb-1" x-show="msg.message"></p>
                        
                        <!-- Price Offer Checkout Button -->
                        <template x-if="msg.is_price_offer && msg.account_id && msg.sender_id != {{ auth()->id() }}">
                            <div class="mt-2 mb-1">
                                <a :href="'{{ route('orders.create') }}?account_id=' + msg.account_hash_id" 
                                   class="inline-flex w-full items-center justify-center gap-2 py-2 px-3 bg-blue-400 hover:bg-blue-400 text-white rounded-lg text-xs font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Beli Akun Ini Sekarang
                                </a>
                            </div>
                        </template>
                                                <!-- Meta (Time & Status) -->
                        <div class="flex items-center justify-end gap-1 mt-0.5">
                            <span class="text-[10px] text-white/60"
                                  x-text="msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})">
                            </span>
 
                            <!-- Status Icons (Only for my messages) -->
                            <template x-if="msg.sender_id == {{ auth()->id() }}">
                                <span>
                                    <!-- Sending (Clock) -->
                                    <template x-if="msg.status === 'sending'">
                                        <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </template>
                                    <!-- Error (Exclamation) -->
                                    <template x-if="msg.status === 'error'">
                                        <div class="flex items-center gap-1 group/err relative cursor-pointer" @click="messages = messages.filter(m => m !== msg)">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="absolute bottom-full right-0 mb-1 hidden group-hover/err:block bg-red-500 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap shadow-lg z-50">
                                                <span x-text="msg.errorMessage || 'Upload failed'"></span>
                                                (Click to remove)
                                            </span>
                                        </div>
                                    </template>
                                    <!-- Sent (One Tick) -->
                                    <template x-if="!msg.status && !msg.is_read">
                                        <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </template>
                                    <!-- Read (Two Ticks) -->
                                    <template x-if="!msg.status && msg.is_read">
                                        <div class="flex -space-x-2">
                                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </template>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- AI Typing Indicator -->
        <div x-show="isAiTyping" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="flex items-end gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center shadow-md">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                </svg>
            </div>
            <div class="bg-blue-600 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl rounded-tl-none px-5 py-3 shadow-sm">
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 bg-blue-400 rounded-full typing-dot"></div>
                    <div class="w-2 h-2 bg-blue-400 rounded-full typing-dot"></div>
                    <div class="w-2 h-2 bg-blue-400 rounded-full typing-dot"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Input Area -->
    <footer class="bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 p-4 flex-none z-20 relative">
        <div class="max-w-4xl mx-auto">
            
            <!-- Canned Responses (Admin Only) -->
            @if(auth()->user()->role === 'admin' && count($cannedResponses) > 0)
            <div class="mb-3 flex flex-wrap gap-2 overflow-x-auto pb-2 scrollbar-none">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter self-center mr-1">Quick:</span>
                @foreach($cannedResponses as $cr)
                    <button @click="newMessage = '{{ addslashes($cr->content) }}'; $nextTick(() => sendMessage())" 
                            class="px-3 py-1 bg-slate-100 dark:bg-slate-700 hover:bg-primary/10 hover:text-primary dark:hover:text-primary rounded-full text-xs font-medium border border-transparent hover:border-primary/20 transition-all whitespace-nowrap">
                        {{ $cr->title }}
                    </button>
                @endforeach
            </div>
            @endif

            <!-- FilePond Container (Hidden by default, shown when file added) -->
            <div x-show="hasFile" class="mb-2 animate-in slide-in-from-bottom-2" x-cloak>
                <input type="file" x-ref="pondInput">
            </div>

            <form @submit.prevent="sendMessage" class="relative flex items-center gap-2">
                <button type="button" @click="triggerFileUpload" class="p-2 text-slate-400 hover:text-blue-400 transition rounded-full hover:bg-slate-50 dark:hover:bg-slate-700" :class="hasFile ? 'text-blue-400' : ''">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </button>

                <input 
                    type="text" 
                    x-model="newMessage"
                    placeholder="Type a message..." 
                    class="flex-1 rounded-full bg-slate-100 dark:bg-slate-900 border border-transparent focus:bg-white focus:border-blue-400 dark:focus:border-blue-400 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/20 transition text-slate-900 dark:text-white placeholder-slate-400"
                >
                
                <button type="submit" class="p-3 bg-blue-400 text-white rounded-full hover:bg-blue-400 transition shadow-lg shadow-blue-400/30 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95 flex items-center justify-center" :disabled="(!newMessage && !hasFile) || isAiTyping">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </footer>

    <!-- Toast -->
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         class="fixed bottom-24 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-2xl text-sm font-medium text-white max-w-xs text-center"
         :class="toast.type === 'success' ? 'bg-blue-600' : 'bg-blue-600'">
        <span x-text="toast.message"></span>
    </div>

    <script>
        function chatApp(userId) {
            return {
                messages: [],
                newMessage: '',
                pond: null,
                hasFile: false,
                pollingInterval: null,
                zoomedImage: null,
                isMinimized: false,
                isAiTyping: false,
                isCheckingOut: false,
                isBotHandling: @js($isBotHandling ?? false),
                isTogglingMode: false,
                showBanner: @js(($isBotHandling ?? false) || ($chatbotGloballyActive ?? false)),
                showHandoverForm: false,
                handoverReason: '',
                handoverLoading: false,
                showPricePanel: false,
                offerPrice: '',
                offerMessage: '',
                validHours: 24,
                isSendingOffer: false,
                toast: { show: false, message: '', type: 'success' },
                activeAccount: @js($activeAccountData),
                
                init() {
                    // Initialize FilePond (Plugins are pre-registered in app.js)
                    window.chatAppInstance = this;

                    this.pond = FilePond.create(this.$refs.pondInput, {
                        allowMultiple: false,
                        labelIdle: 'Drag & Drop images or <span class="filepond--label-action">Browse</span>',
                        credits: false,
                        instantUpload: true,
                        allowImageResize: true,
                        imageResizeTargetWidth: 2560,
                        imageResizeTargetHeight: 1440,
                        imageResizeMode: 'contain',
                        imageResizeUpscale: false,
                        allowImageTransform: true,
                        imageTransformAfterResize: true,
                        imageTransformOutputMimeType: 'image/jpeg',
                        imageTransformOutputQuality: 95,
                        server: {
                            process: {
                                url: '{{ route('upload') }}',
                                onload: (response) => {
                                    try {
                                        const res = JSON.parse(response);
                                        return res.id || response;
                                    } catch (e) {
                                        return response;
                                    }
                                }
                            },
                            revert: '{{ route('revert') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        },
                        onaddfile: () => { this.hasFile = true; },
                        onremovefile: () => { this.hasFile = false; }
                    });

                    // Watch for URL changes (in case user clicks another account)
                    window.addEventListener('popstate', () => this.handleUrlAccount());
                    this.handleUrlAccount();

                    this.fetchMessages();
                    this.pollingInterval = setInterval(() => {
                        this.fetchMessages();
                    }, 5000); // Increased to 5 seconds for better performance
                    
                    this.$watch('messages', (value) => {
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    });
                },

                triggerFileUpload() {
                    if (this.hasFile) {
                        this.pond.removeFile();
                    } else {
                        this.pond.browse();
                    }
                },

                handleUrlAccount() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const accountId = urlParams.get('account_id');
                    if (accountId) {
                        this.fetchMessages();
                    }
                },
                
                fetchMessages() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentUrlAccountId = urlParams.get('account_id');

                    fetch(`{{ url('/chat/messages') }}/${userId}${currentUrlAccountId ? '?account_id=' + currentUrlAccountId : ''}`)
                        .then(async res => {
                            if (!res.ok) {
                                const text = await res.text().catch(() => 'Unknown error');
                                throw new Error(text || `HTTP ${res.status}`);
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (!data) {
                                console.error('Invalid chat data:', data);
                                this.showToast('Gagal memuat pesan. Silakan refresh halaman.', 'error');
                                return;
                            }
                            let messages = data.messages;
                            if (!Array.isArray(messages) && messages && typeof messages === 'object') {
                                messages = Object.values(messages);
                            }

                            if (!Array.isArray(messages)) {
                                console.error('Invalid chat messages payload:', data);
                                return;
                            }

                            const newCount = messages.length;
                            const oldCount = this.messages.filter(m => m.id !== null).length;
                            
                            // Filter out duplicates based on ID (real messages from DB)
                            const seenIds = new Set();
                            const uniqueMessages = [];
                            
                            messages.forEach(m => {
                                if (m.id && seenIds.has(m.id)) return;
                                if (m.id) seenIds.add(m.id);
                                uniqueMessages.push(m);
                            });

                            // Preserve temporary messages that haven't been confirmed yet
                            this.messages.forEach(m => {
                                if (!m.id && m.tempId) {
                                    // Check if this temp message has already been confirmed by a real message
                                    const isConfirmed = uniqueMessages.some(um => um.tempId === m.tempId);
                                    if (!isConfirmed) {
                                        uniqueMessages.push(m);
                                    }
                                }
                            });

                            // Sort by created_at or tempId
                            uniqueMessages.sort((a, b) => {
                                const timeA = new Date(a.created_at || a.tempId).getTime();
                                const timeB = new Date(b.created_at || b.tempId).getTime();
                                return timeA - timeB;
                            });

                            this.messages = uniqueMessages;
                            this.isAiTyping = false;
                            
                            if (newCount > oldCount && oldCount > 0) {
                                const lastMsg = messages[messages.length - 1];
                                if (lastMsg.sender_id != {{ auth()->id() }}) {
                                    this.playNotificationSound();
                                }
                            }
                            
                            if (data.latest_account) {
                                const wasMinimized = this.isMinimized;
                                const isNewAccount = !this.activeAccount || this.activeAccount.id !== data.latest_account.id;
                                this.activeAccount = data.latest_account;
                                
                                if (isNewAccount) {
                                    this.isMinimized = false;
                                } else {
                                    this.isMinimized = wasMinimized;
                                }
                            } else {
                                this.activeAccount = null;
                            }

                            // Update bot handling state from server
                            if (data.is_bot_handling !== undefined) {
                                this.isBotHandling = data.is_bot_handling;
                            }
                        })
                        .catch(err => {
                            console.error('Fetch messages failed:', err);
                            this.showToast('Gagal memuat pesan. Silakan refresh halaman.', 'error');
                        });
                },

                playNotificationSound() {
                    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');
                    audio.volume = 0.5;
                    audio.play().catch(e => console.log('SFX blocked by browser policy'));
                },



                shouldShowDate(msg, index) {
                    if (index === 0) return true;
                    const prevMsg = this.messages[index - 1];
                    if (!prevMsg.created_at || !msg.created_at) return false;
                    
                    const currDate = new Date(msg.created_at).toDateString();
                    const prevDate = new Date(prevMsg.created_at).toDateString();
                    return currDate !== prevDate;
                },

                formatDateLabel(dateStr) {
                    const date = new Date(dateStr);
                    const today = new Date();
                    const yesterday = new Date();
                    yesterday.setDate(today.getDate() - 1);

                    if (date.toDateString() === today.toDateString()) return 'Hari Ini';
                    if (date.toDateString() === yesterday.toDateString()) return 'Kemarin';
                    
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                },
                
                sendMessage() {
                    if (!this.newMessage.trim() && !this.hasFile) return;

                    const fileItem = this.hasFile ? this.pond.getFile() : null;
                    
                    // If there's a file but it hasn't finished uploading to tmp yet
                    if (fileItem && fileItem.status !== 5) { // 5 is 'PROCESSING_COMPLETE'
                        alert('Please wait for image upload to complete.');
                        return;
                    }

                    const tempId = Date.now();
                    let previewUrl = null;
                    
                    if (fileItem) {
                        previewUrl = URL.createObjectURL(fileItem.file);
                    }

                    // Optimistic UI Update
                    const pendingMsg = {
                        id: null,
                        tempId: tempId,
                        sender_id: {{ auth()->id() }},
                        receiver_id: userId,
                        message: this.newMessage,
                        image_path: this.hasFile ? 'temp' : null,
                        fileProxy: previewUrl, 
                        created_at: new Date().toISOString(),
                        status: 'sending',
                        is_read: false,
                        is_auto_message: false,
                    };
                    
                    this.messages.push(pendingMsg);
                    this.scrollToBottom();

                    // Show AI typing if bot is handling and we have a text message
                    const willTriggerAI = this.isBotHandling && this.newMessage.trim();
                    if (willTriggerAI) {
                        // Show typing indicator after a short delay
                        setTimeout(() => { this.isAiTyping = true; this.scrollToBottom(); }, 500);
                    }

                    const sendMessageData = async () => {
                        const formData = new FormData();
                        formData.append('message', this.newMessage);
                        
                        if (fileItem && fileItem.serverId) {
                            // Send the server ID (folder name returned by TemporaryImageController)
                            formData.append('image', fileItem.serverId);
                        }
                        
                        if (this.activeAccount) {
                            formData.append('account_id', this.activeAccount.id);
                        }
                        
                        // Clear inputs immediately
                        this.newMessage = '';
                        this.pond.removeFiles();
                        
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        try {
                            const res = await fetch(`{{ url('/chat') }}/${userId}`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                }
                            });
                            
                            if (!res.ok) {
                                const errorData = await res.json().catch(() => ({}));
                                throw new Error(errorData.error || 'Server Error');
                            }
                            const data = await res.json();
                            
                            // Hide AI typing
                            this.isAiTyping = false;

                            const index = this.messages.findIndex(m => m.tempId === tempId);
                            if (index !== -1) {
                                this.messages[index] = data.message || data; 
                            }

                            // If there's an AI reply, add it to messages
                            if (data.ai_reply) {
                                this.messages.push(data.ai_reply);
                                // If AI changed the account context, update URL so fetchMessages picks it up!
                                if (data.ai_reply.account_id && (!this.activeAccount || this.activeAccount.id != data.ai_reply.account_id)) {
                                    const newUrl = new URL(window.location);
                                    newUrl.searchParams.set('account_id', data.ai_reply.account_id);
                                    window.history.replaceState({}, '', newUrl);
                                }
                            } else if (data.ai_error) {
                                // Add error as a special AI message inline
                                this.messages.push({
                                    id: 'err-' + tempId,
                                    tempId: 'err-' + tempId,
                                    sender_id: '{{ $user->hash_id }}',
                                    receiver_id: {{ auth()->id() }},
                                    message: data.ai_error,
                                    is_read: true,
                                    is_auto_message: true,
                                    is_error_message: true,
                                    created_at: new Date().toISOString()
                                });
                            }
                            
                            this.fetchMessages();
                            
                            if (previewUrl) URL.revokeObjectURL(previewUrl);
                        } catch (error) {
                            console.error('Error sending message:', error);
                            this.isAiTyping = false;
                            const index = this.messages.findIndex(m => m.tempId === tempId);
                            if (index !== -1) {
                                this.messages[index].status = 'error';
                                // Attach error message to the message object for the tooltip
                                this.messages[index].errorMessage = error.message;
                            }
                        }
                    };

                    sendMessageData();
                },

                async requestHandover() {
                    if (this.handoverLoading) return;

                    this.handoverLoading = true;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    try {
                        const res = await fetch('{{ route('chat.handover') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ reason: this.handoverReason })
                        });

                        const data = await res.json();
                        if (data.success) {
                            this.isBotHandling = false;
                            this.showBanner = true;
                            this.showHandoverForm = false;
                            this.handoverReason = '';
                            this.showToast(data.message, 'success');
                        } else {
                            throw new Error(data.message || 'Gagal mengirim permintaan handover.');
                        }
                    } catch (error) {
                        this.showToast(error.message || 'Gagal mengirim permintaan handover.', 'error');
                    }

                    this.handoverLoading = false;
                },

                async toggleChatMode() {
                    this.isTogglingMode = true;
                    const url = this.isBotHandling ? '{{ route("chat.handover") }}' : '{{ route("chat.enable-bot") }}';
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.isBotHandling = !this.isBotHandling;
                            this.showBanner = true;
                            this.showToast(data.message, 'success');
                        }
                    } catch (e) {
                        this.showToast('Gagal mengubah mode chat', 'error');
                    }
                    this.isTogglingMode = false;
                },

                formatMessage(text) {
                    if (!text) return '';
                    // Convert **bold** to <strong>
                    let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    // Convert *italic* to <em>
                    formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
                    return formatted;
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                proceedToCheckout() {
                    if (!this.activeAccount) {
                        this.showToast('No account selected', 'error');
                        return;
                    }

                    this.isCheckingOut = true;

                    // Build the checkout URL with parameters
                    const params = new URLSearchParams({
                        account_id: this.activeAccount.id
                    });

                    // Redirect to orders creation form
                    window.location.href = `{{ route('orders.create') }}?${params.toString()}`;
                },

                async sendPriceOffer() {
                    if (!this.offerPrice) {
                        alert('Silakan masukkan harga penawaran');
                        return;
                    }

                    if (!this.activeAccount) {
                        alert('Pilih akun terlebih dahulu sebelum mengirim penawaran harga.');
                        return;
                    }

                    this.isSendingOffer = true;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('chat.offer-price', $user) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                account_id: this.activeAccount.id,
                                offer_price: parseFloat(this.offerPrice),
                                message: this.offerMessage,
                                valid_hours: this.validHours
                            })
                        });

                        if (!res.ok) {
                            let errorMessage = 'Failed to send price offer';
                            try {
                                const errorData = await res.json();
                                errorMessage = errorData.error || errorData.message || errorMessage;
                            } catch (jsonError) {
                                const text = await res.text();
                                if (text) errorMessage = text;
                            }
                            throw new Error(errorMessage);
                        }

                        await res.json();
                        this.offerPrice = '';
                        this.offerMessage = '';
                        this.validHours = 24;
                        this.showPricePanel = false;

                        if (this.fetchMessages) {
                            this.fetchMessages();
                        }

                        this.showToast('Penawaran harga terkirim.', 'success');
                    } catch (error) {
                        alert('Error: ' + error.message);
                    }

                    this.isSendingOffer = false;
                },
                
                scrollToBottom() {
                    const container = document.getElementById('messagesContainer');
                    if(container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }
        }
    </script>
</body>
</html>
