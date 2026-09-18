<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthorController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = Author::query()->when($search, fn ($query) => $query->where('nama', 'like', "%{$search}%"))->withCount('books')->orderBy('nama')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'authors', 'title' => 'Pengarang', 'description' => 'Kelola data pengarang buku.', 'items' => $items, 'columns' => [['key' => 'nama', 'label' => 'Nama Pengarang'], ['key' => 'books_count', 'label' => 'Jumlah Buku']], 'createUrl' => route('authors.create'), 'search' => $search]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(AuthorRequest $request): RedirectResponse
    {
        Author::create($request->validated());

        return redirect()->route('authors.index')->with('success', 'Pengarang berhasil ditambahkan.');
    }

    public function show(Author $author): Response
    {
        return Inertia::render('Master/Show', ['resource' => 'authors', 'title' => 'Detail Pengarang', 'item' => $author->loadCount('books'), 'editUrl' => route('authors.edit', $author), 'backUrl' => route('authors.index')]);
    }

    public function edit(Author $author): Response
    {
        return Inertia::render('Master/Form', $this->formProps($author));
    }

    public function update(AuthorRequest $request, Author $author): RedirectResponse
    {
        $author->update($request->validated());

        return redirect()->route('authors.index')->with('success', 'Pengarang berhasil diperbarui.');
    }

    public function destroy(Author $author): RedirectResponse
    {
        if ($author->books()->exists()) {
            return back()->with('error', 'Pengarang tidak dapat dihapus karena masih digunakan oleh buku.');
        } $author->delete();

        return back()->with('success', 'Pengarang berhasil dihapus.');
    }

    private function formProps(?Author $author): array
    {
        return ['resource' => 'authors', 'title' => $author ? 'Edit Pengarang' : 'Tambah Pengarang', 'item' => $author, 'fields' => [['name' => 'nama', 'label' => 'Nama pengarang', 'required' => true], ['name' => 'bio', 'label' => 'Biografi', 'type' => 'textarea'], ['name' => 'catatan', 'label' => 'Catatan', 'type' => 'textarea']], 'action' => $author ? route('authors.update', $author) : route('authors.store'), 'method' => $author ? 'patch' : 'post', 'backUrl' => route('authors.index')];
    }
}
