<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = Book::query()->with(['bookType:id,nama', 'publisher:id,nama'])->withCount(['copies as total_copies', 'copies as available_copies' => fn ($query) => $query->where('status', 'tersedia')])->when($search, fn ($query) => $query->where(fn ($query) => $query->where('judul', 'like', "%{$search}%")->orWhere('kode_buku', 'like', "%{$search}%")))->latest('id')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'buku', 'title' => 'Buku', 'description' => 'Kelola judul buku, metadata, dan ringkasan stok eksemplar.', 'items' => $items, 'columns' => [['key' => 'kode_buku', 'label' => 'Kode'], ['key' => 'judul', 'label' => 'Judul'], ['key' => 'bookType.nama', 'label' => 'Jenis'], ['key' => 'publisher.nama', 'label' => 'Penerbit'], ['key' => 'total_copies', 'label' => 'Eksemplar'], ['key' => 'available_copies', 'label' => 'Tersedia']], 'createUrl' => route('books.create'), 'search' => $search]);
    }

    public function create(): Response
    {
        return Inertia::render('Books/Form', $this->formProps(null));
    }

    public function store(BookRequest $request): RedirectResponse
    {
        $book = DB::transaction(fn () => $this->saveBook($request));

        return redirect()->route('books.show', $book)->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book): Response
    {
        return Inertia::render('Books/Show', ['book' => $book->load(['bookType', 'publisher', 'authors', 'copies'])->loadCount(['copies as total_copies', 'copies as available_copies' => fn ($query) => $query->where('status', 'tersedia')]), 'editUrl' => route('books.edit', $book), 'backUrl' => route('books.index'), 'copyCreateUrl' => route('book-copies.create', $book)]);
    }

    public function edit(Book $book): Response
    {
        return Inertia::render('Books/Form', $this->formProps($book));
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        DB::transaction(fn () => $this->saveBook($request, $book));

        return redirect()->route('books.show', $book)->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->copies()->whereHas('loanItems')->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena memiliki histori transaksi.');
        } if ($book->copies()->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena masih memiliki eksemplar.');
        } $cover = $book->cover_path;
        $book->delete();
        if ($cover) {
            Storage::disk('public')->delete($cover);
        }

        return back()->with('success', 'Buku berhasil dihapus.');
    }

    private function saveBook(BookRequest $request, ?Book $book = null): Book
    {
        $data = $request->validated();
        $authors = $data['authors'] ?? [];
        unset($data['authors'], $data['cover']);
        $book ??= new Book;
        $oldCover = $book->cover_path;
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('book-covers', 'public');
        }
        $book->fill($data);
        $book->save();
        $book->authors()->sync($authors);
        if ($request->hasFile('cover') && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }

        return $book;
    }

    private function formProps(?Book $book): array
    {
        $item = $book?->load('authors')->toArray();
        if ($item) {
            $item['authors'] = $book->authors->pluck('id')->values();
        }

        return ['title' => $book ? 'Edit Buku' : 'Tambah Buku', 'item' => $item, 'bookTypes' => BookType::query()->where('aktif', true)->orderBy('nama')->get(['id', 'nama']), 'publishers' => Publisher::query()->orderBy('nama')->get(['id', 'nama']), 'authors' => Author::query()->orderBy('nama')->get(['id', 'nama']), 'action' => $book ? route('books.update', $book) : route('books.store'), 'method' => $book ? 'patch' : 'post', 'backUrl' => $book ? route('books.show', $book) : route('books.index')];
    }
}
