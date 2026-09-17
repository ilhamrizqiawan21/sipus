# ERD SIPUS

Entity Relationship Diagram SIPUS berdasarkan rancangan modul, hak akses, dan alur transaksi.

```mermaid
erDiagram
    USERS {
        bigint id PK
        string username UK
        string password
        enum role
        boolean status
        datetime last_login_at
    }
    MEMBERS {
        bigint id PK
        bigint user_id FK
        string nomor_anggota UK
        enum jenis_anggota
        string nama
        string nis_nip UK
        bigint kelas_id FK
        string telepon
        string email
        string foto_path
        date tanggal_daftar
        boolean status
    }
    CLASSES {
        bigint id PK
        string nama
        string tingkat
        bigint school_year_id FK
        string wali_nama
        boolean aktif
    }
    SCHOOL_YEARS {
        bigint id PK
        string nama
        enum semester
        boolean is_aktif
        date mulai
        date selesai
    }
    BOOKS {
        bigint id PK
        string kode_buku UK
        string judul
        bigint jenis_buku_id FK
        bigint penerbit_id FK
        int tahun_terbit
        text deskripsi
        string cover_path
        datetime deleted_at
    }
    BOOK_COPIES {
        bigint id PK
        bigint book_id FK
        string kode_inventaris UK
        string lokasi_rak
        enum kondisi
        enum status
        date tanggal_masuk
        decimal harga_perolehan
        datetime deleted_at
    }
    AUTHORS {
        bigint id PK
        string nama
        text bio
        text catatan
    }
    BOOK_AUTHORS {
        bigint book_id PK, FK
        bigint author_id PK, FK
    }
    PUBLISHERS {
        bigint id PK
        string nama UK
        text alamat
        string telepon
        string email
        string website
    }
    BOOK_TYPES {
        bigint id PK
        string nama UK
        string kode UK
        text deskripsi
        boolean aktif
    }
    INVENTORIES {
        bigint id PK
        string kode_inventaris UK
        string nama_barang
        string jenis
        int jumlah
        string satuan
        string lokasi
        enum kondisi
        enum status
        date tanggal_perolehan
        decimal harga_perolehan
    }
    VISITS {
        bigint id PK
        bigint member_id FK
        bigint recorded_by FK
        datetime waktu_masuk
        datetime waktu_keluar
        string keperluan
        text catatan
    }
    LOANS {
        bigint id PK
        string kode_transaksi UK
        bigint member_id FK
        bigint recorded_by FK
        date tanggal_pinjam
        date batas_kembali
        date tanggal_selesai
        enum status
        text catatan
    }
    LOAN_ITEMS {
        bigint id PK
        bigint loan_id FK
        bigint book_copy_id FK
        date tanggal_kembali
        enum kondisi_saat_kembali
        enum status
        text catatan
    }
    FINES {
        bigint id PK
        bigint loan_item_id FK
        bigint paid_by FK
        enum jenis
        decimal jumlah
        enum status
        datetime dibayar_at
        text catatan
    }
    SCHOOL_SETTINGS {
        bigint id PK
        string school_name
        string logo_path
        text address
        string education_level
        int teacher_loan_days
        int student_loan_days
        int teacher_loan_limit
        int student_loan_limit
        decimal late_fee_per_day
        decimal lost_book_fee
        decimal damaged_book_fee
    }
    ACTIVITY_LOGS {
        bigint id PK
        bigint user_id FK
        string action
        string subject_type
        bigint subject_id
        text description
        string ip_address
        datetime created_at
    }

    USERS ||--o| MEMBERS : "memiliki profil"
    SCHOOL_YEARS ||--o{ CLASSES : "memiliki"
    CLASSES ||--o{ MEMBERS : "menampung siswa"
    BOOK_TYPES ||--o{ BOOKS : "mengelompokkan"
    PUBLISHERS ||--o{ BOOKS : "menerbitkan"
    BOOKS ||--o{ BOOK_COPIES : "memiliki eksemplar"
    BOOKS ||--o{ BOOK_AUTHORS : "ditulis melalui"
    AUTHORS ||--o{ BOOK_AUTHORS : "menulis melalui"
    MEMBERS ||--o{ VISITS : "melakukan"
    USERS ||--o{ VISITS : "mencatat"
    MEMBERS ||--o{ LOANS : "melakukan"
    USERS ||--o{ LOANS : "mencatat"
    LOANS ||--|{ LOAN_ITEMS : "memiliki detail"
    BOOK_COPIES ||--o{ LOAN_ITEMS : "dipinjam dalam"
    LOAN_ITEMS ||--o| FINES : "dapat dikenai"
    USERS ||--o{ FINES : "menerima pembayaran"
    USERS ||--o{ ACTIVITY_LOGS : "menghasilkan"
```

## Relasi Utama

| Relasi | Kardinalitas | Keterangan |
|---|---:|---|
| `users` - `members` | 1 : 0..1 | Akun dapat memiliki satu profil anggota. |
| `school_years` - `classes` | 1 : banyak | Satu tahun ajaran memiliki banyak kelas. |
| `classes` - `members` | 1 : banyak | Satu kelas dapat memiliki banyak siswa. |
| `books` - `book_copies` | 1 : banyak | Satu judul dapat memiliki banyak eksemplar. |
| `books` - `authors` | banyak : banyak | Dikelola melalui `book_authors`. |
| `members` - `loans` | 1 : banyak | Histori peminjaman anggota. |
| `loans` - `loan_items` | 1 : banyak | Satu transaksi dapat memuat beberapa buku. |
| `book_copies` - `loan_items` | 1 : banyak | Satu eksemplar memiliki banyak histori, tetapi satu pinjaman aktif. |
| `loan_items` - `fines` | 1 : 0..1 | Satu detail dapat memiliki satu denda gabungan. |
| `users` - `activity_logs` | 1 : banyak | Perubahan penting dicatat berdasarkan pelaku. |

## Aturan Integritas Data

- `kode_buku`, `kode_inventaris`, `nomor_anggota`, `username`, dan `kode_transaksi` harus unik.
- Eksemplar berstatus `dipinjam` tidak boleh dipakai pada transaksi aktif lain.
- Anggota nonaktif tidak dapat membuat peminjaman baru.
- Data yang memiliki histori transaksi menggunakan soft delete atau status nonaktif.
- `school_settings` umumnya hanya memiliki satu baris konfigurasi aktif.
- Denda disarankan memakai satu arah relasi saja: `fines.loan_item_id`; kolom `fine_id` pada `loan_items` tidak diperlukan pada migration final.

## Catatan Implementasi

Struktur memisahkan judul buku dari eksemplar agar satu judul dapat memiliki beberapa buku fisik dengan kode inventaris, lokasi, kondisi, dan status berbeda. Histori transaksi tetap dipertahankan agar laporan dan perhitungan statistik tidak berubah ketika data master dinonaktifkan.
