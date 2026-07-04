<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Procurement;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $totalBooks = Book::count();
        $totalCopies = BookCopy::count();
        $availableCopies = BookCopy::whereHas('status', fn ($status) => $status->where('is_available', true))
            ->orWhereNull('status_id')
            ->count();
        $borrowedCopies = BookCopy::whereHas('status', fn ($status) => $status->where('name', 'Borrowed'))->count();

        $booksByCondition = DB::table('book_copies')
            ->join('book_conditions', 'book_copies.condition_id', '=', 'book_conditions.id')
            ->selectRaw('book_conditions.name, COUNT(*) as count')
            ->groupBy('book_conditions.id', 'book_conditions.name')
            ->get();

        $stats = compact('totalBooks', 'totalCopies', 'availableCopies', 'borrowedCopies', 'booksByCondition');
        if ($request->wantsJson()) return response()->json($stats);
        return view('inventory.index', $stats);
    }

    public function procurement(Request $request)
    {
        $procurements = Procurement::with('items')->orderByDesc('created_at')->paginate(20);
        if ($request->wantsJson()) return response()->json($procurements);
        return view('inventory.procurement', compact('procurements'));
    }

    public function stockOpname(Request $request)
    {
        $opnames = StockOpname::with('details')->orderByDesc('created_at')->paginate(20);
        if ($request->wantsJson()) return response()->json($opnames);
        return view('inventory.stock-opname', compact('opnames'));
    }
}
