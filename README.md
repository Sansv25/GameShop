<div align="center">
  <h1>🎮 Sansv GameShop</h1>
  <p>Platform E-Commerce & Katalog Akun Game</p>

  [![Website](https://img.shields.io/badge/Website-sansv--gameshop.store-blue?style=for-the-badge&logo=vercel)](https://sansv-gameshop.store)
  [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
</div>

---

## 👨‍💻 Identitas Developer
Proyek ini dikembangkan oleh **I Made Putra Sanjaya**.

## 🚀 Live URL
Aplikasi ini sudah di-hosting dan dapat diakses secara publik melalui tautan berikut:
**[https://sansv-gameshop.store](https://sansv-gameshop.store)**

## 🏗️ Infrastruktur Hosting & Database

Proyek ini dibangun menggunakan arsitektur modern yang memanfaatkan layanan cloud untuk performa dan keandalan maksimal:

- **Hosting (Railway):** Aplikasi ini di-deploy di [Railway](https://railway.app/). Railway dipilih karena kemudahannya dalam manajemen deployment berkelanjutan (CI/CD), skalabilitas yang baik, dan kemampuannya untuk menjalankan aplikasi Laravel (PHP) beserta antrian (queue) dengan lancar.
- **Database (Supabase PostgreSQL):** Untuk manajemen basis data, proyek ini menggunakan **Supabase** dengan engine **PostgreSQL**. Supabase menyediakan layanan database cloud yang aman, cepat, dan terukur (scalable), cocok untuk menangani transaksi e-commerce, sistem chat *real-time*, dan relasi data yang kompleks.

## ✨ Fitur-Fitur Aplikasi

Aplikasi ini memiliki fitur lengkap baik untuk pengguna (pembeli) maupun pengelola (admin):

1. **Katalog Akun Game:** Menampilkan daftar akun game yang dijual dengan deskripsi dan harga.
2. **Sistem Autentikasi:** Fitur Login, Registrasi, dan Verifikasi Email demi keamanan akun pengguna.
3. **Manajemen Wishlist:** Pengguna dapat menyimpan akun game yang mereka minati ke dalam daftar keinginan (wishlist).
4. **Sistem Pemesanan (Order):** Alur *checkout* untuk memproses pesanan dengan lancar.
5. **Live Chat & Chatbot AI:** 
   - Sistem chat terintegrasi bagi pengguna untuk berinteraksi dengan penjual/admin.
   - Dilengkapi dengan bot/AI untuk menangani pertanyaan otomatis, fitur penawaran harga (*offer price*), dan *handover* ke admin manusia.
6. **Panel Admin Terpadu:**
   - **Manajemen Akun Game:** CRUD (Create, Read, Update, Delete) akun game yang dijual.
   - **Manajemen Chat:** Admin dapat membaca dan membalas pesan dari pelanggan.
   - **Pengaturan AI:** Konfigurasi untuk menyalakan/mematikan chatbot.
   - **Sistem Broadcast:** Admin dapat mengirim pesan siaran ke banyak pengguna sekaligus.
7. **Pengaturan Pengguna (User Settings):** Pengguna dapat mengelola profil, mengganti password, dan mengubah preferensi tampilan aplikasi.

## 🎨 Teknologi Tampilan (Frontend)

Antarmuka pengguna (UI) dibangun agar terlihat modern, interaktif, dan responsif menggunakan teknologi berikut:

- **Blade Templating Engine:** Sistem template bawaan Laravel yang kuat dan dinamis.
- **Tailwind CSS v4:** Framework CSS *utility-first* untuk merancang tampilan yang modern dan sepenuhnya responsif (*mobile-friendly*).
- **Alpine.js & Alpine AJAX:** Digunakan untuk memberikan interaktivitas pada komponen (seperti modal, dropdown, dan form tanpa *reload* halaman) dengan sintaks yang sangat ringan.
- **GSAP (GreenSock):** Library JavaScript animasi untuk memberikan transisi dan pergerakan elemen antarmuka yang halus dan profesional.
- **FilePond:** Library canggih untuk sistem upload gambar *drag-and-drop* dengan fitur *preview* dan *resize* gambar langsung di *browser*.

## 🛠️ Panduan Instalasi Lokal

Jika Anda ingin menjalankan proyek ini di lingkungan lokal (komputer Anda), ikuti langkah-langkah berikut:

### Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL (jika menggunakan lokal) atau akses ke Supabase

### Langkah-Langkah

1. **Clone Repository**
   ```bash
   git clone <url-repo-anda>
   cd crud
   ```

2. **Instalasi Dependencies PHP (Backend)**
   ```bash
   composer install
   ```

3. **Instalasi Dependencies Node.js (Frontend)**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file konfigurasi bawaan dan sesuaikan nilainya:
   ```bash
   cp .env.example .env
   ```
   > Buka file `.env` dan pastikan untuk mengisi variabel penting seperti:
   > - `DB_CONNECTION=pgsql`
   > - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Isi dengan kredensial Supabase Anda)
   > - Kredensial lain seperti SMTP untuk Email (Resend) atau API Key untuk Chatbot AI jika ada.

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi dan Seeding Database**
   Jalankan perintah ini untuk membangun struktur tabel di database Supabase/Lokal Anda beserta data *dummy* awal:
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan Development Server**
   Anda perlu menjalankan dua perintah di terminal yang berbeda (atau gunakan command bawaan `npm run dev` yang sudah menggunakan *concurrently*):
   ```bash
   # Terminal 1: Menjalankan Laravel Server, Vite, dan Queue
   npm run dev
   ```
   *Aplikasi kini dapat diakses melalui `http://localhost:8000`.*

---
*Dokumentasi ini di-generate untuk memudahkan pemahaman terhadap arsitektur dan fungsionalitas proyek Sansv GameShop.*
