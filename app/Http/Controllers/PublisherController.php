<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublisherController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = Publisher::query()->when($search, fn ($query) => $query->where('nama', 'like', "%{$search}%"))->withCount('books')->orderBy('nama')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'publishers', 'title' => 'Penerbit', 'description' => 'Kelola data penerbit buku.', 'items' => $items, 'columns' => [['key' => 'nama', 'label' => 'Nama Penerbit'], ['key' => 'email', 'label' => 'Email'], ['key' => 'telepon', 'label' => 'Telepon'], ['key' => 'books_count', 'label' => 'Jumlah Buku']], 'createUrl' => route('publishers.create'), 'search' => $search]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(PublisherRequest $request): RedirectResponse
    {
        Publisher::create($request->validated());

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function show(Publisher $publisher): Response
    {
        return Inertia::render('Master/Show', ['resource' => 'publishers', 'title' => 'Detail Penerbit', 'item' => $publisher->loadCount('books'), 'editUrl' => route('publishers.edit', $publisher), 'backUrl' => route('publishers.index')]);
    }

    public function edit(Publisher $publisher): Response
    {
        return Inertia::render('Master/Form', $this->formProps($publisher));
    }

    public function update(PublisherRequest $request, Publisher $publisher): RedirectResponse
    {
        $publisher->update($request->validated());

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Publisher $publisher): RedirectResponse
    {
        if ($publisher->books()->exists()) {
            return back()->with('error', 'Penerbit tidak dapat dihapus karena masih digunakan oleh buku.');
        } $publisher->delete();

        return back()->with('success', 'Penerbit berhasil dihapus.');
    }

    private function formProps(?Publisher $publisher): array
    {
        return ['resource' => 'publishers', 'title' => $publisher ? 'Edit Penerbit' : 'Tambah Penerbit', 'item' => $publisher, 'fields' => [['name' => 'nama', 'label' => 'Nama penerbit', 'required' => true], ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea'], ['name' => 'telepon', 'label' => 'Telepon'], ['name' => 'email', 'label' => 'Email', 'type' => 'email'], ['name' => 'website', 'label' => 'Website'], ['name' => 'catatan', 'label' => 'Catatan', 'type' => 'textarea']], 'action' => $publisher ? route('publishers.update', $publisher) : route('publishers.store'), 'method' => $publisher ? 'patch' : 'post', 'backUrl' => route('publishers.index')];
    }
}
