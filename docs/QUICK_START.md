# 🚀 QUICK START GUIDE - SIPUS

## ✅ Status Terkini
- ✅ 27 Models dengan relationships lengkap
- ✅ 11 Controllers dengan full logic
- ✅ 26 Blade views yang functional
- ✅ 40 Routes yang siap digunakan
- ✅ Database schema migration ready
- ✅ Borrowing & Return workflow complete
- ✅ Reports dashboard functional
- ✅ Settings management ready

**Overall Completion: 60-70%** (up from 30-40%)

---

## 🏃 Quick Start (5 Menit)

### 1. Check Laravel & Database
```bash
# Verify database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit()

# Should show SIPUS database is connected
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Create Admin User
```bash
php artisan tinker
>>> App\Models\User::create(['name'=>'Admin', 'email'=>'admin@test.com', 'password'=>bcrypt('password')])
>>> exit()
```

### 4. Start Development Server
```bash
php artisan serve
# Server running at http://localhost:8000
```

### 5. Test Features
1. Go to http://localhost:8000/login
2. Login with admin@test.com / password
3. Try creating book/member/copy
4. Test borrow/return workflow
5. Check reports

---

## 📍 Key URLs

| Feature | URL | Status |
|---------|-----|--------|
| Dashboard | / | ✅ Ready |
| Login | /login | ✅ Ready |
| Books | /books | ✅ Ready |
| Members | /members | ✅ Ready |
| Book Copies | /copies | ✅ Ready |
| **Borrow** | **/loans/borrow** | ✅ **READY** |
| **Return** | **/loans/return** | ✅ **READY** |
| **Loans List** | **/loans** | ✅ **READY** |
| **Reports** | **/reports** | ✅ **READY** |
| **Settings** | **/settings** | ✅ **READY** |
| Procurements | /procurements | ✅ Ready |
| Inventory | /inventory | ✅ Ready |

---

## 🎯 Workflow Contoh

### Skenario: Member A Pinjam Buku
```
1. Login → http://localhost:8000/login
2. Go to → http://localhost:8000/loans/borrow
3. Pilih member "Student A"
4. Pilih buku (e.g., "Matematika SMP Kelas 7")
5. Set durasi (default 14 hari)
6. Click "Simpan Peminjaman"
7. Transaksi tercatat dengan kode unik TXN-XXXXXXXX
8. Buku status berubah menjadi "Dipinjam"
9. Lihat di /loans untuk melihat semua transaksi
10. Lihat detail di /loans/1
```

### Skenario: Member A Kembalikan Buku
```
1. Go to → http://localhost:8000/loans/return
2. Klik transaksi Member A
3. Pilih buku yang dikembalikan (checklist)
4. Input tanggal pengembalian
5. Click "Proses Pengembalian"
6. Sistem auto-calculate:
   - Jika terlambat: create Fine
   - Update status transaksi
   - Update status buku kembali ke "Tersedia"
```

### Skenario: Lihat Laporan
```
1. Go to → http://localhost:8000/reports
2. Lihat dashboard dengan:
   - Total peminjaman
   - Peminjaman aktif
   - Buku terlambat
   - Denda belum lunas
3. Klik masing-masing untuk detail laporan
```

### Skenario: Atur Sistem
```
1. Go to → http://localhost:8000/settings
2. Edit:
   - Nama perpustakaan
   - Email/telepon
   - Batas maksimal pinjam
   - Durasi default peminjaman
   - Tarif denda per hari
3. Click "Simpan Pengaturan"
```

---

## 📂 Struktur File Penting

```
sipus/
├── app/
│   ├── Models/               ✅ 27 models
│   │   ├── Book.php
│   │   ├── Member.php
│   │   ├── BorrowingTransaction.php
│   │   ├── BorrowingItem.php
│   │   ├── Fine.php
│   │   └── ... (24 lebih)
│   └── Http/Controllers/     ✅ 11 controllers
│       ├── LoanController.php
│       ├── ReportController.php
│       ├── SettingController.php
│       ├── InventoryController.php
│       └── ... (7 lebih)
├── resources/views/          ✅ 26 views
│   ├── loans/
│   │   ├── index.blade.php
│   │   ├── borrow.blade.php
│   │   ├── return.blade.php
│   │   └── show.blade.php
│   ├── reports/
│   │   └── index.blade.php
│   ├── settings/
│   │   └── index.blade.php
│   └── ... (19 lebih)
├── routes/
│   └── web.php               ✅ 40 routes
├── database/migrations/      ✅ 1 comprehensive
│   └── 2026_07_04_000000_create_sipus_schema.php
├── .env                      ✅ Fixed (SIPUS DB)
├── COMPLETION_REPORT.md      📄 Laporan lengkap
└── IMPLEMENTATION_SUMMARY.md 📄 Detail teknis

```

---

## 🔧 Common Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (reset db)
php artisan migrate:fresh

# Tinker (interactive shell)
php artisan tinker

# Clear cache
php artisan cache:clear
php artisan config:clear

# List all routes
php artisan route:list

# Check database
php artisan tinker
>>> DB::select("SHOW TABLES FROM sipus;")
```

---

## 🐛 Troubleshooting

### Database Connection Error
```
❌ Error: SQLSTATE[HY000]: General error: 1030 Got error
✅ Solution: Check .env has DB_DATABASE=sipus
```

### Route not found
```
❌ Error: 404 Route not found
✅ Solution: Run php artisan route:list to see all routes
```

### Migration Error
```
❌ Error: SQLSTATE[42S01]: Table already exists
✅ Solution: Run php artisan migrate:rollback then migrate
```

### Blade syntax error
```
❌ Error: Syntax error in blade
✅ Solution: Check resources/views file for {{ vs }}, @if vs @else
```

---

## 📊 Test Data

After migration, create test data:

```bash
php artisan tinker

# Create Member Type
>>> App\Models\MemberType::create(['name' => 'Siswa', 'max_borrow_limit' => 5, 'borrow_duration_days' => 14])

# Create BookCopyStatus
>>> App\Models\BookCopyStatus::create(['name' => 'Tersedia', 'is_available' => true])
>>> App\Models\BookCopyStatus::create(['name' => 'Dipinjam', 'is_available' => false])

# Create Category
>>> App\Models\Category::create(['name' => 'Fiksi'])
>>> App\Models\Category::create(['name' => 'Non-Fiksi'])

# Create Publisher
>>> App\Models\Publisher::create(['name' => 'Gramedia'])

# Create Language
>>> App\Models\Language::create(['name' => 'Indonesia', 'code' => 'id'])

# Create test Book
>>> App\Models\Book::create(['title' => 'Test Book', 'isbn' => '9789876543210', 'category_id' => 1, 'publisher_id' => 1, 'language_id' => 1, 'is_active' => true])

# Create test BookCopy
>>> App\Models\BookCopy::create(['book_id' => 1, 'barcode' => 'BC001', 'status_id' => 1, 'acquisition_date' => now()])

# Create test Member
>>> App\Models\Member::create(['member_code' => 'M001', 'name' => 'John Doe', 'member_type_id' => 1, 'is_active' => true])

# Exit tinker
>>> exit()
```

---

## ✅ Verification Checklist

Run these to verify setup:
```bash
# 1. Check Laravel works
php artisan --version

# 2. Check database connection
mysql -h 127.0.0.1 -u root sipus -e "SHOW TABLES;"

# 3. Check migrations
php artisan migrate:status

# 4. Check routes
php artisan route:list | grep -E "loans|reports|settings"

# 5. Check models
php artisan tinker
>>> App\Models\Book::count()
>>> App\Models\Member::count()
>>> App\Models\BorrowingTransaction::count()
>>> exit()

# 6. Check disk space
df -h

# 7. Check PHP version
php -v
```

---

## 📝 Next Phase TODO

- [ ] Add authorization (roles/permissions)
- [ ] Add email notifications
- [ ] Implement Excel import
- [ ] Write test suites
- [ ] Add API documentation
- [ ] Setup barcode scanning
- [ ] Mobile responsive refinement
- [ ] Performance optimization

---

## 💬 Support

If you encounter issues:
1. Check error message carefully
2. Look at IMPLEMENTATION_SUMMARY.md
3. Check COMPLETION_REPORT.md for details
4. Review controller/model source code
5. Check routes/web.php for route definitions
6. Check .env for configuration

---

**Happy coding! 🎉**

Last updated: July 4, 2026
