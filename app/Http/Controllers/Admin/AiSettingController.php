<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AiSettingController extends Controller
{
    public function edit()
    {
        $systemPrompt = Setting::getValue('ai_system_prompt', $this->getDefaultPrompt());
        $chatbotActive = Setting::isChatbotActive();
        $apiKey = Setting::getGeminiApiKey();

        return view('admin.settings.ai', compact('systemPrompt', 'chatbotActive', 'apiKey'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ai_system_prompt' => 'required|string',
            'chatbot_active' => 'required|boolean',
            'gemini_api_key' => 'nullable|string',
        ]);

        Setting::setValue('ai_system_prompt', $request->ai_system_prompt);
        Setting::setValue('chatbot_active', $request->chatbot_active ? '1' : '0');
        
        if ($request->filled('gemini_api_key')) {
            Setting::setValue('gemini_api_key', $request->gemini_api_key);
        }

        return back()->with('success', 'Pengaturan AI berhasil diperbarui!');
    }

    private function getDefaultPrompt()
    {
        return "Kamu adalah asisten virtual AI untuk toko jual beli akun game online. Nama toko ini adalah \"GameShop\".\n\n## Panduan Umum:\n- Jawab SELALU dalam Bahasa Indonesia.\n- Bersikap ramah, sopan, dan helpful.\n- Jika pelanggan menyarankan atau membicarakan akun spesifik, kamu WAJIB menambahkan tag rahasia `[ACCOUNT_ID: {ID_AKUN}]` di akhir jawabanmu.";
    }
}
