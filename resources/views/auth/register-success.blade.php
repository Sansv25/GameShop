<x-layouts.auth.card>
    <div class="text-center">
        <div class="mb-6 flex justify-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-500/20 text-blue-500 shadow-[0_0_40px_rgba(37,99,235,0.3)]">
                <i class="fas fa-envelope-open-text text-3xl"></i>
            </div>
        </div>

        <h2 class="mb-2 text-2xl font-black text-white tracking-wide">Cek Email Anda</h2>
        
        <p class="mb-6 text-sm text-white/60 leading-relaxed">
            Kami telah mengirimkan tautan verifikasi ke email <br>
            <span class="font-bold text-white">{{ session('email') ?? 'Anda' }}</span>.
        </p>

        <div class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-4 mb-6 text-left">
            <h3 class="mb-1 text-xs font-bold uppercase tracking-wider text-blue-400">Penting:</h3>
            <p class="text-xs text-white/70 leading-relaxed">
                Anda tidak akan bisa login atau bertransaksi di GameShop sebelum memverifikasi alamat email Anda. Jika email tidak ditemukan, periksa folder spam/junk.
            </p>
        </div>

        <a href="{{ route('login') }}" class="btn-primary block w-full rounded-xl px-4 py-3 text-sm font-bold shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02]">
            Kembali ke Halaman Login
        </a>
    </div>
</x-layouts.auth.card>
