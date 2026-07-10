<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function markPaid(Request $request, Fine $fine)
    {
        if ($fine->status === 'paid') {
            return back()->with('success', 'Denda sudah ditandai lunas.');
        }

        $fine->update([
            'status' => 'paid',
            'paid_amount' => $fine->amount,
            'paid_date' => now()->toDateString(),
            'payment_date' => now()->toDateString(),
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Denda berhasil ditandai lunas.');
    }
}
