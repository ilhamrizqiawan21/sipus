<?php

namespace App\Http\Controllers;

use App\Models\BorrowingTransaction;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BorrowingItem;
use App\Models\Fine;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $reports = $this->buildReports($startDate, $endDate);

        if ($request->wantsJson()) return response()->json($reports);
        return view('reports.index', $reports);
    }

    public function circulation(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $report = $this->getCirculationReport($startDate, $endDate);

        $items = BorrowingItem::with('borrowingTransaction')
            ->whereHas('borrowingTransaction', fn ($query) => $query->whereBetween('borrow_date', [$startDate, $endDate]))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) return response()->json(['report' => $report, 'items' => $items]);
        return view('reports.circulation', compact('report', 'items', 'startDate', 'endDate'));
    }

    public function overdue(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $report = $this->getOverdueReport($startDate, $endDate);
        $items = BorrowingItem::with('borrowingTransaction')
            ->where('status', 'borrowed')
            ->whereDate('due_date', '<', now()->toDateString())
            ->whereBetween('due_date', [$startDate, $endDate])
            ->orderBy('due_date')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) return response()->json(['report' => $report, 'items' => $items]);
        return view('reports.overdue', compact('report', 'items', 'startDate', 'endDate'));
    }

    public function collection(Request $request)
    {
        $report = $this->getCollectionReport();

        if ($request->wantsJson()) return response()->json($report);
        [$startDate, $endDate] = $this->dateRange($request);
        return view('reports.index', $this->buildReports($startDate, $endDate));
    }

    public function members(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $report = $this->getMembersReport($startDate, $endDate);
        $members = Member::with('memberType', 'class')
            ->where('is_active', true)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('join_date', [$startDate, $endDate])
                    ->orWhere(function ($fallback) use ($startDate, $endDate) {
                        $fallback->whereNull('join_date')
                            ->whereBetween('created_at', [
                                Carbon::parse($startDate)->startOfDay(),
                                Carbon::parse($endDate)->endOfDay(),
                            ]);
                    });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) return response()->json(['report' => $report, 'members' => $members]);
        return view('reports.members', compact('report', 'members', 'startDate', 'endDate'));
    }

    public function fines(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $report = $this->getFinesReport($startDate, $endDate);

        if ($request->wantsJson()) return response()->json($report);
        return view('reports.fines', [
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'fines' => $this->fineQuery($startDate, $endDate)
                ->with('borrowingItem.borrowingTransaction')
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    private function dateRange(Request $request): array
    {
        return [
            $request->query('start_date', Carbon::now()->subMonth()->toDateString()),
            $request->query('end_date', Carbon::now()->toDateString()),
        ];
    }

    private function buildReports($startDate, $endDate): array
    {
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'circulationReport' => $this->getCirculationReport($startDate, $endDate),
            'overdueReport' => $this->getOverdueReport($startDate, $endDate),
            'collectionReport' => $this->getCollectionReport(),
            'finesReport' => $this->getFinesReport($startDate, $endDate),
            'membersReport' => $this->getMembersReport($startDate, $endDate),
        ];
    }

    private function getCirculationReport($startDate, $endDate)
    {
        return [
            'total_borrowed' => BorrowingTransaction::whereBetween('borrow_date', [$startDate, $endDate])->count(),
            'total_items_borrowed' => BorrowingItem::whereHas('borrowingTransaction', fn ($query) => $query->whereBetween('borrow_date', [$startDate, $endDate]))->count(),
            'total_returned' => BorrowingTransaction::where('status', 'returned')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_loans' => BorrowingItem::where('status', 'borrowed')->count(),
        ];
    }

    private function getOverdueReport($startDate = null, $endDate = null)
    {
        return [
            'total_overdue' => BorrowingItem::where('status', 'borrowed')
                ->where('due_date', '<', Carbon::now()->toDateString())->count(),
        ];
    }

    private function getCollectionReport()
    {
        return [
            'total_books' => Book::count(),
            'total_copies' => BookCopy::count(),
            'by_category' => DB::table('books')
                ->join('categories', 'books.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, COUNT(*) as count')
                ->groupBy('categories.id', 'categories.name')
                ->get(),
        ];
    }

    private function getMembersReport($startDate, $endDate)
    {
        return [
            'active_members' => Member::where('is_active', true)->count(),
            'new_members' => Member::where('is_active', true)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('join_date', [$startDate, $endDate])
                        ->orWhere(function ($fallback) use ($startDate, $endDate) {
                            $fallback->whereNull('join_date')
                                ->whereBetween('created_at', [
                                    Carbon::parse($startDate)->startOfDay(),
                                    Carbon::parse($endDate)->endOfDay(),
                                ]);
                        });
                })
                ->count(),
        ];
    }

    private function getFinesReport($startDate, $endDate)
    {
        $baseQuery = $this->fineQuery($startDate, $endDate);

        return [
            'total_fines' => (clone $baseQuery)->sum('amount'),
            'paid_fines' => (clone $baseQuery)->where('status', 'paid')->sum('paid_amount'),
            'unpaid_fines' => (clone $baseQuery)->where('status', 'unpaid')->sum('amount'),
            'waived_fines' => (clone $baseQuery)->where('status', 'waived')->sum('amount'),
            'total_records' => (clone $baseQuery)->count(),
            'unpaid_records' => (clone $baseQuery)->where('status', 'unpaid')->count(),
        ];
    }

    private function fineQuery($startDate, $endDate)
    {
        return Fine::query()->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);
    }
}
