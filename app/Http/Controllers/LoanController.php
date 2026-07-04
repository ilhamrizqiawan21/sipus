<?php

namespace App\Http\Controllers;

use App\Models\BorrowingTransaction;
use App\Models\BorrowingItem;
use App\Models\BookCopy;
use App\Models\BookCopyStatus;
use App\Models\Member;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = BorrowingTransaction::with('member', 'borrowingItems')
            ->orderByDesc('created_at')
            ->paginate(20);
        if ($request->wantsJson()) return response()->json($loans);
        return view('loans.index', ['loans' => $loans]);
    }

    public function borrow(Request $request)
    {
        $members = Member::where('is_active', true)->orderBy('name')->get();
        $availableStatusId = $this->availableStatusId();
        $copies = BookCopy::with('book', 'status')
            ->whereHas('book')
            ->where(function ($query) use ($availableStatusId) {
                $query->where('status_id', $availableStatusId)
                    ->orWhereNull('status_id')
                    ->orWhereHas('status', fn ($status) => $status->where('is_available', true));
            })
            ->orderBy('barcode')
            ->get();

        return view('loans.borrow', compact('members', 'copies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_copy_ids' => 'required|array|min:1',
            'book_copy_ids.*' => 'distinct|exists:book_copies,id',
            'borrow_date' => 'required|date',
            'duration_days' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $member = Member::with('class', 'memberType')->findOrFail($data['member_id']);
            $availableStatusId = $this->availableStatusId();
            $borrowedStatusId = $this->borrowedStatusId();
            $borrowDate = Carbon::parse($data['borrow_date'])->startOfDay();
            $durationDays = (int) $data['duration_days'];
            $dueDate = $borrowDate->copy()->addDays($durationDays);
            $transactionCode = 'TXN-' . now()->format('YmdHis');
            $bookCopyIds = collect($data['book_copy_ids'])->map(fn ($id) => (int) $id)->unique()->values();
            $bookCopies = BookCopy::with('book', 'status')
                ->whereIn('id', $bookCopyIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($bookCopies->count() !== $bookCopyIds->count()) {
                throw new \RuntimeException('Ada eksemplar yang tidak ditemukan.');
            }

            $transaction = BorrowingTransaction::create([
                'transaction_code' => $transactionCode,
                'member_id' => $member->id,
                'member_code_snapshot' => $member->member_code,
                'member_name_snapshot' => $member->name,
                'member_class_snapshot' => $member->class?->name,
                'member_type_snapshot' => $member->memberType?->name ?? 'Anggota',
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'status' => 'borrowed',
                'processed_by' => auth()->id(),
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($bookCopyIds as $bookCopyId) {
                $bookCopy = $bookCopies->get($bookCopyId);
                if (!$this->isCopyAvailable($bookCopy, $availableStatusId)) {
                    if ((int) $bookCopy->status_id === $borrowedStatusId) {
                        throw new \RuntimeException('Eksemplar ' . ($bookCopy->barcode ?? $bookCopy->id) . ' sedang dipinjam.');
                    }

                    throw new \RuntimeException('Eksemplar ' . ($bookCopy->barcode ?? $bookCopy->id) . ' tidak tersedia untuk dipinjam.');
                }
                $book = $bookCopy->book;
                if (!$book) {
                    throw new \RuntimeException('Eksemplar ' . ($bookCopy->barcode ?? $bookCopy->id) . ' tidak memiliki data buku.');
                }

                BorrowingItem::create([
                    'borrowing_transaction_id' => $transaction->id,
                    'book_copy_id' => $bookCopyId,
                    'book_title_snapshot' => $book->title,
                    'book_isbn_snapshot' => $book->isbn,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'borrowed',
                ]);
                $bookCopy->update(['status_id' => $borrowedStatusId]);
            }

            DB::commit();
            if ($request->wantsJson()) return response()->json($transaction, 201);
            return redirect()->route('loans.show', $transaction->id)->with('success', "Peminjaman: $transactionCode");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function return(Request $request)
    {
        $transactions = BorrowingTransaction::where('status', '!=', 'returned')
            ->with('member', 'borrowingItems')->orderByDesc('due_date')->get();
        return view('loans.return', compact('transactions'));
    }

    public function processReturn(Request $request, $transactionId)
    {
        $data = $request->validate([
            'book_copy_ids' => 'required|array|min:1',
            'book_copy_ids.*' => 'distinct|exists:book_copies,id',
            'return_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $transaction = BorrowingTransaction::with('borrowingItems')->findOrFail($transactionId);
            if ($transaction->status === 'returned') {
                throw new \RuntimeException('Transaksi ini sudah selesai dikembalikan.');
            }

            $availableStatusId = $this->availableStatusId();
            $returnDate = Carbon::parse($data['return_date'])->startOfDay();
            $bookCopyIds = collect($data['book_copy_ids'])->map(fn ($id) => (int) $id)->unique()->values();
            $borrowingItems = BorrowingItem::where('borrowing_transaction_id', $transactionId)
                ->whereIn('book_copy_id', $bookCopyIds)
                ->where('status', '!=', 'returned')
                ->lockForUpdate()
                ->get()
                ->keyBy('book_copy_id');

            if ($borrowingItems->count() !== $bookCopyIds->count()) {
                throw new \RuntimeException('Ada eksemplar yang tidak termasuk transaksi aktif ini atau sudah dikembalikan.');
            }

            foreach ($bookCopyIds as $bookCopyId) {
                $borrowingItem = $borrowingItems->get($bookCopyId);
                $borrowingItem->update(['return_date' => $returnDate, 'status' => 'returned']);
                BookCopy::findOrFail($bookCopyId)->update(['status_id' => $availableStatusId]);

                if ($returnDate->gt(Carbon::parse($borrowingItem->due_date))) {
                    $overdueDays = (int) ceil($returnDate->diffInDays(Carbon::parse($borrowingItem->due_date)));
                    Fine::create([
                        'borrowing_transaction_id' => $transaction->id,
                        'book_copy_id' => $bookCopyId,
                        'reason' => 'Overdue (' . $overdueDays . ' days)',
                        'amount' => $overdueDays * 5000,
                        'status' => 'unpaid',
                    ]);
                }
            }

            $allReturned = !BorrowingItem::where('borrowing_transaction_id', $transactionId)
                ->where('status', '!=', 'returned')->exists();
            $transaction->update(['status' => $allReturned ? 'returned' : 'partially_returned', 'updated_by' => auth()->id()]);
            DB::commit();
            if ($request->wantsJson()) return response()->json($transaction);
            return redirect()->route('loans.index')->with('success', 'Pengembalian diproses');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $transaction = BorrowingTransaction::with('member', 'borrowingItems', 'fines')->findOrFail($id);
        return view('loans.show', compact('transaction'));
    }

    private function availableStatusId(): int
    {
        return BookCopyStatus::firstOrCreate(
            ['name' => 'Available'],
            ['description' => 'Siap dipinjam', 'is_available' => true, 'is_active' => true]
        )->id;
    }

    private function borrowedStatusId(): int
    {
        return BookCopyStatus::firstOrCreate(
            ['name' => 'Borrowed'],
            ['description' => 'Sedang dipinjam', 'is_available' => false, 'is_active' => true]
        )->id;
    }

    private function isCopyAvailable(BookCopy $bookCopy, int $availableStatusId): bool
    {
        if (empty($bookCopy->status_id)) {
            return true;
        }

        if ((int) $bookCopy->status_id === $availableStatusId) {
            return true;
        }

        return (bool) optional($bookCopy->status)->is_available;
    }
}
