<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookTypeRequest;
use App\Models\BookType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookTypeController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();
        $items = BookType::query()->when($search, fn ($query) => $query->where(fn ($query) => $query->where('nama', 'like', "%{$search}%")->orWhere('kode', 'like', "%{$search}%")))->when(in_array($status, ['active', 'inactive'], true), fn ($query) => $query->where('aktif', $status === 'active'))->withCount('books')->latest('id')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'book-types', 'title' => 'Jenis Buku', 'description' => 'Kelola kategori dan klasifikasi koleksi buku.', 'items' => $items, 'columns' => [['key' => 'nama', 'label' => 'Nama Jenis'], ['key' => 'kode', 'label' => 'Kode'], ['key' => 'books_count', 'label' => 'Jumlah Buku'], ['key' => 'aktif', 'label' => 'Status', 'type' => 'status']], 'createUrl' => route('book-types.create'), 'search' => $search, 'filters' => ['status' => $status]]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(BookTypeRequest $request): RedirectResponse
    {
        BookType::create($request->validated());

        return redirect()->route('book-types.index')->with('success', 'Jenis buku berhasil ditambahkan.');
    }

    public function show(BookType $bookType): Response
    {
        return Inertia::render('Master/Show', ['resource' => 'book-types', 'title' => 'Detail Jenis Buku', 'item' => $bookType->loadCount('books'), 'books' => $bookType->books()->select(['id', 'jenis_buku_id', 'kode_buku', 'judul'])->latest('id')->limit(20)->get(), 'editUrl' => route('book-types.edit', $bookType), 'backUrl' => route('book-types.index')]);
    }

    public function edit(BookType $bookType): Response
    {
        return Inertia::render('Master/Form', $this->formProps($bookType));
    }

    public function update(BookTypeRequest $request, BookType $bookType): RedirectResponse
    {
        $bookType->update($request->validated());

        return redirect()->route('book-types.index')->with('success', 'Jenis buku berhasil diperbarui.');
    }

    public function destroy(BookType $bookType): RedirectResponse
    {
        if ($bookType->books()->exists()) {
            return back()->with('error', 'Jenis buku tidak dapat dihapus karena masih digunakan oleh buku.');
        }

        $bookType->delete();

        return back()->with('success', 'Jenis buku berhasil dihapus.');
    }

    private function formProps(?BookType $bookType): array
    {
        return ['resource' => 'book-types', 'title' => $bookType ? 'Edit Jenis Buku' : 'Tambah Jenis Buku', 'item' => $bookType, 'fields' => [['name' => 'nama', 'label' => 'Nama jenis buku', 'required' => true], ['name' => 'kode', 'label' => 'Kode'], ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea'], ['name' => 'aktif', 'label' => 'Jenis buku aktif', 'type' => 'checkbox', 'default' => true]], 'action' => $bookType ? route('book-types.update', $bookType) : route('book-types.store'), 'method' => $bookType ? 'patch' : 'post', 'backUrl' => route('book-types.index')];
    }
}
