<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Unread Messages Alert --}}
        @if($unreadCount > 0)
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-amber-900 dark:text-amber-100">{{ $unreadCount }} Pesan Belum Dibaca</h3>
                    <p class="text-sm text-amber-700 dark:text-amber-300">Anda memiliki pesan baru yang menunggu</p>
                </div>
            </div>
            <a href="{{ route('admin.chat.index') }}" class="self-start sm:self-auto shrink-0 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition text-sm">
                Lihat Pesan
            </a>
        </div>
        @endif

        {{-- Stat Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex flex-col items-center justify-center p-5 sm:p-6 bg-blue-600 dark:bg-blue-900/20 rounded-xl border border-emerald-200 dark:border-emerald-700">
                <div class="text-4xl sm:text-5xl font-bold text-blue-400 dark:text-blue-400">{{ $totalSold }}</div>
                <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300 mt-2 text-center">Total Akun Terjual</div>
            </div>
            <div class="flex flex-col items-center justify-center p-5 sm:p-6 bg-blue-600 dark:bg-blue-900/20 rounded-xl border border-sky-200 dark:border-sky-700">
                <div class="text-4xl sm:text-5xl font-bold text-sky-600 dark:text-sky-400">{{ $totalAvailable }}</div>
                <div class="text-sm font-medium text-sky-700 dark:text-sky-300 mt-2 text-center">Akun Tersedia</div>
            </div>
            <div class="flex flex-col items-center justify-center p-5 sm:p-6 bg-blue-600 dark:bg-blue-900/20 rounded-xl border border-blue-300 dark:border-purple-700">
                <div class="text-4xl sm:text-5xl font-bold text-blue-500 dark:text-blue-500">{{ $chatThisMonth }}</div>
                <div class="text-sm font-medium text-purple-700 dark:text-blue-300 mt-2 text-center">Chat Bulan Ini</div>
            </div>
        </div>

        {{-- Charts Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Sold vs Available Doughnut --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4">Status Akun</h3>
                <div class="relative mx-auto" style="max-height:200px; max-width:200px;">
                    <canvas id="accountStatusChart"></canvas>
                </div>
                <div class="mt-4 flex justify-center gap-4 text-sm flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-400 shrink-0"></div>
                        <span class="text-gray-600 dark:text-gray-300">Terjual: {{ $totalSold }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-sky-500 shrink-0"></div>
                        <span class="text-gray-600 dark:text-gray-300">Tersedia: {{ $totalAvailable }}</span>
                    </div>
                </div>
            </div>

            {{-- Accounts by Category Bar --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4">Akun per Kategori</h3>
                <div class="relative" style="max-height:200px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            {{-- Chat Activity Line --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sm:p-6 sm:col-span-2 lg:col-span-1">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4">Aktivitas Chat</h3>
                <div class="relative" style="max-height:200px;">
                    <canvas id="chatActivityChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const isDark    = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
        const tickColor = isDark ? '#9ca3af' : '#6b7280';

        // Account Status Doughnut
        new Chart(document.getElementById('accountStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Terjual', 'Tersedia'],
                datasets: [{
                    data: [{{ $totalSold }}, {{ $totalAvailable }}],
                    backgroundColor: ['#10b981', '#0ea5e9'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } }
            }
        });

        // Category Bar Chart
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($accountsByCategory->pluck('category')) !!},
                datasets: [{
                    label: 'Jumlah Akun',
                    data: {!! json_encode($accountsByCategory->pluck('count')) !!},
                    backgroundColor: '#0ea5e9',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: tickColor, maxRotation: 30 }, grid: { color: gridColor } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: tickColor }, grid: { color: gridColor } }
                }
            }
        });

        // Chat Activity Line Chart
        new Chart(document.getElementById('chatActivityChart'), {
            type: 'line',
            data: {
                labels: ['Hari Ini', 'Minggu Ini', 'Bulan Ini'],
                datasets: [{
                    label: 'Pengguna Chat',
                    data: [{{ $chatToday }}, {{ $chatThisWeek }}, {{ $chatThisMonth }}],
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#8b5cf6',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: tickColor }, grid: { color: gridColor } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: tickColor }, grid: { color: gridColor } }
                }
            }
        });
    </script>
</x-layouts.app>
