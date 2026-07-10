<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InitialMasterDataSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $timestamp = now();

        $this->seedRows('languages', ['name'], [
            ['name' => 'Indonesia', 'code' => 'ID', 'is_active' => true],
            ['name' => 'English', 'code' => 'EN', 'is_active' => true],
            ['name' => 'Arab', 'code' => 'AR', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('categories', ['code'], [
            ['name' => 'Karya Umum', 'code' => '000', 'is_active' => true],
            ['name' => 'Filsafat', 'code' => '100', 'is_active' => true],
            ['name' => 'Agama', 'code' => '200', 'is_active' => true],
            ['name' => 'Ilmu Sosial', 'code' => '300', 'is_active' => true],
            ['name' => 'Bahasa', 'code' => '400', 'is_active' => true],
            ['name' => 'Ilmu Murni', 'code' => '500', 'is_active' => true],
            ['name' => 'Ilmu Terapan', 'code' => '600', 'is_active' => true],
            ['name' => 'Kesenian & Olahraga', 'code' => '700', 'is_active' => true],
            ['name' => 'Kesusastraan', 'code' => '800', 'is_active' => true],
            ['name' => 'Sejarah & Geografi', 'code' => '900', 'is_active' => true],
            ['name' => 'Fiksi', 'code' => 'FIC', 'is_active' => true],
            ['name' => 'Referensi', 'code' => 'REF', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('member_types', ['name'], [
            ['name' => 'Siswa', 'borrow_limit' => 2, 'borrow_duration_days' => 7, 'is_active' => true],
            ['name' => 'Guru', 'borrow_limit' => 5, 'borrow_duration_days' => 14, 'is_active' => true],
            ['name' => 'Staff', 'borrow_limit' => 3, 'borrow_duration_days' => 7, 'is_active' => true],
        ], $timestamp);

        $this->seedRows('classes', ['name'], [
            ['name' => 'VII A', 'grade_level' => 'VII', 'academic_year' => '2025/2026', 'is_active' => true],
            ['name' => 'VII B', 'grade_level' => 'VII', 'academic_year' => '2025/2026', 'is_active' => true],
            ['name' => 'VIII A', 'grade_level' => 'VIII', 'academic_year' => '2025/2026', 'is_active' => true],
            ['name' => 'VIII B', 'grade_level' => 'VIII', 'academic_year' => '2025/2026', 'is_active' => true],
            ['name' => 'IX A', 'grade_level' => 'IX', 'academic_year' => '2025/2026', 'is_active' => true],
            ['name' => 'IX B', 'grade_level' => 'IX', 'academic_year' => '2025/2026', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('visit_purposes', ['name'], [
            ['name' => 'Membaca di Tempat', 'is_active' => true],
            ['name' => 'Meminjam Buku', 'is_active' => true],
            ['name' => 'Mengembalikan Buku', 'is_active' => true],
            ['name' => 'Mengerjakan Tugas', 'is_active' => true],
            ['name' => 'Diskusi Kelompok', 'is_active' => true],
            ['name' => 'Lainnya', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('book_conditions', ['name'], [
            ['name' => 'Baik', 'description' => 'Kondisi fisik buku baik dan layak dipinjamkan', 'is_active' => true],
            ['name' => 'Rusak Ringan', 'description' => 'Kerusakan minor, masih layak dipinjamkan', 'is_active' => true],
            ['name' => 'Rusak Berat', 'description' => 'Kerusakan signifikan, perlu perbaikan sebelum dipinjamkan', 'is_active' => true],
            ['name' => 'Hilang', 'description' => 'Buku dinyatakan hilang', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('book_copy_statuses', ['code'], [
            ['code' => 'available', 'name' => 'Tersedia', 'is_active' => true],
            ['code' => 'borrowed', 'name' => 'Dipinjam', 'is_active' => true],
            ['code' => 'reserved', 'name' => 'Dipesan', 'is_active' => true],
            ['code' => 'in_repair', 'name' => 'Dalam Perbaikan', 'is_active' => true],
            ['code' => 'damaged', 'name' => 'Rusak', 'is_active' => true],
            ['code' => 'lost', 'name' => 'Hilang', 'is_active' => true],
            ['code' => 'withdrawn', 'name' => 'Ditarik', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('book_sources', ['name'], [
            ['name' => 'Pembelian', 'description' => 'Pengadaan melalui pembelian oleh sekolah', 'is_active' => true],
            ['name' => 'Hibah / Sumbangan', 'description' => 'Sumbangan dari pihak ketiga, wali murid, atau alumni', 'is_active' => true],
            ['name' => 'Bantuan Pemerintah', 'description' => 'Bantuan dari Kemenag/Kemendikbud atau dinas terkait', 'is_active' => true],
            ['name' => 'Hasil Karya Siswa/Guru', 'description' => 'Karya tulis internal yang didokumentasikan sebagai koleksi', 'is_active' => true],
        ], $timestamp);

        $this->seedRows('bookshelves', ['code'], [
            ['code' => 'RAK-001', 'name' => 'Rak Utama', 'location' => 'Ruang Perpustakaan', 'capacity' => 150, 'is_active' => true],
            ['code' => 'RAK-002', 'name' => 'Rak Referensi', 'location' => 'Ruang Perpustakaan', 'capacity' => 100, 'is_active' => true],
            ['code' => 'RAK-003', 'name' => 'Rak Fiksi', 'location' => 'Ruang Baca', 'capacity' => 120, 'is_active' => true],
        ], $timestamp);

        $this->seedRows('settings', ['key'], [
            ['key' => 'library_name', 'value' => 'Perpustakaan MTs Al-Ihsan Batujajar', 'type' => 'string', 'group' => 'general', 'description' => 'Nama perpustakaan yang tampil di header/cetakan'],
            ['key' => 'library_address', 'value' => 'Batujajar, Kabupaten Bandung Barat, Jawa Barat', 'type' => 'string', 'group' => 'general', 'description' => 'Alamat perpustakaan'],
            ['key' => 'library_phone', 'value' => '', 'type' => 'string', 'group' => 'general', 'description' => 'Nomor telepon perpustakaan'],
            ['key' => 'library_email', 'value' => '', 'type' => 'string', 'group' => 'general', 'description' => 'Email perpustakaan'],
            ['key' => 'academic_year_active', 'value' => '2025/2026', 'type' => 'string', 'group' => 'general', 'description' => 'Tahun ajaran aktif saat ini'],
            ['key' => 'max_borrow_limit', 'value' => '5', 'type' => 'integer', 'group' => 'circulation', 'description' => 'Batas jumlah buku yang dapat dipinjam'],
            ['key' => 'borrow_duration_days', 'value' => '14', 'type' => 'integer', 'group' => 'circulation', 'description' => 'Durasi peminjaman default untuk form pengaturan'],
            ['key' => 'default_borrow_duration_days', 'value' => '7', 'type' => 'integer', 'group' => 'circulation', 'description' => 'Lama peminjaman default jika member_type tidak mengatur khusus'],
            ['key' => 'fine_per_day', 'value' => '500', 'type' => 'integer', 'group' => 'circulation', 'description' => 'Denda keterlambatan per hari (Rupiah)'],
            ['key' => 'max_renewal_count', 'value' => '1', 'type' => 'integer', 'group' => 'circulation', 'description' => 'Maksimal perpanjangan per item peminjaman'],
        ], $timestamp);
    }

    private function seedRows(string $table, array $uniqueColumns, array $rows, mixed $timestamp): void
    {
        $columns = Schema::getColumnListing($table);

        foreach ($rows as $row) {
            if ($table === 'settings') {
                $row['setting_key'] = $row['key'] ?? $row['setting_key'] ?? null;
                $row['setting_value'] = $row['value'] ?? $row['setting_value'] ?? null;
                $row['setting_type'] = $row['type'] ?? $row['setting_type'] ?? 'string';
            }

            $row = array_intersect_key($row, array_flip($columns));
            if (in_array('created_at', $columns, true)) {
                $row['created_at'] = $timestamp;
            }
            if (in_array('updated_at', $columns, true)) {
                $row['updated_at'] = $timestamp;
            }

            $unique = [];
            foreach ($uniqueColumns as $column) {
                if (array_key_exists($column, $row)) {
                    $unique[$column] = $row[$column];
                }
            }

            if ($unique === []) {
                foreach (['key', 'code', 'name'] as $fallbackColumn) {
                    if (array_key_exists($fallbackColumn, $row)) {
                        $unique[$fallbackColumn] = $row[$fallbackColumn];
                        break;
                    }
                }
            }

            if ($unique === []) {
                continue;
            }

            DB::table($table)->updateOrInsert($unique, $row);
        }
    }
}
