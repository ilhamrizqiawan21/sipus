# SIPUS

SIPUS adalah aplikasi sistem informasi perpustakaan dengan antarmuka bergaya dashboard dan katalog koleksi yang dirancang mengikuti desain Figma di folder `desain_figma`.

## Ikhtisar Proyek

Aplikasi ini berfungsi sebagai sistem manajemen perpustakaan sekolah dengan fitur dasar:
- Dashboard ringkas untuk statistik koleksi dan peminjaman.
- Katalog buku untuk melihat daftar buku dan detail buku.
- Manajemen anggota perpustakaan.
- Autentikasi pengguna dengan login dan registrasi.
- Integrasi tampilan responsif menggunakan Bootstrap dan Vite.

## Spesifikasi Teknis

- Bahasa utama: PHP
- Framework backend: Laravel 13.x
- Frontend: Blade, Bootstrap, CSS kustom ringan, Vite
- Pengelola paket: Composer, npm
- Database: MySQL / SQLite / database yang didukung Laravel
- Versi PHP yang dibutuhkan: `^8.3`

## Bahasa dan Teknologi yang Digunakan

- PHP 8.3
- Laravel 13.x
- Blade templating
- Bootstrap 5
- Vite 8.x
- JavaScript modern (ESM)
- HTML5, CSS3

## Fitur Utama

- Dashboard admin dengan statistik buku, anggota, dan transaksi.
- Katalog buku lengkap dengan pencarian, status, dan detail buku.
- Daftar anggota perpustakaan dengan pencarian dan detail anggota.
- Login / registrasi pengguna.
- Halaman detail buku dan anggota.
- Endpoint JSON untuk buku dan anggota (`wantsJson()` support).
- Halaman layout utama yang meniru struktur desain UI Figma.

## Menu dan Navigasi

Menu utama aplikasi tersedia di sidebar dan mencakup:

- Dashboard
- Katalog Buku
- Eksemplar
- Pengadaan
- Anggota
- Peminjaman
- Pengembalian
- Laporan
- Pengaturan

Selain sidebar, ada juga:
- Input pencarian global di topbar
- Tombol notifikasi
- Profil pengguna di topbar

## Keunggulan

- Struktur aplikasi yang sudah hadir dengan arsitektur Laravel standar.
- Desain UI konsisten dengan tampilan dashboard dan kartu statistik.
- Dukungan pagination pada halaman daftar buku dan anggota.
- Komponen halaman siap dikembangkan ke fitur sirkulasi penuh.
- Mudah disesuaikan untuk sekolah atau perpustakaan kecil.

## Persyaratan Sistem

- PHP 8.3 atau lebih baru
- Composer
- Node.js & npm
- Database MySQL / SQLite / PostgreSQL yang kompatibel dengan Laravel

## Cara Install

1. Clone repositori ke mesin lokal:

```bash
git clone <repository-url> sipus
cd sipus
```

2. Install dependensi PHP:

```bash
composer install
```

3. Install dependensi frontend:

```bash
npm install
```

4. Salin file lingkungan dan buat `APP_KEY`:

```bash
cp .env.example .env
php artisan key:generate
```

5. Konfigurasi database di file `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipus
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migrasi database:

```bash
php artisan migrate
```

7. Bangun aset frontend:

```bash
npm run build
```

## Cara Menjalankan

Untuk menjalankan aplikasi secara lokal:

```bash
php artisan serve
```

Untuk development dengan Vite:

```bash
npm run dev
```

## Perintah Tambahan

- `composer run setup` — install dependensi, generate key, migrate, dan build aset.
- `composer run dev` — jalankan server Laravel dengan watch dan Vite secara bersamaan.
- `composer run test` — jalankan test Laravel.

## Struktur Utama Aplikasi

- `app/Http/Controllers` — logika controller untuk buku, anggota, dan autentikasi.
- `app/Models` — model `Book`, `Member`, `User`.
- `resources/views` — tampilan Blade untuk dashboard, buku, anggota, login, register.
- `resources/css` — gaya aplikasi.
- `routes/web.php` — definisi rute aplikasi.
- `database/migrations` — migrasi standar Laravel, termasuk tabel `users`.

## Catatan Tambahan

- Folder `desain_figma` menyimpan desain UI dan aset referensi; ini menjadi sumber tampilan yang dapat diadaptasi.
- Beberapa menu di sidebar masih merupakan placeholder dan dapat dikembangkan menjadi fitur pengelolaan sirkulasi lebih lanjut.

---

## Lisensi

Proyek ini menggunakan lisensi MIT.
