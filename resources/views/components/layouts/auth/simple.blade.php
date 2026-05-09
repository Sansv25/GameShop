<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/aquawolf04/font-awesome-pro@5cd1511/css/all.css">
        <style>
            html, body { background-color: #030712 !important; color: #f1f5f9; }
            .glow-text{background:#2563eb;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
            .glass{background:rgba(255,255,255,.04);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.08)}
            .btn-primary{background:#2563eb;border:1px solid rgba(37,99,235,.3);color:#fff;transition:all .3s}
            .btn-primary:hover{box-shadow:0 0 30px rgba(37,99,235,.4);transform:translateY(-2px)}
            .mesh-bg{position:fixed;inset:0;z-index:0;background:rgba(30,58,138,0.1);pointer-events:none}
        </style>
    </head>
    <body class="min-h-screen antialiased relative">
        <div class="mesh-bg"></div>
        <div class="relative z-10 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-4 font-medium mb-4">
                    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-24 md:h-32 w-auto">
                    <span class="text-3xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                </a>
                <div class="space-y-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
