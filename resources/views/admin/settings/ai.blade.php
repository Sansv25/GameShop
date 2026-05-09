<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('AI Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-lg flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.ai.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- General Toggle -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-700">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white">Global AI Chatbot</h3>
                                <p class="text-xs text-slate-500">Aktifkan atau nonaktifkan AI untuk seluruh pelanggan.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="chatbot_active" value="0">
                                <input type="checkbox" name="chatbot_active" value="1" class="sr-only peer" {{ $chatbotActive ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        <!-- System Prompt -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">System Instruction (Prompt Utama)</label>
                            <p class="text-xs text-slate-500 mb-3">Ini adalah instruksi dasar yang dibaca oleh AI. Anda bisa mengatur gaya bahasa dan aturan main di sini.</p>
                            <textarea 
                                name="ai_system_prompt" 
                                rows="12" 
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm font-mono leading-relaxed focus:border-primary focus:ring-primary shadow-sm"
                            >{{ $systemPrompt }}</textarea>
                            @error('ai_system_prompt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- API Key -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Gemini API Key</label>
                            <input 
                                type="password" 
                                name="gemini_api_key" 
                                value="{{ $apiKey }}" 
                                placeholder="AIzaSy..."
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-primary focus:ring-primary shadow-sm"
                            >
                            <p class="text-[10px] text-slate-400 mt-2 italic">*Kosongkan jika tidak ingin mengubah kunci API saat ini.</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/25 hover:bg-primary/90 transform active:scale-95 transition-all">
                                Simpan Konfigurasi AI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
