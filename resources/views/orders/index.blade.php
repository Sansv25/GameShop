<x-layouts.user>
    <x-slot:pageTitle>Riwayat Pesanan</x-slot:pageTitle>
    <x-slot:pageSubtitle>Semua transaksi pembelian akun game Anda</x-slot:pageSubtitle>

    @if($orders->isEmpty())
        <div class="gsap-fade-up flex flex-col items-center justify-center py-32 text-center">
            <div class="mb-6 animate-bounce">
                <svg class="w-24 h-24 mx-auto text-blue-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h2 class="text-2xl font-black text-white mb-3">Belum Ada Pesanan</h2>
            <p class="text-white/40 mb-8 max-w-sm">Anda belum pernah melakukan pembelian. Yuk mulai beli akun game favoritmu!</p>
            <a href="{{ route('home') }}" class="gs-btn-primary px-8 py-3 rounded-2xl text-sm font-bold">Mulai Belanja</a>
        </div>
    @else
        {{-- Stats Bar --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @php
                $total = $orders->count();
                $completed = $orders->where('status','completed')->count();
                $pending = $orders->where('status','pending')->count();
                $totalSpent = $orders->sum('amount');
            @endphp
            @foreach([
                ['label' => 'Total Order', 'value' => $total, 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>', 'color' => 'rgba(99,102,241,'],
                ['label' => 'Selesai', 'value' => $completed, 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'color' => 'rgba(52,211,153,'],
                ['label' => 'Pending', 'value' => $pending, 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'color' => 'rgba(251,191,36,'],
                ['label' => 'Total Spent', 'value' => 'Rp '.number_format($totalSpent,0,',','.'), 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'color' => 'rgba(139,92,246,'],
            ] as $stat)
            <div class="gs-card rounded-2xl p-5 gsap-fade-up" style="border-color:{{ $stat['color'] }}.2)">
                <div class="mb-3 text-white/50">{!! $stat['icon'] !!}</div>
                <div class="text-xl md:text-2xl font-black text-white">{{ $stat['value'] }}</div>
                <div class="text-xs text-white/40 font-semibold uppercase tracking-widest mt-1">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Orders Table --}}
        <div class="gs-card rounded-3xl overflow-hidden mb-8">
            <div class="p-5 border-b border-white/5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="font-bold text-white text-sm">Semua Transaksi</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,.05)">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-white/30 uppercase tracking-widest">No. Order</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-white/30 uppercase tracking-widest">Akun Game</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-white/30 uppercase tracking-widest">Harga</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-white/30 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-white/30 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-white/30 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr style="border-bottom:1px solid rgba(255,255,255,.03)" class="hover:bg-white/[.02] transition">
                            <td class="px-6 py-4 text-sm font-mono font-bold text-blue-300">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-white/70 font-medium">{{ $order->gameAccount->title ?? 'Akun Dihapus' }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-white">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusMap = [
                                        'completed' => ['bg:rgba(52,211,153,.15)', 'border:rgba(52,211,153,.25)', 'color:#34d399', 'Selesai'],
                                        'pending'   => ['bg:rgba(251,191,36,.12)', 'border:rgba(251,191,36,.25)', 'color:#fbbf24', 'Pending'],
                                    ];
                                    $s = $statusMap[$order->status] ?? ['bg:rgba(255,255,255,.05)', 'border:rgba(255,255,255,.1)', 'color:rgba(255,255,255,.4)', ucfirst($order->status)];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider"
                                      style="background:{{ explode('bg:', $s[0])[1] }};border:1px solid {{ explode('border:', $s[1])[1] }};color:{{ explode('color:', $s[2])[1] }}">
                                    {{ $s[3] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-white/40">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.success', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500/20 hover:text-blue-300 transition text-xs font-bold">
                                    Lihat Akun
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    @endif
</x-layouts.user>
