<x-layouts.user>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Kembali</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Konfirmasi Pembelian</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Periksa detail akun sebelum menyelesaikan pembelian</p>
        </div>

        <div class="max-w-4xl">
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Order Summary -->
                <div class="lg:col-span-2 space-y-6">
                    @if ($account)
                        <!-- Account Details Card -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="aspect-video bg-slate-100 dark:bg-slate-900 overflow-hidden">
                                <img src="{{ Storage::url($account->image_path) }}" alt="{{ $account->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <span class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold">{{ $account->category }}</span>
                                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ $account->title }}</h2>
                                    </div>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $account->description }}</p>
                            </div>
                        </div>

                        <!-- Pricing Details -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                            <h3 class="font-bold text-lg text-slate-900 dark:text-white">Rincian Harga</h3>
                            
                            <div class="space-y-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <!-- Original Price -->
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-600 dark:text-slate-400">Harga Asli</span>
                                    <span class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($account->price, 0, ',', '.') }}</span>
                                </div>

                                <!-- Negotiated Price (if different) -->
                                @if ($negotiatedPrice && $negotiatedPrice != $account->price)
                                    <div class="flex justify-between items-center text-blue-400 dark:text-blue-400">
                                        <span class="font-medium">Penawaran Khusus</span>
                                        <span class="inline-block px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 rounded text-sm font-bold">
                                            -Rp {{ number_format($account->price - $negotiatedPrice, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Final Price -->
                                <div class="flex justify-between items-center pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <span class="font-bold text-slate-900 dark:text-white">Total Bayar</span>
                                    <span class="text-2xl font-bold text-blue-400 dark:text-blue-400">
                                        Rp {{ number_format($negotiatedPrice ?? $account->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-6 text-center">
                            <p class="text-red-600 dark:text-red-400 font-medium">Akun tidak ditemukan atau tidak tersedia</p>
                            <a href="{{ route('wishlists.index') }}" class="inline-block mt-4 px-6 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition">
                                Kembali ke Wishlist
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Order Form Sidebar -->
                <div class="sticky top-6 h-fit">
                    <form action="{{ route('orders.store') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                        @csrf

                        @if ($account)
                            <input type="hidden" name="game_account_id" value="{{ $account->hash_id }}">
                            <input type="hidden" name="negotiated_price" value="{{ $negotiatedPrice ?? $account->price }}">

                            <!-- Agreement Checkbox -->
                            <div class="space-y-3 pb-4 border-b border-slate-200 dark:border-slate-700">
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Persyaratan Pembelian</h4>
                                
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input id="agree_terms" type="checkbox" name="agree_terms" value="1" required class="mt-1 w-4 h-4 text-primary rounded">
                                    <span class="text-xs text-slate-600 dark:text-slate-400">
                                        Saya setuju akun ini dibeli dengan harga yang ditentukan dan data login akan ditampilkan setelah pembelian
                                    </span>
                                </label>

                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input id="agree_final" type="checkbox" name="agree_final" value="1" required class="mt-1 w-4 h-4 text-primary rounded">
                                    <span class="text-xs text-slate-600 dark:text-slate-400">
                                        Saya memahami bahwa pembelian ini bersifat final dan tidak dapat dibatalkan
                                    </span>
                                </label>
                            </div>

                            <!-- Additional Info -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Catatan Pembelian (Opsional)
                                </label>
                                <textarea name="notes" rows="3" 
                                    placeholder="Tambahkan catatan khusus untuk pesanan ini..." 
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/30">
                                </textarea>
                            </div>

                            <!-- Submit Button -->
                            <button id="orderSubmitBtn" type="submit" class="w-full py-3 px-4 bg-blue-400 hover:bg-blue-400 text-white font-bold rounded-lg transition-all shadow-lg shadow-blue-400/30 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m10 0a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 100 4 2 2 0 000-4z" />
                                </svg>
                                <span>Selesaikan Pembelian</span>
                            </button>

                            <!-- Warning -->
                            <div id="orderAgreementWarning" class="text-[10px] text-amber-700 dark:text-amber-300 text-center pt-2 hidden">
                                Harap centang kedua persyaratan agar tombol pembelian aktif.
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 text-center pt-2">
                                Login data akan muncul di halaman sukses
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const agreeTerms = document.getElementById('agree_terms');
            const agreeFinal = document.getElementById('agree_final');
            const submitBtn = document.getElementById('orderSubmitBtn');
            const warning = document.getElementById('orderAgreementWarning');

            if (!agreeTerms || !agreeFinal || !submitBtn || !warning) {
                return;
            }

            const updateState = () => {
                const isValid = agreeTerms.checked && agreeFinal.checked;
                submitBtn.disabled = !isValid;
                submitBtn.classList.toggle('opacity-50', !isValid);
                submitBtn.classList.toggle('cursor-not-allowed', !isValid);
                warning.classList.toggle('hidden', isValid);
            };

            agreeTerms.addEventListener('change', updateState);
            agreeFinal.addEventListener('change', updateState);
            updateState();
        });
    </script>
</x-layouts.user>
