<x-layouts.app title="Messages">
    <div x-data="adminChatManager()" x-init="startPolling()">
        <!-- AI Chatbot Settings Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden dark:bg-slate-800 dark:border-slate-700 mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <!-- AI Icon -->
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300"
                             :class="chatbotActive ? 'bg-blue-600 shadow-lg shadow-violet-500/30' : 'bg-slate-100 dark:bg-slate-700'">
                            <svg class="w-6 h-6 transition-colors" :class="chatbotActive ? 'text-white' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                AI Auto-Reply
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold tracking-wider uppercase transition-all duration-300"
                                      :class="chatbotActive ? 'bg-blue-100 text-emerald-700 dark:bg-blue-900/50 dark:text-blue-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'"
                                      x-text="chatbotActive ? 'ACTIVE' : 'OFF'"></span>
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Powered by Gemini AI — auto-responds to customer chats</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Toggle Switch -->
                        <button @click="toggleChatbot()" :disabled="isToggling"
                                class="relative inline-flex h-7 w-[52px] items-center rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
                                :class="chatbotActive ? 'bg-blue-600 focus:ring-violet-500 shadow-lg shadow-violet-500/30' : 'bg-slate-300 dark:bg-slate-600 focus:ring-slate-400'">
                            <span class="inline-block h-5 w-5 rounded-full bg-white shadow-md transform transition-all duration-300"
                                  :class="chatbotActive ? 'translate-x-[26px]' : 'translate-x-1'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Summary Cards -->
        <div class="grid gap-4 lg:grid-cols-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm dark:bg-slate-800 dark:border-slate-700">
                <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Total Order</div>
                <div class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalOrders) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm dark:bg-slate-800 dark:border-slate-700">
                <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Tertunda</div>
                <div class="mt-4 text-3xl font-bold text-amber-600">{{ number_format($pendingOrders) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm dark:bg-slate-800 dark:border-slate-700">
                <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Total Pendapatan</div>
                <div class="mt-4 text-3xl font-bold text-blue-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm dark:bg-slate-800 dark:border-slate-700">
                <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Pelanggan</div>
                <div class="mt-4 text-3xl font-bold text-sky-600">{{ number_format($totalCustomers) }}</div>
                @if($topCategory)
                    <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">Top Kategori: {{ $topCategory->category }}</div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden dark:bg-slate-800 dark:border-slate-700 mb-6">
            <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Broadcast Promo</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Kirim pesan singkat ke semua pengguna yang terdaftar.</p>
                </div>
                <a href="{{ route('admin.broadcast.create') }}" class="inline-flex items-center gap-2 rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Kirim Broadcast
                </a>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden dark:bg-slate-800 dark:border-slate-700">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Recent Conversations</h2>
            </div>

            @if($users->isEmpty())
                <div class="p-12 text-center text-slate-400 dark:text-slate-500">
                     <p>No messages yet.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($users as $user)
                        <a href="{{ route('chat.show', $user) }}"
                           class="block hover:bg-slate-50 dark:hover:bg-slate-700/50 transition p-4 sm:px-6"
                           data-user-id="{{ $user->hash_id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-300 font-bold text-lg shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <!-- Bot handling indicator -->
                                        @if($user->is_bot_active && $chatbotActive)
                                            <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full bg-blue-500 border-2 border-white dark:border-slate-800 flex items-center justify-center" title="AI is handling this chat">
                                                <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $user->name }}</h3>
                                            @if($user->unread_count > 0)
                                                <span data-unread-count class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                                    {{ $user->unread_count }}
                                                </span>
                                            @else
                                                <span data-unread-count style="display: none;"></span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400 ml-4">
                                    @if($user->is_bot_active && $chatbotActive)
                                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-violet-100 dark:bg-violet-900/30 text-blue-600 dark:text-blue-400 whitespace-nowrap">AI</span>
                                    @else
                                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 whitespace-nowrap">Manual</span>
                                    @endif
                                    <span class="text-xs font-medium bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-slate-500 dark:text-slate-300 whitespace-nowrap">Go to Chat <span class="ml-1">&rarr;</span></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>


        <!-- Toast Notification -->
        <div x-show="toast.show" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl shadow-2xl text-sm font-medium text-white"
             :class="toast.type === 'success' ? 'bg-blue-600' : 'bg-blue-600'">
            <span x-text="toast.message"></span>
        </div>
    </div>

    <script>
        function adminChatManager() {
            return {
                chatbotActive: @js($chatbotActive),
                isToggling: false,
                pollingInterval: null,
                toast: { show: false, message: '', type: 'success' },

                startPolling() {
                    this.pollingInterval = setInterval(() => {
                        fetch('{{ route('admin.chat.index') }}')
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newUsers = doc.querySelectorAll('[data-user-id]');
                                newUsers.forEach(newUser => {
                                    const userId = newUser.dataset.userId;
                                    const unreadBadge = newUser.querySelector('[data-unread-count]');
                                    const currentBadge = document.querySelector(`[data-user-id='${userId}'] [data-unread-count]`);
                                    if (currentBadge && unreadBadge) {
                                        currentBadge.textContent = unreadBadge.textContent;
                                        currentBadge.style.display = unreadBadge.style.display;
                                    }
                                });
                            })
                            .catch(err => console.error('Error polling:', err));
                    }, 5000);
                },

                async toggleChatbot() {
                    this.isToggling = true;
                    try {
                        const res = await fetch('{{ route('admin.settings.chatbot.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.chatbotActive = data.chatbot_active;
                            this.showToast(data.message, 'success');
                        }
                    } catch (e) {
                        this.showToast('Gagal mengubah status chatbot', 'error');
                    }
                    this.isToggling = false;
                },


                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 3000);
                }
            }
        }
    </script>
</x-layouts.app>
