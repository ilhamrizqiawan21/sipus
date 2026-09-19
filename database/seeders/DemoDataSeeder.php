<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BookType;
use App\Models\Classroom;
use App\Models\Fine;
use App\Models\Inventory;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\SchoolSetting;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()->updateOrCreate(['username' => 'admin'], ['nama' => 'Admin SIPUS', 'password' => Hash::make('password'), 'role' => 'admin', 'status' => true]);
            $years = $this->seedSchoolYears();
            $classes = $this->seedClasses($years['active']);
            $members = $this->seedMembers($classes, $years['active']);
            $types = $this->seedBookTypes();
            $authors = $this->seedAuthors();
            $publishers = $this->seedPublishers();
            $books = $this->seedBooks($types, $authors, $publishers);
            $copies = $this->seedCopies($books);

            $this->seedInventories();
            $this->seedVisits($members, $admin);
            $this->seedLoans($members, $admin, $copies);
            $this->seedSchoolSetting();
            $this->seedActivityLogs($admin, $members, $books);
        });
    }

    /** @return array{active: SchoolYear, previous: SchoolYear} */
    private function seedSchoolYears(): array
    {
        $previous = SchoolYear::query()->updateOrCreate(['nama' => '2025/2026', 'semester' => '2'], ['mulai' => '2026-01-01', 'selesai' => '2026-06-30', 'is_aktif' => false]);
        $active = SchoolYear::query()->updateOrCreate(['nama' => '2026/2027', 'semester' => '1'], ['mulai' => '2026-07-01', 'selesai' => '2026-12-31', 'is_aktif' => true]);
        SchoolYear::query()->whereKeyNot($active->id)->update(['is_aktif' => false]);

        return compact('active', 'previous');
    }

    /** @return array<string, Classroom> */
    private function seedClasses(SchoolYear $year): array
    {
        $classes = [];
        foreach ([['VII A', 'VII', 'Budi Santoso'], ['VIII A', 'VIII', 'Siti Rahma'], ['IX A', 'IX', 'Andi Wijaya']] as [$nama, $tingkat, $wali]) {
            $classes[$nama] = Classroom::query()->updateOrCreate(['nama' => $nama, 'school_year_id' => $year->id], ['tingkat' => $tingkat, 'wali_nama' => $wali, 'aktif' => true]);
        }

        return $classes;
    }

    /** @return array<string, Member> */
    private function seedMembers(array $classes, SchoolYear $year): array
    {
        $members = [];
        $rows = [
            ['SIPUS-2026-00001', 'Alya Pratama', 'siswa', 'alya.pratama', 'SIPUS-2026-00001', $classes['VII A']->id],
            ['SIPUS-2026-00002', 'Raka Permana', 'siswa', 'raka.permana', 'SIPUS-2026-00002', $classes['VIII A']->id],
            ['SIPUS-2026-00003', 'Nadia Putri', 'siswa', 'nadia.putri', 'SIPUS-2026-00003', $classes['IX A']->id],
            ['SIPUS-2026-00004', 'Dewi Lestari', 'guru', 'dewi.lestari', 'SIPUS-2026-00004', null],
        ];

        foreach ($rows as [$number, $name, $type, $username, $nisNip, $classId]) {
            $user = User::query()->updateOrCreate(['username' => $username], ['nama' => $name, 'password' => Hash::make('password'), 'role' => $type, 'status' => true]);
            $members[$number] = Member::query()->updateOrCreate(['nomor_anggota' => $number], ['user_id' => $user->id, 'jenis_anggota' => $type, 'nama' => $name, 'nis_nip' => $nisNip, 'kelas_id' => $classId, 'tanggal_daftar' => $year->mulai, 'status' => true]);
        }

        return $members;
    }

    /** @return array<string, BookType> */
    private function seedBookTypes(): array
    {
        $types = [];
        foreach ([['Fiksi', 'FIK'], ['Referensi', 'REF'], ['Pelajaran', 'PEL'], ['Sains', 'SAI'], ['Sejarah', 'SEJ']] as [$name, $code]) {
            $types[$code] = BookType::query()->updateOrCreate(['kode' => $code], ['nama' => $name, 'deskripsi' => "Koleksi {$name} perpustakaan.", 'aktif' => true]);
        }

        return $types;
    }

    /** @return array<string, Author> */
    private function seedAuthors(): array
    {
        $authors = [];
        foreach ([['Andrea Hirata', 'Penulis novel Indonesia.'], ['Tere Liye', 'Penulis fiksi dan novel remaja.'], ['Dewi Lestari', 'Penulis dan penyanyi Indonesia.'], ['Bambang Sugiarto', 'Penulis buku pendidikan.']] as [$name, $bio]) {
            $authors[$name] = Author::query()->updateOrCreate(['nama' => $name], ['bio' => $bio]);
        }

        return $authors;
    }

    /** @return array<string, Publisher> */
    private function seedPublishers(): array
    {
        $publishers = [];
        foreach (['Bentang Pustaka', 'Gramedia Pustaka Utama', 'Erlangga'] as $name) {
            $publishers[$name] = Publisher::query()->updateOrCreate(['nama' => $name], ['alamat' => 'Jakarta', 'email' => str($name)->lower()->replace(' ', '.').'@example.test']);
        }

        return $publishers;
    }

    /** @return array<string, Book> */
    private function seedBooks(array $types, array $authors, array $publishers): array
    {
        $rows = [
            ['BK-2026-00001', 'Laskar Pelangi', 'FIK', 'Bentang Pustaka', 'Andrea Hirata', 2005],
            ['BK-2026-00002', 'Bumi', 'FIK', 'Gramedia Pustaka Utama', 'Tere Liye', 2014],
            ['BK-2026-00003', 'Filosofi Kopi', 'FIK', 'Bentang Pustaka', 'Dewi Lestari', 2006],
            ['BK-2026-00004', 'Matematika SMP Kelas VII', 'PEL', 'Erlangga', 'Bambang Sugiarto', 2024],
            ['BK-2026-00005', 'Ensiklopedia Sains Dasar', 'SAI', 'Erlangga', 'Bambang Sugiarto', 2023],
            ['BK-2026-00006', 'Sejarah Indonesia Modern', 'SEJ', 'Erlangga', 'Bambang Sugiarto', 2022],
            ['BK-2026-00007', 'Kamus Bahasa Indonesia', 'REF', 'Gramedia Pustaka Utama', 'Bambang Sugiarto', 2021],
        ];
        $books = [];

        foreach ($rows as [$code, $title, $type, $publisher, $author, $year]) {
            $book = Book::query()->updateOrCreate(['kode_buku' => $code], ['judul' => $title, 'jenis_buku_id' => $types[$type]->id, 'penerbit_id' => $publishers[$publisher]->id, 'tahun_terbit' => $year, 'deskripsi' => "Deskripsi koleksi {$title}."]);
            $book->authors()->sync([$authors[$author]->id]);
            $books[$code] = $book;
        }

        return $books;
    }

    /** @return array<string, BookCopy> */
    private function seedCopies(array $books): array
    {
        $copies = [];
        $number = 1;
        foreach ($books as $bookCode => $book) {
            $total = in_array($bookCode, ['BK-2026-00001', 'BK-2026-00002'], true) ? 3 : 2;
            for ($index = 1; $index <= $total; $index++) {
                $code = sprintf('INV-BK-2026-%03d', $number++);
                $copies[$code] = BookCopy::query()->updateOrCreate(['kode_inventaris' => $code], ['book_id' => $book->id, 'lokasi_rak' => 'Rak '.chr(65 + (($number - 2) % 4)).'-0'.$index, 'kondisi' => 'baik', 'status' => 'tersedia', 'tanggal_masuk' => '2026-07-01', 'harga_perolehan' => 85000]);
            }
        }

        return $copies;
    }

    private function seedInventories(): void
    {
        foreach ([['INV-2026-00001', 'Meja baca', 8, 'unit', 'Ruang baca'], ['INV-2026-00002', 'Kursi baca', 32, 'unit', 'Ruang baca'], ['INV-2026-00003', 'Komputer layanan', 4, 'unit', 'Ruang komputer']] as [$code, $name, $amount, $unit, $location]) {
            Inventory::query()->updateOrCreate(['kode_inventaris' => $code], ['nama_barang' => $name, 'jenis' => 'Perlengkapan', 'jumlah' => $amount, 'satuan' => $unit, 'lokasi' => $location, 'kondisi' => 'baik', 'status' => 'aktif', 'tanggal_perolehan' => '2026-07-01', 'harga_perolehan' => 500000]);
        }
    }

    private function seedVisits(array $members, User $admin): void
    {
        foreach (array_values($members) as $index => $member) {
            Visit::query()->updateOrCreate(['member_id' => $member->id, 'waktu_masuk' => now()->subDays($index + 1)->setTime(8 + $index, 0)], ['recorded_by' => $admin->id, 'waktu_keluar' => now()->subDays($index + 1)->setTime(10 + $index, 0), 'keperluan' => $index % 2 === 0 ? 'Membaca' : 'Meminjam buku', 'catatan' => 'Data demo kunjungan.']);
        }
    }

    private function seedLoans(array $members, User $admin, array $copies): void
    {
        $copyList = array_values($copies);
        foreach (array_values($members) as $index => $member) {
            $loanDate = now()->subDays($index + 2)->toDateString();
            $status = $index === 1 ? 'terlambat' : ($index === 2 ? 'selesai' : 'dipinjam');
            $loan = Loan::query()->updateOrCreate(['kode_transaksi' => sprintf('TRX-202609-%03d', $index + 1)], ['member_id' => $member->id, 'recorded_by' => $admin->id, 'tanggal_pinjam' => $loanDate, 'batas_kembali' => now()->subDays($index === 1 ? 5 : -5)->toDateString(), 'tanggal_selesai' => $status === 'selesai' ? now()->subDay()->toDateString() : null, 'status' => $status, 'catatan' => 'Transaksi demo untuk pengujian dashboard.']);
            $copy = $copyList[$index];
            $itemStatus = $status === 'selesai' ? 'kembali' : 'dipinjam';
            $item = LoanItem::query()->updateOrCreate(['loan_id' => $loan->id, 'book_copy_id' => $copy->id], ['tanggal_kembali' => $itemStatus === 'kembali' ? now()->subDay()->toDateString() : null, 'kondisi_saat_kembali' => $itemStatus === 'kembali' ? 'baik' : null, 'status' => $itemStatus, 'catatan' => null]);
            $copy->update(['status' => $itemStatus === 'dipinjam' ? 'dipinjam' : 'tersedia']);
            if ($status === 'terlambat') {
                Fine::query()->updateOrCreate(['loan_item_id' => $item->id], ['jenis' => 'keterlambatan', 'jumlah' => 15000, 'status' => 'belum_lunas', 'paid_by' => null, 'dibayar_at' => null]);
            }
        }
    }

    private function seedSchoolSetting(): void
    {
        SchoolSetting::query()->firstOrCreate([], ['school_name' => 'SMA Negeri 1 SIPUS', 'address' => 'Jl. Pendidikan No. 1, Jakarta', 'education_level' => 'SMA', 'teacher_loan_days' => 14, 'student_loan_days' => 7, 'teacher_loan_limit' => 5, 'student_loan_limit' => 2, 'late_fee_per_day' => 1000, 'lost_book_fee' => 150000, 'damaged_book_fee' => 75000, 'member_number_format' => 'SIPUS-YYYY-NNNNN', 'loan_number_format' => 'TRX-YYYYMM-NNNNN', 'fine_blocks_loan' => true]);
    }

    private function seedActivityLogs(User $admin, array $members, array $books): void
    {
        ActivityLog::query()->firstOrCreate(['user_id' => $admin->id, 'action' => 'seed.demo', 'description' => 'Data demo SIPUS dibuat.'], ['ip_address' => '127.0.0.1']);
        ActivityLog::query()->firstOrCreate(['user_id' => $admin->id, 'action' => 'member.created', 'description' => 'Data anggota demo tersedia.'], ['subject_type' => Member::class, 'subject_id' => reset($members)->id, 'ip_address' => '127.0.0.1']);
        ActivityLog::query()->firstOrCreate(['user_id' => $admin->id, 'action' => 'book.created', 'description' => 'Katalog buku demo tersedia.'], ['subject_type' => Book::class, 'subject_id' => reset($books)->id, 'ip_address' => '127.0.0.1']);
    }
}
