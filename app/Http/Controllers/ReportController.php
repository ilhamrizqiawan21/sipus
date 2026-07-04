<?php

namespace App\Http\Controllers;

use App\Models\BorrowingTransaction;
use App\Models\Book;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());

        $circulationReport = $this->getCirculationReport($startDate, $endDate);
        $overdueReport = $this->getOverdueReport($startDate, $endDate);
        $collectionReport = $this->getCollectionReport();
        $finesReport = $this->getFinesReport($startDate, $endDate);

        $reports = compact('circulationReport', 'overdueReport', 'collectionReport', 'finesReport');
        if ($request->wantsJson()) return response()->json($reports);
        return view('reports.index', $reports);
    }

    public function circulation(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());
        $report = $this->getCirculationReport($startDate, $endDate);
        if ($request->wantsJson()) return response()->json($report);
        return view('reports.circulation', compact('report', 'startDate', 'endDate'));
    }

    private function getCirculationReport($startDate, $endDate)
    {
        return [
            'total_borrowed' => BorrowingTransaction::whereBetween('borrow_date', [$startDate, $endDate])->count(),
            'total_returned' => BorrowingTransaction::where('status', 'returned')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_loans' => BorrowingTransaction::whereIn('status', ['borrowed', 'partially_returned'])->count(),
        ];
    }

    private function getOverdueReport($startDate = null, $endDate = null)
    {
        return [
            'total_overdue' => BorrowingTransaction::where('status', '!=', 'returned')
                ->where('due_date', '<', Carbon::now()->toDateString())->count(),
        ];
    }

    private function getCollectionReport()
    {
        return [
            'total_books' => Book::count(),
            'total_copies' => \App\Models\BookCopy::count(),
            'by_category' => DB::table('books')
                ->join('categories', 'books.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, COUNT(*) as count')
                ->groupBy('categories.id', 'categories.name')
                ->get(),
        ];
    }

    private function getFinesReport($startDate, $endDate)
    {
        return [
            'total_fines' => Fine::sum('amount'),
            'unpaid_fines' => Fine::where('status', 'unpaid')->sum('amount'),
        ];
    }
}
