<?php

namespace Database\Seeders;

use App\Models\CannedResponse;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PlatformInitSeeder extends Seeder
{
    public function run(): void
    {
        // Canned Responses
        $responses = [
            ['title' => 'Salam Hangat', 'content' => 'Halo! Terima kasih telah menghubungi GameShop. Ada yang bisa kami bantu mengenai akun game ini?'],
            ['title' => 'Cara Bayar', 'content' => 'Untuk pembayaran, kami menerima Transfer Bank, QRIS, dan E-Wallet (Dana/OVO). Silakan konfirmasi jika Anda ingin melanjutkan ke pembayaran.'],
            ['title' => 'Akun Ready?', 'content' => 'Ya, akun ini masih tersedia dan siap untuk dipindah-tangankan setelah pembayaran dikonfirmasi.'],
            ['title' => 'Nego Tipis', 'content' => 'Mohon maaf, harga yang tertera sudah harga pas (nett) karena kualitas akun sudah kami jamin keamanannya.'],
            ['title' => 'Proses Cepat', 'content' => 'Proses pengiriman data login dilakukan maksimal 10-15 menit setelah bukti pembayaran kami validasi.'],
        ];

        foreach ($responses as $res) {
            CannedResponse::updateOrCreate(['title' => $res['title']], $res);
        }

        // Initial AI System Prompt if not exists
        if (!Setting::getValue('ai_system_prompt')) {
            Setting::setValue('ai_system_prompt', "Kamu adalah asisten virtual AI untuk toko jual beli akun game online. Nama toko ini adalah \"GameShop\".\n\n## Panduan Umum:\n- Jawab SELALU dalam Bahasa Indonesia.\n- Bersikap ramah, sopan, dan helpful.\n- Jika pelanggan bertanya tentang akun game yang tersedia, referensikan data di bawah ini secara akurat.\n- JIKA kamu menyarankan akun spesifik dari daftar, kamu WAJIB menambahkan tag rahasia `[ACCOUNT_ID: {ID_AKUN}]` di akhir jawabanmu.");
        }
    }
}
