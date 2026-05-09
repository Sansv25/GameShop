<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <style>
        html, body { background-color: #030712 !important; color: #f1f5f9; }
    </style>
    <body class="min-h-screen antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-4 font-medium mb-2">
                    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-24 md:h-32 w-auto">
                    <span class="text-3xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                </a>

                <div class="space-y-6">
                    <div class="rounded-xl border border-white/10 bg-slate-900/50 backdrop-blur-xl text-white shadow-xs">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
