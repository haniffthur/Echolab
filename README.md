VMS (Visitor Management System) - Laravel 10
Proyek ini adalah sistem manajemen visitor dan karyawan sederhana yang dibangun menggunakan Laravel 10. Aplikasi ini dirancang untuk berjalan di lingkungan jaringan lokal (LAN) tanpa memerlukan koneksi internet, terintegrasi dengan mesin tapping kartu.

Fitur Utama
API Backend: Endpoint untuk menerima data tap dari mesin hardware.

Dashboard & Monitor Real-time: Halaman untuk memantau aktivitas tap dan status orang di dalam gedung secara live.

Manajemen Data (CRUD):

Manajemen Gate (Terminal Mesin)

Manajemen Karyawan

Manajemen Kartu (Karyawan & Tamu)

Manajemen Akun Login (Admin)

Laporan Aktivitas: Halaman untuk melihat riwayat semua aktivitas tap.

Sistem Login: Otentikasi untuk administrator dashboard.

Tanpa Internet: Dirancang untuk berjalan sepenuhnya offline di jaringan lokal.

Persyaratan Sistem
PHP >= 8.1

Composer

Node.js & NPM

Database (MySQL direkomendasikan)

Server Lokal (XAMPP, Laragon, dll.)

Langkah-langkah Instalasi
Clone Repositori

git clone [URL_REPOSITORY_ANDA]
cd [NAMA_FOLDER_PROYEK]

Instal Dependensi

composer install
npm install

Konfigurasi Environment

Salin file .env.example menjadi .env.

cp .env.example .env

Buat application key baru.

php artisan key:generate

Buka file .env dan atur koneksi database Anda (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Migrasi & Seeding Database

Jalankan perintah ini untuk membuat semua tabel dan mengisi data admin pertama.

php artisan migrate --seed

Perintah ini akan membuat satu akun admin default:

Email: admin@vms.test

Password: password

Compile Aset Frontend

npm run dev

Jalankan Server Development

php artisan serve

Aplikasi sekarang bisa diakses di http://127.0.0.1:8000.

Konfigurasi API
Endpoint utama untuk mesin tapping adalah:

GET /api/tap

Parameter yang Diterima:

Untuk Tap Biasa (Masuk/Keluar):

cardno: Nomor unik kartu.

termno: Nomor terminal mesin/gate yang sudah terdaftar.

IO: Arah tap (1 untuk Masuk, 0 untuk Keluar).

Contoh: http://[IP_SERVER]/api/tap?cardno=12345&termno=001&IO=1

Untuk Registrasi Otomatis (Tap Pertama Karyawan Baru):

cardno: Nomor unik kartu baru.

name: Nama lengkap karyawan.

Contoh: http://[IP_SERVER]/api/tap?cardno=KARTU-BARU-01&name=Budi%20Santoso