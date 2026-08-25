Dokumentasi Project Sistem Parkir (Laravel Filament)
Dokumentasi ini berisi panduan instalasi, konfigurasi, dan alur kerja aplikasi sistem manajemen parkir berbasis Laravel 12 dan Filament v4.

🛠️ Persyaratan Sistem
Pastikan perangkat kamu memenuhi spesifikasi berikut sebelum memulai:

PHP >= 8.2 (dengan ekstensi pdo_mysql, mbstring, bcmath, xml)

Composer >= 2.x

MySQL / MariaDB

Node.js & NPM (Opsional, untuk kompilasi aset frontend)

🚀 Langkah Instalasi (Setup Project)
Ikuti langkah-langkah di bawah ini untuk menginstal project di lingkungan lokal.

1. Clone Repository
Bash
git clone <URL_REPOSITORY_KAMU>
cd <NAMA_FOLDER_PROJECT>
2. Install Dependency PHP
Bash
composer install
3. Salin Environment File
Salin file .env.example menjadi .env:

Bash
cp .env.example .env
(Untuk pengguna Windows PowerShell: copy .env.example .env)

4. Konfigurasi Database
Buka file .env menggunakan editor teks, lalu sesuaikan kredensial database kamu:

Cuplikan kode
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_parkir
DB_USERNAME=root
DB_PASSWORD=
5. Generate Application Key
Bash
php artisan key:generate
6. Jalankan Migration & Seeder
Jalankan migrasi tabel beserta data awal (Master Tarif dan User Admin):

Bash
php artisan migrate --seed
7. Buat User Panel Filament (Jika belum ada via Seeder)
Jika perlu membuat user admin baru untuk login ke dashboard Filament:

Bash
php artisan make:filament-user
8. Optimize & Clear Cache
Pastikan cache aplikasi bersih sebelum menjalankan server:

Bash
php artisan optimize:clear
9. Jalankan Development Server
Bash
php artisan serve
Akses panel admin melalui browser di: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

📋 Fitur Utama & Alur Kerja Aplikasi
1. Master Tarif Parkir
Digunakan untuk mengelola jenis kendaraan beserta skema tarif (per jam / flat).

Memiliki validasi tarif minimal untuk mencegah kesalahan input nominal 0 atau minus.

2. Pintu Masuk (Buat Karcis)
Petugas memilih Jenis Kendaraan dan menginput Plat Nomor.

Sistem membuat Kode Karcis unik dan mencatat Waktu Masuk secara otomatis.

Fitur Preview Barcode / Karcis tersedia untuk dicetak saat kendaraan masuk.

Status transaksi awal diset menjadi MASUK.

3. Pintu Keluar (Bayar & Struk)
Tabel pada halaman Bayar menampilkan daftar kendaraan yang berstatus MASUK.

Petugas mengklik tombol Bayar di baris kendaraan terkait untuk membuka modal pembayaran.

Durasi dan total biaya parkir dihitung secara otomatis.

Terdapat validasi ketat pada Uang Bayar (wajib lebih besar atau sama dengan Total Biaya).

Setelah pembayaran disubmit:

Record transaksi disimpan ke tabel pintu_keluars.

Status transaksi di pintu_masuks diperbarui menjadi SELESAI.

Kendaraan otomatis hilang dari daftar transaksi aktif.

Pop-up notifikasi menyediakan Tombol Cetak Struk (Struk thermal 80mm yang otomatis memicu dialog print).

⚡ Troubleshooting & Optimasi Performa
Jika aplikasi mengalami lag atau error di lingkungan lokal, jalankan perintah berikut:

Clear All Caches:

Bash
php artisan optimize:clear
Optimize Class Loading & Cache Views (Production/Dev):

Bash
composer dump-autoload -o
php artisan config:cache
php artisan route:cache
php artisan view:cache