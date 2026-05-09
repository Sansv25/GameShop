<x-layouts.app title="Manage Accounts">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden dark:bg-slate-800 dark:border-slate-700">

            {{-- Header --}}
            <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center gap-3 sm:justify-between">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">All Accounts</h2>
                <a href="{{ route('admin.accounts.create') }}"
                   class="self-start sm:self-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Account
                </a>
            </div>

            {{-- Desktop Table (hidden on mobile) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-200 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">Image</th>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($accounts as $account)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-6 py-4">
                                <div class="relative w-12 h-12">
                                    <img src="{{ Storage::url($account->image_path) }}" class="w-12 h-12 rounded-lg object-cover bg-slate-200">
                                    @if($account->images && count($account->images) > 1)
                                    <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                                        +{{ count($account->images) - 1 }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900 dark:text-white truncate max-w-xs" title="{{ $account->title }}">{{ $account->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-600 dark:text-slate-100">
                                    {{ $account->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($account->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $account->status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    @if($account->status === 'available')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    @else
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    @endif
                                    {{ ucfirst($account->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.accounts.edit', $account) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ $account->id }}')" class="text-red-500 hover:text-red-700 p-1" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-form-{{ $account->id }}" action="{{ route('admin.accounts.destroy', $account) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List (shown only on mobile) --}}
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($accounts as $account)
                <div class="p-4 flex gap-3 items-start">
                    {{-- Thumbnail --}}
                    <div class="relative shrink-0">
                        <img src="{{ Storage::url($account->image_path) }}" class="w-14 h-14 rounded-lg object-cover bg-slate-200">
                        @if($account->images && count($account->images) > 1)
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                            +{{ count($account->images) - 1 }}
                        </span>
                        @endif
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white truncate text-sm">{{ $account->title }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $account->category }}</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-1">Rp {{ number_format($account->price, 0, ',', '.') }}</p>
                        <span class="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $account->status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                            {{ ucfirst($account->status) }}
                        </span>
                    </div>
                    {{-- Actions --}}
                    <div class="flex flex-col gap-2 shrink-0">
                        <a href="{{ route('admin.accounts.edit', $account) }}" class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-500 hover:text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button type="button" onclick="confirmDelete('{{ $account->id }}')" class="p-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <form id="delete-form-mobile-{{ $account->id }}" action="{{ route('admin.accounts.destroy', $account) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Try desktop form first, then mobile
                    const form = document.getElementById('delete-form-' + id)
                               || document.getElementById('delete-form-mobile-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
    @endpush
</x-layouts.app>
