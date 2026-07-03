<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index()
    {
        $orders = Procurement::orderByDesc('created_at')->paginate(20);
        return view('procurements.index', compact('orders'));
    }

    public function create()
    {
        return view('procurements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'nullable|string', // CSV-like lines: title,qty,price
        ]);

        $items = [];
        if (!empty($data['items'])) {
            $lines = preg_split('/\r?\n/', trim($data['items']));
            foreach ($lines as $line) {
                $parts = array_map('trim', explode(',', $line));
                if (count($parts) >= 2) {
                    $items[] = [
                        'title' => $parts[0] ?? null,
                        'quantity' => isset($parts[1]) ? (int)$parts[1] : 1,
                        'price' => isset($parts[2]) ? (float)$parts[2] : 0,
                    ];
                }
            }
        }

        $order = Procurement::create([
            'supplier_name' => $data['supplier_name'],
            'order_date' => $data['order_date'],
            'notes' => $data['notes'] ?? null,
            'items' => $items,
            'status' => 'pending',
            'total' => array_sum(array_map(function ($it) { return ($it['quantity'] ?? 0) * ($it['price'] ?? 0); }, $items)),
        ]);

        session()->flash('success', 'Pengadaan tersimpan.');
        return redirect()->route('procurements.index');
    }

    public function show($id)
    {
        $order = Procurement::findOrFail($id);
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
