# SIPUS (Sistem Pengelolaan Perpustakaan Sekolah)

## 1. Tujuan dan Teknologi

SIPUS adalah aplikasi administrasi perpustakaan sekolah untuk mengelola koleksi buku, anggota, inventaris, kunjungan, peminjaman, pengembalian, denda, dan laporan.

- Laravel sebagai backend.
- TypeScript, Vue, dan Inertia sebagai frontend.
- MySQL atau MariaDB sebagai database utama.
- UI administrasi bersih, putih, responsif, dengan hijau sebagai warna aksen.
- Layout memiliki sidebar buka/tutup, hamburger button, headbar, dan area konten.

## 2. Role dan Hak Akses

### Admin

Admin memiliki akses penuh: CRUD buku, eksemplar, penerbit, pengarang, jenis buku, kelas, inventaris, siswa, guru, dan akun admin; import anggota XLSX; pengelolaan kunjungan, peminjaman, pengembalian, denda, laporan, log aktivitas, dan pengaturan sekolah.

### Guru dan Siswa

Guru dan siswa menggunakan aplikasi sebagai anggota perpustakaan. Mereka dapat melihat dashboard pribadi, mencari buku, melihat ketersediaan, riwayat peminjaman, kunjungan, denda, serta mengubah username, password, dan foto profil.

Staff perpustakaan menggunakan role Admin. Jika kelak diperlukan pemisahan, role `petugas` dapat ditambahkan untuk operasi harian tanpa akses pengaturan sistem.

| Modul | Admin | Guru | Siswa |
|---|---|---|---|
| Dashboard | Statistik dan pengelolaan | Dashboard pribadi | Dashboard pribadi |
| Buku dan eksemplar | CRUD | Lihat ketersediaan | Lihat ketersediaan |
| Penerbit, pengarang, jenis | CRUD | Filter pencarian | Filter pencarian |
| Inventaris | CRUD | Tidak dapat mengubah | Tidak dapat mengubah |
| Kelas | CRUD | Lihat | Lihat |
| Anggota dan akun | CRUD | Data sendiri | Data sendiri |
| Kunjungan | Catat dan lihat semua | Riwayat sendiri | Riwayat sendiri |
| Peminjaman dan pengembalian | Kelola dan proses | Riwayat sendiri | Riwayat sendiri |
| Laporan | Lihat dan export | Tidak tersedia | Tidak tersedia |
| Pengaturan sekolah | CRUD | Tidak tersedia | Tidak tersedia |
| Profil | Profil sendiri | Profil sendiri | Profil sendiri |

## 3. Modul dan Struktur Data

### 3.1 Buku dan Eksemplar

Data judul buku dipisahkan dari eksemplar. Satu judul dapat memiliki banyak eksemplar dengan kode inventaris berbeda.

#### `books`

- `id` — primary key.
- `kode_buku` — ISBN atau kode katalog, nullable dan unik jika diisi.
- `judul` — wajib.
- `jenis_buku_id` — wajib.
- `penerbit_id` — nullable.
- `tahun_terbit` — nullable.
- `deskripsi` — nullable.
- `cover_path` — nullable.
- `created_at`, `updated_at`, `deleted_at`.

#### `book_copies`

- `id`, `book_id`.
- `kode_inventaris` — wajib dan unik.
- `lokasi_rak` — nullable.
- `kondisi` — `baik`, `rusak_ringan`, `rusak_berat`, `hilang`.
- `status` — `tersedia`, `dipinjam`, `diperbaiki`, `hilang`, `dihapus`.
- `tanggal_masuk`, `harga_perolehan`, `catatan` — nullable.
- `created_at`, `updated_at`, `deleted_at`.

Buku yang pernah dipakai transaksi tidak boleh dihapus permanen. Gunakan soft delete atau status nonaktif.

### 3.2 Penerbit

- `id`, `nama` — wajib dan unik.
- `alamat`, `telepon`, `email`, `website`, `catatan` — nullable.

Penerbit yang masih dipakai buku tidak dapat dihapus tanpa mengganti relasinya.

### 3.3 Pengarang

- `id`, `nama` — wajib.
- `bio`, `catatan` — nullable.

Gunakan tabel pivot `book_author`, karena satu buku dapat memiliki beberapa pengarang.

### 3.4 Jenis Buku

- `id`, `nama` — wajib dan unik.
- `kode` — nullable dan unik.
- `deskripsi` — nullable.
- `aktif` — boolean.

Jenis yang sudah dipakai tidak dihapus permanen; ubah menjadi tidak aktif.

### 3.5 Inventaris Perpustakaan

Jenis awal: kursi, meja baca, rak buku, meja admin, lampu, alat kebersihan, dan ATK. Admin dapat menambah jenis lain.

- `id`.
- `kode_inventaris` — wajib dan unik.
- `nama_barang`, `jenis` — wajib.
- `jumlah` — minimal 1.
- `satuan` — buah, unit, set, atau lainnya.
- `lokasi` — nullable.
- `kondisi` — `baik`, `rusak_ringan`, `rusak_berat`, `hilang`.
- `status` — `aktif`, `diperbaiki`, `dihapus`.
- `tanggal_perolehan`, `harga_perolehan`, `catatan` — nullable.

### 3.6 Kelas

- `id`, `nama`, `tingkat`, `tahun_ajaran_id`.
- `wali_nama` — nullable.
- `aktif` — boolean.

### 3.7 Akun dan Anggota

#### `users`

- `id`.
- `username` — wajib dan unik.
- `password` — disimpan sebagai hash.
- `role` — `admin`, `guru`, atau `siswa`.
- `status` — aktif atau nonaktif.
- `last_login_at`, `created_at`, `updated_at` — nullable atau otomatis.

#### `members`

- `id`, `user_id` — `user_id` nullable untuk anggota tanpa akun.
- `nomor_anggota` — wajib dan unik.
- `jenis_anggota` — `siswa` atau `guru`.
- `nama` — wajib.
- `nis_nip` — nullable dan unik jika diisi.
- `kelas_id` — wajib untuk siswa, nullable untuk guru.
- `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `telepon`, `email`, `foto_path` — nullable.
- `tanggal_daftar` — wajib.
- `status` — aktif atau nonaktif.

Admin dapat CRUD anggota, mengaktifkan atau menonaktifkan akun, dan import XLSX. Template import harus menyediakan nama kolom, contoh baris, field wajib, dan aturan format.

### 3.8 Kunjungan

- `id`, `member_id`.
- `waktu_masuk` — wajib.
- `waktu_keluar` — nullable.
- `keperluan` — membaca, meminjam, mengembalikan, atau lainnya.
- `dicatat_oleh` — admin, nullable untuk pencatatan mandiri.
- `catatan` — nullable.

Data ini menjadi sumber statistik kunjungan harian dan bulanan.

### 3.9 Peminjaman dan Pengembalian

#### `loans`

- `id`.
- `kode_transaksi` — wajib dan unik.
- `member_id` — wajib.
- `tanggal_pinjam`, `batas_kembali` — wajib.
- `tanggal_selesai` — nullable.
- `status` — `draft`, `dipinjam`, `sebagian_kembali`, `selesai`, `terlambat`, `dibatalkan`.
- `dicatat_oleh`, `catatan` — nullable.

#### `loan_items`

- `id`, `loan_id`, `book_copy_id`.
- `tanggal_kembali`, `kondisi_saat_kembali`, `denda_id`, `catatan` — nullable.
- `status` — `dipinjam`, `kembali`, `hilang`, atau `rusak`.

Satu transaksi dapat berisi beberapa eksemplar. Eksemplar hanya dapat dipinjam saat berstatus `tersedia`. Saat dipinjam status menjadi `dipinjam`; saat kembali status menjadi `tersedia`, `rusak`, atau `hilang`.

### 3.10 Denda

- `id`, `loan_item_id`.
- `jenis` — keterlambatan, hilang, atau kerusakan.
- `jumlah` — wajib dan tidak boleh negatif.
- `status` — `belum_lunas`, `sebagian`, atau `lunas`.
- `dibayar_at`, `dibayar_oleh`, `catatan` — nullable.

Denda keterlambatan dihitung dari hari setelah `batas_kembali`. Denda hilang dan rusak mengikuti nilai pada pengaturan.

### 3.11 Pengaturan

Admin dapat mengatur nama sekolah, logo, alamat, jenjang, lama peminjaman guru dan siswa, batas jumlah buku tiap role, denda per hari, denda buku hilang, denda kerusakan, serta format nomor anggota dan transaksi.

## 4. Alur CRUD

Setiap halaman admin memiliki index dengan tabel, pencarian, filter, pagination, ringkasan jumlah data, dan tombol tambah.

1. Admin membuka form create.
2. Sistem memvalidasi field wajib, tipe data, keunikan, dan relasi.
3. Data disimpan dan notifikasi sukses ditampilkan.
4. Halaman detail menampilkan data dan relasi terkait.
5. Edit memakai form yang sama dengan data terisi.
6. Hapus memerlukan konfirmasi dan ditolak jika data masih memiliki histori atau relasi penting.
7. Data historis memakai soft delete atau status nonaktif.

## 5. Alur Peminjaman

1. Admin mencari anggota berdasarkan nomor anggota, nama, atau username.
2. Sistem menampilkan status anggota, pinjaman aktif, dan denda belum lunas.
3. Admin memilih satu atau beberapa eksemplar yang tersedia.
4. Sistem menghitung batas pengembalian dan memeriksa batas jumlah buku.
5. Admin menyimpan transaksi.
6. Sistem membuat kode transaksi, menyimpan detail, dan mengubah status eksemplar menjadi `dipinjam`.
7. Statistik dashboard dan laporan diperbarui.

Anggota nonaktif, anggota yang melewati batas pinjaman, atau anggota yang terkena blokir denda tidak dapat meminjam.

## 6. Alur Pengembalian

1. Admin mencari kode transaksi atau nomor anggota.
2. Sistem menampilkan buku yang masih dipinjam dan batas pengembaliannya.
3. Admin memilih buku yang dikembalikan.
4. Sistem menghitung keterlambatan dan denda.
5. Admin memeriksa kondisi buku.
6. Admin menyimpan pengembalian.
7. Sistem memperbarui status detail transaksi dan eksemplar.
8. Jika semua buku kembali, status transaksi menjadi `selesai`.

Pengembalian sebagian diperbolehkan. Denda tetap terkait dengan eksemplar yang bersangkutan dan dapat dibayar atau ditandai lunas oleh admin.

## 7. Dashboard

### Admin

- Total judul dan eksemplar buku.
- Buku tersedia, dipinjam, rusak, dan hilang.
- Peminjaman hari ini, minggu ini, dan bulan ini.
- Grafik tren peminjaman dan kunjungan.
- Total inventaris.
- Buku paling sering dan paling jarang dipinjam.
- Kunjungan terbaru dan log aktivitas admin.

### Guru dan Siswa

- Peminjaman aktif.
- Buku yang belum dikembalikan.
- Riwayat transaksi terbaru.
- Buku yang paling sering dipinjam oleh anggota tersebut.
- Kunjungan bulan berjalan.
- Denda belum lunas.

## 8. Pencarian Ketersediaan Buku

Guru dan siswa dapat mencari berdasarkan judul, kode atau ISBN, jenis buku, pengarang, penerbit, dan lokasi rak. Hasil menampilkan judul, pengarang, penerbit, jumlah eksemplar, jumlah tersedia, lokasi rak, dan status ketersediaan.

## 9. Laporan

Admin dapat memfilter berdasarkan hari, minggu, bulan, rentang tanggal, kelas, anggota, jenis buku, pengarang, penerbit, dan status transaksi.

Jenis laporan: peminjaman, pengembalian, keterlambatan dan denda, buku paling sering dipinjam, buku jarang dipinjam, anggota, kunjungan, stok dan kondisi buku, serta inventaris. Semua laporan menampilkan periode, filter, waktu pembuatan, dan pembuat laporan. Export tersedia dalam PDF dan XLSX.

## 10. Validasi dan Aturan Umum

- Field wajib diberi penanda di form.
- Nominal tidak boleh negatif.
- Tanggal pengembalian tidak boleh lebih awal dari tanggal peminjaman.
- Kode buku, kode inventaris, kode transaksi, username, dan nomor anggota harus unik.
- Data yang memiliki histori transaksi tidak dihapus permanen.
- Perubahan penting dicatat di log aktivitas.
- Upload gambar dibatasi pada JPG, JPEG, PNG, serta ukuran maksimum aplikasi.
- Upload XLSX divalidasi per baris dan menampilkan baris yang gagal.
- Semua endpoint CRUD dilindungi autentikasi dan permission.
- Pengguna hanya dapat melihat dan mengubah profilnya sendiri.

## 11. Menu Aplikasi

### Admin

Dashboard; Buku dan Eksemplar; Penerbit; Pengarang; Jenis Buku; Inventaris Perpustakaan; Kelas; Anggota dan Staff; Kunjungan; Peminjaman dan Pengembalian; Laporan; Pengaturan; Profil.

### Guru dan Siswa

Dashboard; Cari Buku; Peminjaman Saya; Kunjungan Saya; Profil.

## 12. Tahapan Implementasi

1. Autentikasi, role, permission, layout, dan pengaturan sekolah.
2. Master data: jenis buku, penerbit, pengarang, kelas, anggota, dan tahun ajaran.
3. Buku, eksemplar, lokasi rak, dan inventaris.
4. Kunjungan, peminjaman, dan pengembalian.
5. Perhitungan denda dan aturan peminjaman.
6. Dashboard dan pencarian ketersediaan buku.
7. Laporan PDF dan XLSX.
8. Import XLSX, audit log, validasi, dan pengujian tiap modul.
