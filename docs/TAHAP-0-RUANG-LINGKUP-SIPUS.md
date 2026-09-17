# Tahap 0 — Ruang Lingkup dan Keputusan Dasar SIPUS

Dokumen ini menjadi baseline implementasi SIPUS. Keputusan di bawah dipakai sebagai aturan awal sampai ada perubahan yang disepakati dan dicatat di dokumen.

## 1. Tujuan MVP

SIPUS versi pertama harus memungkinkan Admin perpustakaan untuk:

- Mengelola data anggota, kelas, buku, eksemplar, dan inventaris.
- Mencatat kunjungan perpustakaan.
- Memproses peminjaman dan pengembalian buku.
- Menghitung dan mencatat denda.
- Melihat dashboard operasional.
- Membuat laporan dan export PDF/XLSX.

Guru dan siswa dapat login, mencari ketersediaan buku, serta melihat data pribadi, kunjungan, peminjaman, dan denda masing-masing.

## 2. Role dan Batas Akses

| Role | Fungsi utama | Batasan |
|---|---|---|
| Admin | Mengelola seluruh data dan transaksi perpustakaan | Tidak ada batas modul dalam MVP |
| Guru | Anggota perpustakaan | Tidak dapat mengubah master data atau memproses transaksi |
| Siswa | Anggota perpustakaan | Tidak dapat mengubah master data atau memproses transaksi |

Staff perpustakaan memakai role Admin pada MVP. Role `petugas` dapat dibuat kemudian apabila diperlukan pemisahan antara petugas operasional dan administrator sistem.

## 3. Modul MVP

### Wajib untuk rilis pertama

1. Login, logout, profil, role, dan permission.
2. Layout responsif dan komponen UI dasar.
3. Tahun ajaran dan kelas.
4. Anggota siswa, guru, dan akun admin.
5. Jenis buku, penerbit, pengarang.
6. Buku dan eksemplar buku.
7. Inventaris perpustakaan.
8. Kunjungan.
9. Peminjaman dan pengembalian.
10. Denda dan pembayaran denda.
11. Dashboard admin, guru, dan siswa.
12. Pencarian ketersediaan buku.
13. Laporan utama dan export PDF/XLSX.
14. Log aktivitas penting dan backup database.

### Ditunda setelah MVP

- Reservasi atau antrean buku.
- Pengajuan peminjaman mandiri oleh siswa atau guru.
- Notifikasi email, WhatsApp, atau push notification.
- Barcode scanner dan kartu anggota digital.
- Multi-cabang perpustakaan.
- Integrasi dengan sistem akademik sekolah.
- Denda otomatis melalui payment gateway.

Fitur yang ditunda tidak boleh mengubah struktur transaksi MVP tanpa migration dan keputusan baru.

## 4. Keputusan Alur Operasional

### Peminjaman

- Peminjaman diproses dan disimpan oleh Admin.
- Guru dan siswa dapat melihat katalog dan ketersediaan, tetapi belum dapat membuat transaksi sendiri.
- Satu transaksi dapat berisi beberapa eksemplar buku.
- Eksemplar hanya dapat dipinjam jika statusnya `tersedia`.
- Anggota harus berstatus aktif.
- Anggota tidak boleh melewati batas jumlah buku yang dikonfigurasi.
- Anggota dengan denda yang memblokir transaksi tidak dapat meminjam sampai status denda berubah sesuai aturan.

### Pengembalian

- Pengembalian diproses oleh Admin.
- Pengembalian sebagian diperbolehkan.
- Sistem menghitung keterlambatan berdasarkan `batas_kembali`.
- Admin memeriksa kondisi eksemplar saat kembali.
- Eksemplar kembali menjadi `tersedia`, `rusak`, atau `hilang`.
- Transaksi berubah menjadi `selesai` setelah seluruh eksemplar dikembalikan.

### Denda

- Denda keterlambatan dihitung per hari.
- Denda kehilangan dan kerusakan mengikuti nominal pada Pengaturan.
- Denda dapat berstatus `belum_lunas`, `sebagian`, atau `lunas`.
- Pembayaran denda dicatat oleh Admin beserta waktu dan pelakunya.
- Aturan apakah denda memblokir peminjaman disimpan sebagai konfigurasi.

## 5. Nilai Awal yang Dapat Diubah Admin

Nilai berikut menjadi baseline saat instalasi dan harus disimpan di `school_settings` atau tabel konfigurasi yang setara:

| Pengaturan | Nilai awal |
|---|---:|
| Lama pinjam siswa | 7 hari |
| Lama pinjam guru | 14 hari |
| Batas buku siswa | 2 buku |
| Batas buku guru | 5 buku |
| Denda keterlambatan | Rp1.000 per hari per eksemplar |
| Denda buku hilang | Diatur Admin |
| Denda kerusakan | Diatur Admin |
| Blokir karena denda | Aktif |

Nilai tersebut adalah default aplikasi, bukan nilai tetap sekolah. Admin dapat mengubahnya melalui Pengaturan.

## 6. Istilah Data yang Dipakai

- **Judul buku**: informasi bibliografi, misalnya judul, pengarang, penerbit, dan tahun terbit.
- **Eksemplar**: buku fisik dari suatu judul, memiliki kode inventaris, lokasi, kondisi, dan status sendiri.
- **Anggota**: profil siswa atau guru yang menggunakan layanan perpustakaan.
- **Akun**: kredensial login yang dapat terhubung ke profil anggota.
- **Transaksi**: satu aktivitas peminjaman yang dapat memiliki beberapa detail buku.
- **Detail transaksi**: satu eksemplar buku di dalam transaksi.
- **Kunjungan**: catatan kedatangan anggota ke perpustakaan.
- **Inventaris**: barang perpustakaan selain eksemplar buku.

## 7. Aturan Data Penting

- `users.username`, `members.nomor_anggota`, `books.kode_buku`, `book_copies.kode_inventaris`, dan `loans.kode_transaksi` harus unik.
- Satu judul buku dapat memiliki banyak eksemplar.
- Satu buku dapat memiliki banyak pengarang melalui tabel pivot.
- Buku dan anggota yang memiliki histori tidak dihapus permanen.
- Eksemplar yang sedang dipinjam tidak dapat muncul pada transaksi aktif lain.
- Siswa wajib memiliki kelas aktif; guru tidak wajib memiliki kelas.
- Password selalu disimpan sebagai hash.
- Perubahan transaksi, penghapusan, perubahan status, dan pembayaran denda dicatat di log aktivitas.

## 8. Format Kode Awal

Format dapat diubah Admin, tetapi implementasi awal menggunakan pola berikut:

- Nomor anggota: `SIPUS-YYYY-NNNNN`.
- Kode inventaris buku: `BK-YYYY-NNNNN`.
- Kode inventaris barang: `INV-YYYY-NNNNN`.
- Kode transaksi: `TRX-YYYYMM-NNNNN`.

Nomor dibuat oleh server dan tidak boleh bergantung pada input pengguna.

## 9. Kriteria Selesai Tahap 0

Tahap 0 dianggap selesai apabila:

- Role dan batas akses sudah ditetapkan.
- Daftar modul MVP sudah ditetapkan.
- Alur peminjaman dan pengembalian sudah ditetapkan.
- Aturan denda dan nilai default sudah tersedia.
- Istilah judul buku dan eksemplar sudah dibedakan.
- Field utama dan relasi sudah dituangkan dalam [ERD SIPUS](ERD-SIPUS.md).
- Tidak ada keputusan yang menghalangi pembuatan migration dan permission.

## 10. Dasar Masuk ke Tahap 1

Setelah baseline ini digunakan, pekerjaan berikutnya adalah Tahap 1: menyiapkan project Laravel, konfigurasi environment, koneksi database, Git, dan build frontend. Jika ada perubahan aturan setelah tahap ini, perubahan dicatat sebagai revisi agar migration, permission, dan alur transaksi tetap sinkron.
