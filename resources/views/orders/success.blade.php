<x-layouts.user>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <x-phosphor-trophy class="w-8 h-8 mr-3 text-yellow-500" />
                Selamat! Akun Game Berhasil Dibeli
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Pembelian Anda telah berhasil diproses</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
        <!-- Success Animation -->
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 dark:bg-green-900 rounded-full mb-4">
                <x-phosphor-check-circle class="w-12 h-12 text-green-600 dark:text-green-400" />
            </div>
            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                Pembelian Berhasil!
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Akun game Anda telah berhasil dibeli dan siap digunakan
            </p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Account Information -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">
                            <x-phosphor-file-text class="w-5 h-5 inline mr-2" /> Detail Akun
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-slate-600 dark:text-slate-400">Nomor Pesanan</span>
                                <span class="font-mono text-slate-800 dark:text-slate-200">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-slate-600 dark:text-slate-400">Game</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $order->gameAccount->category }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-slate-600 dark:text-slate-400">Judul Akun</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $order->gameAccount->title }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-slate-600 dark:text-slate-400">Harga</span>
                                <span class="font-bold text-green-600 dark:text-green-400">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-600 dark:text-slate-400">Tanggal Pembelian</span>
                                <span class="text-slate-800 dark:text-slate-200">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Credentials -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">
                            <x-phosphor-lock class="w-5 h-5 inline mr-2" /> Kredensial Akun
                        </h4>
                        <div class="bg-blue-600 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Username
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input
                                            type="text"
                                            value="{{ $order->account_username }}"
                                            readonly
                                            class="flex-1 px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 font-mono"
                                            id="username"
                                        >
                                        <button
                                            onclick="copyToClipboard('username')"
                                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                                            title="Salin Username"
                                        >
                                            <x-phosphor-copy class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Password
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input
                                            type="password"
                                            value="{{ $order->account_password }}"
                                            readonly
                                            class="flex-1 px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 font-mono"
                                            id="password"
                                        >
                                        <button
                                            onclick="togglePassword()"
                                            class="px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors duration-200"
                                            title="Tampilkan/Sembunyikan Password"
                                            id="toggleBtn"
                                        >
                                            <span id="eyeIconContainer">
                                                <x-phosphor-eye class="w-4 h-4" />
                                            </span>
                                        </button>
                                        <button
                                            onclick="copyToClipboard('password')"
                                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                                            title="Salin Password"
                                        >
                                            <x-phosphor-copy class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <x-phosphor-warning class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                            </div>
                            <div>
                                <h5 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    Penting!
                                </h5>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                    Simpan kredensial ini dengan aman. Jangan bagikan ke orang lain untuk menghindari penyalahgunaan akun.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
                href="{{ route('orders.index') }}"
                class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-colors duration-200"
            >
                <x-phosphor-list class="w-5 h-5 mr-2" /> Lihat Semua Pesanan
            </a>
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors duration-200"
            >
                <x-phosphor-house class="w-5 h-5 mr-2" /> Kembali ke Beranda
            </a>
            <a
                href="{{ route('accounts.show', $order->gameAccount) }}"
                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors duration-200"
            >
                <x-phosphor-game-controller class="w-5 h-5 mr-2" /> Lihat Detail Game
            </a>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(element.value).then(function() {
                // Show success feedback
                const originalText = element.value;
                element.value = 'Disalin!';
                element.classList.add('bg-green-100', 'dark:bg-green-900', 'text-green-800', 'dark:text-green-200');

                setTimeout(() => {
                    element.value = originalText;
                    element.classList.remove('bg-green-100', 'dark:bg-green-900', 'text-green-800', 'dark:text-green-200');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
            });
        }

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIconContainer = document.getElementById('eyeIconContainer');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIconContainer.innerHTML = '<x-phosphor-eye-slash class="w-4 h-4"></x-phosphor-eye-slash>';
            } else {
                passwordField.type = 'password';
                eyeIconContainer.innerHTML = '<x-phosphor-eye class="w-4 h-4"></x-phosphor-eye>';
            }
        }
    </script>
</x-layouts.user>