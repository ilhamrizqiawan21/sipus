<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\Book;
use App\Models\BookProcurementItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    public function index()
    {
        $orders = Procurement::withCount('items')->orderByDesc('created_at')->paginate(20);
        return view('procurements.index', compact('orders'));
    }

    public function create()
    {
        $books = Book::select('id', 'title')->orderBy('title')->get();
        return view('procurements.create', compact('books'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'notes' => 'nullable|string',
            'book_id' => 'required|array|min:1',
            'book_id.*' => 'required|exists:books,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'unit_price' => 'required|array|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $items = [];
        foreach ($data['book_id'] as $index => $bookId) {
            $quantity = (int) ($data['quantity'][$index] ?? 0);
            $unitPrice = (float) ($data['unit_price'][$index] ?? 0);
            if ($quantity < 1) {
                continue;
            }
            $items[] = [
                'book_id' => $bookId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ];
        }

        $order = DB::transaction(function () use ($data, $items) {
            $order = Procurement::create([
                'supplier_name' => $data['supplier_name'],
                'order_date' => $data['order_date'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'total' => array_sum(array_map(fn ($it) => $it['quantity'] * $it['unit_price'], $items)),
            ]);

            foreach ($items as $item) {
                BookProcurementItem::create($item + ['book_procurement_id' => $order->id]);
            }

            return $order;
        });

        session()->flash('success', 'Pengadaan tersimpan.');
        return redirect()->route('procurements.index');
    }

    public function show($id)
    {
        $order = Procurement::with('items.book')->findOrFail($id);
        return view('procurements.show', compact('order'));
    }

    public function approve($id)
    {
        $order = Procurement::findOrFail($id);
        $order->status = 'approved';
        $order->save();
        session()->flash('success', 'Pengadaan disetujui.');
        return redirect()->route('procurements.show', $id);
    }

    public function destroy($id)
    {
        $order = Procurement::findOrFail($id);
        $order->delete();
        session()->flash('success', 'Pengadaan dihapus.');
        return redirect()->route('procurements.index');
    }
}
