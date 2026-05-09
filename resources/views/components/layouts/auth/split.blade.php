<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <style>
        html, body { background-color: #030712 !important; color: #f1f5f9; }
    </style>
    <body class="min-h-screen antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex dark:border-r dark:border-gray-800">
                <div class="absolute inset-0 bg-gray-900"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-2">
                    <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-10 md:h-12 w-auto">
                    <span class="text-xl md:text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <x-heading size="xl">&ldquo;{{ trim($message) }}&rdquo;</x-heading>
                        <footer><x-heading>{{ trim($author) }}</x-heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-3 font-medium lg:hidden mb-4">
                        <img src="{{ asset('asset/logo-square.png') }}" alt="GameShop Logo" class="h-20 w-auto">
                        <span class="text-2xl font-black text-white tracking-widest" style="font-family: 'Orbitron', sans-serif;">GAME<span class="text-blue-500">SHOP</span></span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
