# DOCUMENTATION: OPERATIONAL MANUAL AND SYSTEM ARCHITECTURE

## ❖ SECTION 1: SYSTEM OVERVIEW

Platform ini merupakan sistem tata kelola administrasi gampong/desa digital yang mengintegrasikan layanan mandiri warga (*Citizen Self-Service*), manajemen birokrasi internal, dan asisten virtual bertenaga Kecerdasan Buatan (AI).

### 1.1 Core Purpose

Tujuan utama dari sistem ini adalah mereduksi birokrasi konvensional di tingkat pemerintahan desa melalui otomatisasi siklus pengajuan dokumen dan pelayanan informasi publik. Sistem menyediakan portal bagi warga untuk mengajukan surat keterangan secara mandiri, sekaligus menyediakan panel kontrol bagi aparatur desa untuk memverifikasi, menandatangani, dan menerbitkan dokumen resmi secara digital.

### 1.2 Target Users

* **Warga / Masyarakat Umum:** Pengguna akhir yang memanfaatkan portal publik untuk memantau status pengajuan, membaca panduan layanan, serta melakukan konsultasi interaktif via AI.


* **Aparatur / Pejabat Desa:** Pengguna administratif yang memvalidasi permohonan surat, mengelola templat dokumen, dan memantau statistik aktivitas desa.


* **Sistem Administrator:** Pengelola sistem yang memiliki otoritas penuh terhadap konfigurasi hak akses pengguna, integrasi API, dan pemeliharaan server.



---

## ❖ SECTION 2: SYSTEM ARCHITECTURE & TECH STACK

Aplikasi ini dibangun menggunakan arsitektur hibrida modern dengan memisahkan wilayah administrasi reaktif dan area publik SPA (*Single Page Application*).

```
                                  +-----------------------+
                                  |     CLIENT BROWSER    |
                                  +-----------+-----------+
                                              |
                                              | HTTP Requests / Inertia Router
                                              v
                                  +-----------+-----------+
                                  |    LARAVEL CORE FRAME |
                                  +-----+-----------+-----+
                                        |           |
             +--------------------------+           +--------------------------+
             |                                                                 |
             v                                                                 v
+------------+------------+                                       +------------+------------+
|  PUBLIC PORTAL (SPA)    |                                       |   ADMIN DASHBOARD (TALL) |
+-------------------------+                                       +-------------------------+
| - React.js Layer        |                                       | - Filament PHP Engine   |
| - Inertia.js Bridge     |                                       | - Livewire Reactivity   |
| - Tailwind CSS Design   |                                       | - Alpine.js Handlers    |
+------------+------------+                                       +------------+------------+
             |                                                                 |
             +--------------------------+           +--------------------------+
                                        |           |
                                        v           v
                                  +-----------+-----------+
                                  |   DATABASE / ENGINE   |
                                  +-----------+-----------+
                                  | - MySQL / PostgreSQL  |
                                  | - Gemini AI API       |
                                  | - Redis Queue Worker  |
                                  +-----------------------+

```

### 2.1 Backend Layer (Core)

* **Laravel Framework:** Bertindak sebagai penyedia logika bisnis, manajemen otentikasi tingkat sesi, ORM (Eloquent), pencatatan antrean tugas (*Queue Work*), dan keamanan sistem.



### 2.2 Administrative Layer (Admin Panel)

* **Filament PHP:** Lapisan administrasi berbasis Livewire dan Alpine.js untuk membangun antarmuka CRUD (*Create, Read, Update, Delete*) secara cepat, responsif, dan reaktif tanpa membebani sisi klien.



### 2.3 Public & Citizen Layer (Frontend)

* **React.js & Vue.js (Hybrid Component System):** Lapisan presentasi publik menggunakan kombinasi komponen reaktif untuk memberikan pengalaman pengguna yang mulus.


* **Inertia.js:** Bertindak sebagai jembatan (*bridge*) yang menghubungkan pengontrol (*controller*) Laravel dengan komponen React di sisi publik tanpa memerlukan arsitektur terpisah berbasis REST API konvensional.


* **Tailwind CSS & Shadcn-Vue:** Fondasi desain utilitas untuk menghadirkan antarmuka pengguna yang bersih, modern, dan adaptif terhadap gawai.



### 2.4 Intelligence Layer (AI)

* **Google Gemini Pro API:** Mesin utama pembentuk kecerdasan buatan untuk menggerakkan asisten obrolan yang kontekstual dan adaptif terhadap informasi regulasi desa.



---

## ❖ SECTION 3: CORE FUNCTIONAL FEATURES

### 3.1 Portal Publik & Pelayanan Warga

* **Formulir Pengajuan Surat Mandiri (`FormulirPengajuan.vue`):** Antarmuka ramah pengguna bagi warga untuk mengajukan berbagai jenis surat keterangan dengan mengunggah prasyarat dokumen yang diperlukan.


* **Dasbor Warga (`Dashboard.vue`):** Panel pelacakan seketika untuk memantau apakah surat yang diajukan berada dalam status *Pending*, *Diproses*, *Disetujui*, atau *Ditolak*.


* **Indeks Panduan Biografi (`Panduan.vue`):** Pusat dokumentasi regulasi dan syarat operasional kepengurusan dokumen kependudukan (KK, KTP, Surat Pindah, dll).



### 3.2 Asisten Virtual Ceurdas AI (`AiChatWidget.vue` / `ChatWidget.vue`)

* **Chatbot Interaktif Kontekstual:** Integrasi dengan `GeminiService.php` memungkinkan AI menjawab pertanyaan warga mengenai tatacara administrasi, lokasi fasilitas desa, hingga profil pejabat desa secara otomatis.


* **Pencatatan Riwayat (`ChatHistory.php`):** Menyimpan log interaksi pengguna untuk melatih pemahaman model dan analisis kebutuhan informasi warga.



### 3.3 Sistem Administrasi Pemerintahan (Filament Dashboard)

* **Manajemen Template Surat (`SuratTemplateResource.php`):** Ekosistem pembuatan struktur dinamis fail surat resmi menggunakan sintaksis pengodean teks bodi dokumen resmi (`0012_restructure_aturan_pakar_linter.sql`, `default_surat.blade.php`).


* **Orkestrasi Permohonan Surat (`SuratRequestResource.php`):** Antarmuka terpusat bagi kepala desa atau sekretaris untuk memverifikasi lampiran warga, menulis catatan penolakan, atau melakukan persetujuan penerbitan dokumen.


* **Manajemen Keanggotaan (`UserResource.php`):** Pengendalian penuh akun pengguna, otorisasi hak akses, dan manajemen identitas kependudukan warga.


* **Manajemen Pejabat (`PejabatDesaResource.php`):** Registrasi dan pembaruan berkala aparatur aktif gampong beserta bagan strukturalnya.



---

## ❖ SECTION 4: DATA FLOW & SYSTEM PIPELINES

### 4.1 Alur Pengajuan Surat (Surat Request Pipeline)

```
[ Warga ] ---> Mengisi Form & Unggah Berkas ---> Model: SuratRequest (Status: Pending)
                                                              |
                                                              v
[ Admin ] ---> Filament Dashboard ---> Validasi Berkas ---+
                                                          |
             +--------------------------------------------+
             |
             +---> [ DITOLAK ] ---> Tulis Alasan ---> Update Status: Ditolak ---> Notifikasi
             |
             +---> [ DISETUJUI ] --> System Engine ---> Generate File (PDF Template)
                                                              |
                                                              v
                                                   Update Status: Selesai
                                                              |
                                                              v
                                                 Queue: ProcessSuratQueue ---> Kirim ke Warga

```

1. Warga mengisi data melalui komponen `FormulirPengajuan.vue`. Data dikirim melalui jembatan Inertia ke backend dan disimpan ke tabel `surat_requests` dengan status awal `pending`.


2. Aparatur desa menerima notifikasi pada modul `LatestSurat` di dasbor admin Filament.


3. Aparatur melakukan evaluasi dokumen. Jika data tidak selaras, status diubah menjadi `ditolak` disertai input alasan penolakan. Jika valid, aparatur menekan tombol setujui.


4. Sistem memicu *engine* perakit dokumen (`DocumentController.php`) untuk menyuntikkan data kependudukan warga ke dalam struktur kode `default_surat.blade.php` atau `surat_keputusan.blade.php`.


5. Setelah dokumen berhasil dirakit, status diperbarui menjadi `selesai`. Perintah dialihkan ke sistem latar belakang melalui antrean perintah `ProcessSuratQueue.php` untuk memproses dokumen akhir tanpa mengganggu performa server utama.



### 4.2 Alur Konsultasi AI (AI Chatbot Pipeline)

1. Pengguna mengirimkan pesan melalui widget komponen `AiChatWidget.vue`.


2. Permintaan ditangkap oleh `AiChatController.php` dan dikirimkan ke kelas `GeminiService.php`.


3. `GeminiService.php` memuat konfigurasi dari `config/gemini.php`, menggabungkan basis pengetahuan lokal desa (*system prompt*), dan mengirimkannya ke API Google Gemini.


4. Respons dari API disimpan ke dalam tabel `chat_histories` dan `ai_chats` untuk pencatatan riwayat sebelum dikembalikan ke komponen antarmuka pengguna.



---

## ❖ SECTION 5: DATABASE SCHEMA & MAPS

Skema data utama relasional dikonfigurasi melalui migrasi sistem (`2025_11_24_create_desa_system_tables.sql`) dengan rancangan entitas sebagai berikut:

### 5.1 Tabel: `users`

Menampung data kependudukan dan hak akses otentikasi.

* `id` (BigInt, PK)


* `name` (Varchar) – Nama lengkap warga.


* `email` (Varchar, Unique) – Alamat surel.


* `nik` (Varchar, Unique) – Nomor Induk Kependudukan.


* `role` (Varchar) – Otoritas pengguna (`admin`, `warga`, `pejabat`).


* `password` (Varchar) – Hash kata sandi.



### 5.2 Tabel: `surat_templates`

Menyimpan format baku dokumen resmi desa.

* `id` (BigInt, PK)


* `nama_surat` (Varchar) – Contoh: Surat Keterangan Kurang Mampu.


* `kode_surat` (Varchar) – Penomoran regulasi dokumen.


* `body_text` (LongText) – Konten utama surat beserta variabel penampung (*placeholder*).



### 5.3 Tabel: `surat_requests`

Mencatat seluruh transaksi permohonan dokumen warga.

* `id` (BigInt, PK)


* `user_id` (BigInt, FK -> `users.id`) – Identitas pemohon.


* `surat_template_id` (BigInt, FK -> `surat_templates.id`) – Jenis surat yang diminta.


* `keterangan` (Text) – Alasan pengajuan surat.


* `status` (Varchar) – Status sirkulasi dokumen (`pending`, `proses`, `selesai`, `ditolak`).


* `file_path` (Varchar, Nullable) – Jalur unduhan berkas PDF setelah disetujui.



---

## ❖ SECTION 6: LOCAL INSTALLATION & DEVELOPMENT SETUP

### 6.1 Prasyarat Sistem (System Prerequisites)

* PHP ^8.2


* Composer v2


* Node.js v18.x / v20.x & NPM


* Database Engine (MySQL / MariaDB)


* Google Gemini API Key



### 6.2 Prosedur Instalasi Lokal (Step-by-Step Installation)

1. Lakukan klon terhadap repositori proyek ini ke komputer lokal.


2. Duplikasikan fail konfigurasi lingkungan dengan mengeksekusi perintah berikut di terminal:


```bash
cp .env.example .env

```



```
3.  Buka fail `.env` dan sesuaikan kredensial pangkalan data Anda
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=username_database_anda
    DB_PASSWORD=password_database_anda

```

4. Masukkan token rahasia Google Gemini API Anda pada fail `.env`:


```env
GEMINI_API_KEY=titip_api_key_gemini_anda_di_sini

```



```
5.  Instal seluruh dependensi komponen pustaka backend PHP melalui Composer:
    ```bash
    composer install

```

6. Bangkitkan kunci enkripsi aplikasi Laravel:


```bash
php artisan key:generate

```



```
7.  Jalankan migrasi database untuk menyusun struktur tabel beserta data awal (*seeder*):
    ```bash
php artisan migrate --seed

```

8. Instal dependensi modul JavaScript sisi klien:


```bash

```



npm install

```
9.  Lakukan kompilasi aset frontend menggunakan Vite:
    ```bash
    npm run build

```

10. Tautkan direktori penyimpanan file lokal agar dapat diakses oleh publik:


```bash

```



php artisan storage:link

```
11. Jalankan peladen lokal untuk menguji aplikasi:
    ```bash
php artisan serve

```

Aplikasi dapat diakses melalui `http://localhost:8000`. Dashboard administrasi Filament dapat diakses melalui rute `/admin`.

---

## ❖ SECTION 7: ROADMAP & FUTURE DEVELOPMENT

Untuk meningkatkan ketahanan, skalabilitas, dan fungsi aplikasi ini di masa depan, arah pengembangan berikutnya diprioritaskan pada aspek berikut:

### 7.1 Implementasi Tanda Tangan Elektronik (TTE) tersertifikasi

* Mengintegrasikan sistem persetujuan surat dengan API BSrE (Badan Siber dan Sandi Negara) untuk menyematkan *Digital Signature* resmi berbentuk QR Code pada fail PDF yang diterbitkan, guna menjamin keabsahan dokumen hukum dan mencegah pemalsuan surat desa.



### 7.2 Integrasi WA Gateway untuk Pengiriman Notifikasi Otomatis

* Mengembangkan layanan pemberitahuan berbasis WhatsApp menggunakan pustaka pengirim pesan otomatis. Fitur ini diprogram untuk mengirimkan notifikasi seketika kepada warga saat status pengajuan surat mereka berubah dari `proses` menjadi `selesai` atau `ditolak`.



### 7.3 Optimalisasi AI Berbasis Retrieval-Augmented Generation (RAG)

* Meningkatkan kemampuan modul `GeminiService.php` agar tidak hanya merespons pertanyaan umum, melainkan mampu membaca pangkalan data dokumen PDF regulasi spesifik desa, anggaran belanja, atau peraturan daerah secara dinamis, sehingga meminimalkan tingkat halusinasi informasi pada kecerdasan buatan.



### 7.4 Modul Statistik Kependudukan Tingkat Lanjut (Advanced Analytics Dashboard)

* Membangun bagan analisis kependudukan interaktif berskala besar pada dasbor utama Filament. Modul ini dirancang untuk memetakan diagram piramida usia warga, grafik tingkat kemiskinan, pemetaan sebaran bantuan sosial, dan pelaporan otomatis jenis surat yang paling sering diajukan setiap bulannya.



```***
