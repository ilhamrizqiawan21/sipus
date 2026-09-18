<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCopyRequest;
use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BookCopyController extends Controller
{
    public function create(Book $book): Response
    {
        return Inertia::render('Master/Form', ['title' => "Tambah Eksemplar — {$book->judul}", 'item' => ['book_id' => $book->id, 'tanggal_masuk' => now()->toDateString(), 'kondisi' => 'baik', 'status' => 'tersedia'], 'fields' => $this->fields($book), 'action' => route('book-copies.store', $book), 'method' => 'post', 'backUrl' => route('books.show', $book)]);
    }

    public function store(BookCopyRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();
        $data['book_id'] = $book->id;
        BookCopy::create($data);

        return redirect()->route('books.show', $book)->with('success', 'Eksemplar berhasil ditambahkan.');
    }

    public function edit(BookCopy $bookCopy): Response
    {
        $bookCopy->load('book');

        return Inertia::render('Master/Form', ['title' => "Edit Eksemplar — {$bookCopy->book->judul}", 'item' => $bookCopy, 'fields' => $this->fields($bookCopy->book), 'action' => route('book-copies.update', $bookCopy), 'method' => 'patch', 'backUrl' => route('books.show', $bookCopy->book)]);
    }

    public function update(BookCopyRequest $request, BookCopy $bookCopy): RedirectResponse
    {
        $bookCopy->update($request->validated());

        return redirect()->route('books.show', $bookCopy->book_id)->with('success', 'Eksemplar berhasil diperbarui.');
    }

    public function destroy(BookCopy $bookCopy): RedirectResponse
    {
        if ($bookCopy->loanItems()->exists()) {
            return back()->with('error', 'Eksemplar tidak dapat dihapus karena memiliki histori transaksi.');
        } $book = $bookCopy->book_id;
        $bookCopy->delete();

        return redirect()->route('books.show', $book)->with('success', 'Eksemplar berhasil dihapus.');
    }

    private function fields(Book $book): array
    {
        return [['name' => 'book_id', 'label' => 'Buku', 'type' => 'select', 'required' => true, 'options' => [['value' => $book->id, 'label' => $book->judul]]], ['name' => 'kode_inventaris', 'label' => 'Kode inventaris', 'required' => true], ['name' => 'lokasi_rak', 'label' => 'Lokasi rak'], ['name' => 'kondisi', 'label' => 'Kondisi', 'type' => 'select', 'required' => true, 'options' => [['value' => 'baik', 'label' => 'Baik'], ['value' => 'rusak_ringan', 'label' => 'Rusak ringan'], ['value' => 'rusak_berat', 'label' => 'Rusak berat'], ['value' => 'hilang', 'label' => 'Hilang']]], ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => [['value' => 'tersedia', 'label' => 'Tersedia'], ['value' => 'dipinjam', 'label' => 'Dipinjam'], ['value' => 'diperbaiki', 'label' => 'Diperbaiki'], ['value' => 'hilang', 'label' => 'Hilang'], ['value' => 'dihapus', 'label' => 'Dihapus']]], ['name' => 'tanggal_masuk', 'label' => 'Tanggal masuk', 'type' => 'date'], ['name' => 'harga_perolehan', 'label' => 'Harga perolehan', 'type' => 'number'], ['name' => 'catatan', 'label' => 'Catatan', 'type' => 'textarea']];
    }
}
