<x-layouts.app>
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Kirim Broadcast</h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kirim pengumuman atau promosi ke semua pengguna terdaftar.</p>
                </div>
                <div class="rounded-3xl bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    Penerima: {{ $recipientCount }} pengguna
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            @if (session('success'))
                <div class="mb-4 rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.broadcast.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Judul Broadcast</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" placeholder="Contoh: Diskon 20% Hari Ini" required>
                    @error('subject') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Pesan</label>
                    <textarea name="message" rows="6" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" placeholder="Tuliskan pesan promosi, pengumuman, atau update penting di sini." required>{{ old('message') }}</textarea>
                    @error('message') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-slate-500 dark:text-slate-400">Broadcast ini dikirim sebagai pesan langsung dari admin.</div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary/90 transition">Kirim Broadcast</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
