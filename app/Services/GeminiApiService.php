<?php

namespace App\Services;

use App\Models\GameAccount;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiApiService
{
    protected string $apiKey;
    protected string $model = 'gemini-2.5-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? '';
    }

    /**
     * Generate a chatbot reply based on the customer's message and conversation history.
     *
     * @return array{success: bool, text: string|null, error: string|null}
     */
    public function generateReply(int $customerId, int $adminId, string $customerMessage): array
    {
        if (empty($this->apiKey)) {
            Log::error('Gemini API key is not configured.');
            return ['success' => false, 'text' => null, 'error' => 'API Key Gemini belum dikonfigurasi. Hubungi admin untuk mengatur API Key.'];
        }

        try {
            // Build system instruction with game account data
            $systemInstruction = $this->buildSystemInstruction();

            // Build conversation history
            $conversationHistory = $this->buildConversationHistory($customerId, $adminId);

            // Build the request payload
            $payload = [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ],
                'contents' => [
                    ...$conversationHistory,
                    [
                        'role' => 'user',
                        'parts' => [['text' => $customerMessage]]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ];

            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

            $response = Http::withHeaders([
                'X-goog-api-key' => $this->apiKey
            ])->timeout(30)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text) {
                    return ['success' => true, 'text' => $text, 'error' => null];
                }
                return ['success' => false, 'text' => null, 'error' => 'AI tidak dapat menghasilkan respons. Silakan coba lagi.'];
            }

            $status = $response->status();
            $body = $response->json();
            $errorMsg = $body['error']['message'] ?? 'Unknown error';

            Log::error('Gemini API Error', [
                'status' => $status,
                'body' => $response->body(),
            ]);

            // Friendly error messages based on status code
            if ($status === 429) {
                return ['success' => false, 'text' => null, 'error' => 'Kuota API Gemini sudah habis. AI tidak dapat merespons saat ini. Silakan chat langsung dengan admin.'];
            } elseif ($status === 403) {
                return ['success' => false, 'text' => null, 'error' => 'API Key tidak valid atau tidak memiliki akses. Hubungi admin.'];
            } elseif ($status === 400) {
                return ['success' => false, 'text' => null, 'error' => 'Permintaan ke AI gagal. Silakan coba lagi.' ];
            } elseif ($status === 503) {
                return ['success' => false, 'text' => null, 'error' => 'AI sedang sibuk atau tidak tersedia. Silakan coba lagi nanti atau gunakan Chat Admin.'];
            } elseif ($status >= 500) {
                return ['success' => false, 'text' => null, 'error' => 'Layanan AI sedang bermasalah. Silakan coba lagi nanti atau hubungi admin.'];
            }

            return ['success' => false, 'text' => null, 'error' => 'AI Error: Silakan coba lagi nanti.'];
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'text' => null, 'error' => 'Koneksi ke AI gagal: ' . $e->getMessage()];
        }
    }

    /**
     * Build system instruction with available game accounts data.
     */
    protected function buildSystemInstruction(): string
    {
        $accounts = GameAccount::where('status', 'available')->get();

        $accountList = $accounts->map(function ($acc) {
            $price = 'Rp ' . number_format($acc->price, 0, ',', '.');
            $info = "- ID_AKUN: {$acc->id} | {$acc->title} | Kategori: {$acc->category} | Harga: {$price}";
            if ($acc->description) {
                // Trim description to max 200 chars to save tokens
                $desc = mb_strlen($acc->description) > 200
                    ? mb_substr($acc->description, 0, 200) . '...'
                    : $acc->description;
                $info .= " | Deskripsi: {$desc}";
            }
            return $info;
        })->implode("\n");

        $soldAccounts = GameAccount::where('status', 'sold')->count();
        $availableAccounts = $accounts->count();

        $promptHeader = Setting::getValue('ai_system_prompt', $this->getDefaultPromptHeader());

        return <<<PROMPT
{$promptHeader}

## Daftar Akun Game Tersedia:
{$accountList}

Jika tidak ada akun yang tersedia, beri tahu pelanggan stok sedang kosong.
PROMPT;
    }

    private function getDefaultPromptHeader(): string
    {
        return <<<PROMPT
Kamu adalah asisten virtual AI untuk toko jual beli akun game online. Nama toko ini adalah "GameShop".

## Panduan Umum:
- Jawab SELALU dalam Bahasa Indonesia (kecuali nama game/istilah gaming yang lazim dalam Bahasa Inggris).
- Bersikap ramah, sopan, dan helpful.
- Jika pelanggan bertanya tentang akun game yang tersedia, referensikan data di bawah ini secara akurat.
- Jika pelanggan meminta rekomendasi akun (seperti "akun termurah", "akun terbaik", dll) atau bertanya spesifik tentang akun yang lain dari yang sedang diobrolkan, kamu HARUS mencari akun yang paling cocok dari daftar.
- JIKA kamu menyarankan atau membicarakan akun spesifik dari daftar, kamu WAJIB menambahkan tag rahasia `[ACCOUNT_ID: {ID_AKUN}]` di akhir jawabanmu. Contoh: "Ini akun termurah yang kita punya! [ACCOUNT_ID: 15]". Sistem akan menggunakannya untuk mengubah tampilan UI pengguna secara otomatis.
- Jangan sebut ID akun kepada pengguna selain melalui tag sistem di atas.
- Jika pelanggan bertanya sesuatu di luar konteks (bukan tentang akun game / toko), jawab dengan sopan bahwa kamu hanya bisa membantu terkait akun game yang dijual di toko ini.
- Jangan mengarang detail akun yang tidak ada dalam data.
- Gunakan emoji secukupnya agar ramah.
PROMPT;
    }

    /**
     * Build conversation history from recent messages (last 20 messages).
     */
    protected function buildConversationHistory(int $customerId, int $adminId): array
    {
        $messages = Message::where(function ($q) use ($customerId, $adminId) {
            $q->where('sender_id', $customerId)->where('receiver_id', $adminId);
        })->orWhere(function ($q) use ($customerId, $adminId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $customerId);
        })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        $history = [];

        foreach ($messages as $msg) {
            if (empty($msg->message)) continue;

            $role = $msg->sender_id === $customerId ? 'user' : 'model';

            $history[] = [
                'role' => $role,
                'parts' => [['text' => $msg->message]]
            ];
        }

        return $history;
    }
}
