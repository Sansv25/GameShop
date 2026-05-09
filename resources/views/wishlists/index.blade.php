<x-layouts.user>
    <x-slot:pageTitle>Wishlist Saya</x-slot:pageTitle>
    <x-slot:pageSubtitle>Akun game impian yang Anda tandai</x-slot:pageSubtitle>

    @if($wishlists->isEmpty())
        <div class="gsap-fade-up flex flex-col items-center justify-center py-32 text-center">
            <div class="text-7xl mb-6 animate-bounce">💜</div>
            <h2 class="text-2xl font-black text-white mb-3">Wishlist Kosong</h2>
            <p class="text-white/40 mb-8 max-w-sm">Kamu belum menambahkan akun apapun ke wishlist. Temukan akun terbaikmu sekarang!</p>
            <a href="{{ route('home') }}" class="gs-btn-primary px-8 py-3 rounded-2xl text-sm font-bold">Jelajahi Katalog</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($wishlists as $wishlist)
            <div class="gs-card rounded-3xl overflow-hidden group">
                <a href="{{ route('accounts.show', $wishlist->gameAccount) }}" class="block relative h-48 overflow-hidden">
                    @if($wishlist->gameAccount->image_path)
                        <img src="{{ Storage::url($wishlist->gameAccount->image_path) }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-85"
                             alt="{{ $wishlist->gameAccount->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-indigo-950/50 text-white/20 text-sm font-bold">No Image</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                    <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                         style="background:rgba(255,255,255,.08);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.1);color:rgba(167,139,250,1)">
                        {{ $wishlist->gameAccount->category ?? 'Game' }}
                    </div>
                </a>
                <div class="p-5">
                    <h3 class="font-bold text-white text-base mb-1 truncate group-hover:text-blue-300 transition">
                        {{ $wishlist->gameAccount->title }}
                    </h3>
                    <p class="text-blue-500 font-black text-lg mb-4">Rp {{ number_format($wishlist->gameAccount->price, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('accounts.show', $wishlist->gameAccount) }}"
                           class="gs-btn-primary flex-1 text-center py-2.5 rounded-xl text-sm font-bold relative z-10">
                            Lihat Detail
                        </a>
                        <form action="{{ route('wishlists.destroy', $wishlist->gameAccount) }}" method="POST" class="flex-none">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition"
                                    style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:rgb(248,113,113);"
                                    onmouseover="this.style.background='rgba(239,68,68,.2)'"
                                    onmouseout="this.style.background='rgba(239,68,68,.1)'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof gsap === 'undefined') return;
        // Stagger wishlist cards with 3D rotate-in
        gsap.utils.toArray('.gs-card').forEach((card, i) => {
            gsap.fromTo(card,
                { rotationY: 20, transformPerspective: 800, opacity: 0, y: 40 },
                { rotationY: 0, opacity: 1, y: 0, duration: 0.75, ease: 'power3.out', delay: 0.3 + i * 0.1 }
            );
        });
    });
    </script>
    @endpush
</x-layouts.user>
