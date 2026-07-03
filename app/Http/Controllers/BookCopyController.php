<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use App\Models\Book;
use Illuminate\Http\Request;

class BookCopyController extends Controller
{
    public function index(Request $request)
    {
        $copies = BookCopy::with('book')->paginate(20);
        return view('copies.index', compact('copies'));
    }

    public function create()
    {
        $books = Book::select('id','title')->orderBy('title')->get();
        return view('copies.create', compact('books'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|integer',
            'barcode' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        BookCopy::create($data);
        session()->flash('success', 'Eksamplar berhasil ditambahkan.');
        return redirect()->route('copies.index');
    }

    public function edit($id)
    {
        $copy = BookCopy::findOrFail($id);
        $books = Book::select('id','title')->orderBy('title')->get();
        return view('copies.edit', compact('copy','books'));
    }

    public function update(Request $request, $id)
    {
        $copy = BookCopy::findOrFail($id);
        $data = $request->validate([
            'book_id' => 'required|integer',
            'barcode' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $copy->update($data);
        session()->flash('success', 'Eksamplar diperbarui.');
        return redirect()->route('copies.index');
    }

    public function destroy($id)
    {
        $copy = BookCopy::findOrFail($id);
        $copy->delete();
        session()->flash('success', 'Eksamplar dihapus.');
        return redirect()->route('copies.index');
    }
}
