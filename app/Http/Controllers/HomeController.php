<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::now()->locale('id');

        $bookCount = 0;
        $memberCount = 0;
        $activeLoans = 0;
        $overdueLoans = 0;
        $inventoryCount = 0;
        $latestTransactions = [];
        $categoryStats = [
            ['label' => 'Agama', 'value' => 312, 'color' => '#145048'],
            ['label' => 'IPA', 'value' => 248, 'color' => '#2a8a76'],
            ['label' => 'IPS', 'value' => 195, 'color' => '#bf7c33'],
            ['label' => 'Bahasa', 'value' => 167, 'color' => '#5ba89a'],
            ['label' => 'Umum', 'value' => 143, 'color' => '#e8a44a'],
        ];
        $borrowingTrend = [
            ['month' => 'Jan 2026', 'total' => 120],
            ['month' => 'Feb 2026', 'total' => 145],
            ['month' => 'Mar 2026', 'total' => 162],
            ['month' => 'Apr 2026', 'total' => 138],
            ['month' => 'Mei 2026', 'total' => 175],
            ['month' => 'Jun 2026', 'total' => 190],
            ['month' => 'Jul 2026', 'total' => 84],
        ];
        $processSteps = [
            ['title' => 'Inventaris Koleksi', 'description' => 'Perbarui data buku, eksemplar, dan status rak secara berkala.'],
            ['title' => 'Catat Peminjaman', 'description' => 'Rekam transaksi peminjaman beserta detail anggota dan jadwal pengembalian.'],
            ['title' => 'Proses Pengembalian', 'description' => 'Terima kembali buku dan rekam kondisi akhir serta keterlambatan.'],
            ['title' => 'Cetak Laporan', 'description' => 'Buat laporan sirkulasi, koleksi, dan keterlambatan untuk pengambilan keputusan.'],
        ];

        try {
            $bookCount = Book::count();
            $memberCount = Member::count();

            if (DB::getSchemaBuilder()->hasTable('borrowing_items')) {
                $activeLoans = DB::table('borrowing_items')->where('status', 'borrowed')->count();
                $overdueLoans = DB::table('borrowing_items')
                    ->where('status', 'borrowed')
                    ->whereDate('due_date', '<', $today->toDateString())
                    ->count();

                $latestTransactions = DB::table('borrowing_items')
                    ->select('transaction_code', 'member_name', 'book_title', 'borrow_date', 'due_date', 'status')
                    ->orderByDesc('borrow_date')
                    ->limit(5)
                    ->get()
                    ->map(function ($row) {
                        return [
                            'code' => $row->transaction_code ?? 'TRX-0000',
                            'member' => $row->member_name ?? 'Anggota',
                            'book' => $row->book_title ?? 'Judul Buku',
                            'borrowedAt' => $row->borrow_date ? Carbon::parse($row->borrow_date)->translatedFormat('d M Y') : '-',
                            'dueAt' => $row->due_date ? Carbon::parse($row->due_date)->translatedFormat('d M Y') : '-',
                            'status' => ucfirst($row->status ?? 'Dipinjam'),
                        ];
                    })
                    ->toArray();

                $trendRows = DB::table('borrowing_items')
                    ->select(DB::raw("DATE_FORMAT(borrow_date, '%Y-%m') as month_key"), DB::raw("DATE_FORMAT(borrow_date, '%b %Y') as month_label"), DB::raw('count(*) as total'))
                    ->whereNotNull('borrow_date')
                    ->groupBy('month_key', 'month_label')
                    ->orderBy('month_key')
                    ->limit(7)
                    ->get();

                if ($trendRows->isNotEmpty()) {
                    $borrowingTrend = $trendRows->map(fn($row) => [
                        'month' => $row->month_label,
                        'total' => $row->total,
                    ])->toArray();
                }
            }

            if (DB::getSchemaBuilder()->hasTable('books') && DB::getSchemaBuilder()->hasTable('categories')) {
                $categoryStats = DB::table('books')
                    ->join('categories', 'books.category_id', '=', 'categories.id')
                    ->select('categories.name as label', DB::raw('count(*) as value'))
                    ->groupBy('categories.name')
                    ->orderByDesc('value')
                    ->limit(5)
                    ->get()
                    ->map(function ($row) {
                        return [
                            'label' => $row->label,
                            'value' => $row->value,
                            'color' => '#2a8a76',
                        ];
                    })
                    ->toArray();
            }

            if (DB::getSchemaBuilder()->hasTable('book_copies')) {
                $inventoryCount = DB::table('book_copies')->count();
            }
        } catch (\Throwable $exception) {
            // Jika tabel belum ada atau koneksi tidak tersedia, gunakan nilai fallback.
        }

        if (empty($latestTransactions)) {
            $latestTransactions = [
                ['code' => 'BRW-20260702-0018', 'member' => 'Ahmad Fauzi', 'book' => 'Tafsir Al-Muyassar', 'borrowedAt' => '02 Jul 2026', 'dueAt' => '09 Jul 2026', 'status' => 'Dipinjam'],
                ['code' => 'BRW-20260702-0017', 'member' => 'Siti Nurhaliza', 'book' => 'IPA Terpadu Kelas 7', 'borrowedAt' => '02 Jul 2026', 'dueAt' => '09 Jul 2026', 'status' => 'Dipinjam'],
                ['code' => 'BRW-20260701-0016', 'member' => 'Budi Santoso', 'book' => 'Bahasa Indonesia Kelas 9', 'borrowedAt' => '01 Jul 2026', 'dueAt' => '08 Jul 2026', 'status' => 'Terlambat'],
            ];
        }

        return view('home', compact(
            'today',
            'bookCount',
            'memberCount',
            'activeLoans',
            'overdueLoans',
            'inventoryCount',
            'latestTransactions',
            'categoryStats',
            'borrowingTrend',
            'processSteps'
        ));
    }
}
