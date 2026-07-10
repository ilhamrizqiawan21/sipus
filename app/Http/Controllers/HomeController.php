<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BorrowingItem;
use App\Models\BorrowingTransaction;
use App\Models\Fine;
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
        $unpaidFines = 0;
        $latestTransactions = [];
        $categoryStats = [];
        $borrowingTrend = [];
        $processSteps = [
            ['title' => 'Inventaris Koleksi', 'description' => 'Perbarui data buku, eksemplar, dan status rak secara berkala.'],
            ['title' => 'Catat Peminjaman', 'description' => 'Rekam transaksi peminjaman beserta detail anggota dan jadwal pengembalian.'],
            ['title' => 'Proses Pengembalian', 'description' => 'Terima kembali buku dan rekam kondisi akhir serta keterlambatan.'],
            ['title' => 'Cetak Laporan', 'description' => 'Buat laporan sirkulasi, koleksi, dan keterlambatan untuk pengambilan keputusan.'],
        ];

        try {
            $bookCount = Book::count();
            $memberCount = Member::where('is_active', true)->count();
            $inventoryCount = BookCopy::count();
            $activeLoans = BorrowingItem::where('status', 'borrowed')->count();
            $overdueLoans = BorrowingItem::where('status', 'borrowed')
                ->whereDate('due_date', '<', $today->toDateString())
                ->count();
            $unpaidFines = Fine::where('status', 'unpaid')->sum('amount');

            if (DB::getSchemaBuilder()->hasTable('borrowing_transactions')) {
                $latestTransactions = BorrowingTransaction::with('borrowingItems')
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get()
                    ->map(function ($transaction) {
                        $bookTitles = $transaction->borrowingItems
                            ->pluck('book_title_snapshot')
                            ->filter()
                            ->unique()
                            ->values();

                        return [
                            'id' => $transaction->id,
                            'code' => $transaction->transaction_code,
                            'member' => $transaction->member_name_snapshot,
                            'book' => $bookTitles->isNotEmpty() ? $bookTitles->implode(', ') : '-',
                            'borrowedAt' => $transaction->borrow_date ? Carbon::parse($transaction->borrow_date)->translatedFormat('d M Y') : '-',
                            'dueAt' => $transaction->due_date ? Carbon::parse($transaction->due_date)->translatedFormat('d M Y') : '-',
                            'status' => $this->formatTransactionStatus($transaction->status),
                        ];
                    })
                    ->toArray();

                $trendRows = DB::table('borrowing_items')
                    ->join('borrowing_transactions', 'borrowing_items.borrowing_transaction_id', '=', 'borrowing_transactions.id')
                    ->select(DB::raw("DATE_FORMAT(borrowing_transactions.borrow_date, '%Y-%m') as month_key"), DB::raw("DATE_FORMAT(borrowing_transactions.borrow_date, '%b %Y') as month_label"), DB::raw('count(*) as total'))
                    ->whereNotNull('borrowing_transactions.borrow_date')
                    ->groupBy('month_key', 'month_label')
                    ->orderBy('month_key')
                    ->limit(7)
                    ->get();

                $borrowingTrend = $trendRows->map(fn($row) => [
                    'month' => $row->month_label,
                    'total' => $row->total,
                ])->toArray();
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

        } catch (\Throwable $exception) {
            // Jika tabel belum ada atau koneksi tidak tersedia, tampilkan data kosong dari controller.
        }

        return view('home', compact(
            'today',
            'bookCount',
            'memberCount',
            'activeLoans',
            'overdueLoans',
            'inventoryCount',
            'unpaidFines',
            'latestTransactions',
            'categoryStats',
            'borrowingTrend',
            'processSteps'
        ));
    }

    private function formatTransactionStatus(?string $status): string
    {
        return match ($status) {
            'borrowed' => 'Dipinjam',
            'partially_returned' => 'Dikembalikan Sebagian',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            default => '-',
        };
    }
}
