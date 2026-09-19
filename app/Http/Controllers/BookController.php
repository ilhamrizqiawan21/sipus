<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Publisher;
use App\Services\BookSpreadsheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookController extends Controller
{
    public function __construct(private readonly BookSpreadsheetService $spreadsheet) {}

    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = Book::query()->with(['bookType:id,nama', 'publisher:id,nama'])->withCount(['copies as total_copies', 'copies as available_copies' => fn ($query) => $query->where('status', 'tersedia')])->when($search, fn ($query) => $query->where(fn ($query) => $query->where('judul', 'like', "%{$search}%")->orWhere('kode_buku', 'like', "%{$search}%")))->latest('id')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'buku', 'title' => 'Buku', 'description' => 'Kelola judul buku, metadata, dan ringkasan stok eksemplar.', 'items' => $items, 'columns' => [['key' => 'kode_buku', 'label' => 'Kode'], ['key' => 'judul', 'label' => 'Judul'], ['key' => 'book_type.nama', 'label' => 'Jenis'], ['key' => 'publisher.nama', 'label' => 'Penerbit'], ['key' => 'total_copies', 'label' => 'Eksemplar'], ['key' => 'available_copies', 'label' => 'Tersedia']], 'createUrl' => route('books.create'), 'search' => $search, 'importUrl' => route('books.import'), 'importPreviewUrl' => route('books.import.preview'), 'templateUrl' => route('books.import.template')]);
    }

    public function import(): Response
    {
        return Inertia::render('Books/Import', ['templateUrl' => route('books.import.template'), 'backUrl' => route('books.index')]);
    }

    public function previewImport(Request $request): Response
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx', 'max:5120']]);
        $rows = $this->spreadsheet->validateRows($this->spreadsheet->parse($request->file('file')));
        $token = (string) Str::uuid();
        Storage::disk('local')->put("book-imports/{$token}.json", json_encode($rows, JSON_THROW_ON_ERROR));

        return Inertia::render('Books/ImportPreview', ['token' => $token, 'rows' => $rows, 'backUrl' => route('books.import')]);
    }

    public function confirmImport(Request $request): RedirectResponse
    {
        $data = $request->validate(['token' => ['required', 'uuid']]);
        $path = "book-imports/{$data['token']}.json";
        abort_unless(Storage::disk('local')->exists($path), 404, 'Preview import sudah kedaluwarsa.');
        $rows = json_decode(Storage::disk('local')->get($path), true, 512, JSON_THROW_ON_ERROR);
        $validRows = collect($this->spreadsheet->validateRows($rows))->where('_valid', true)->values();
        DB::transaction(function () use ($validRows): void {
            $validRows->each(fn (array $row) => $this->saveImportedBook($row));
        });
        Storage::disk('local')->delete($path);

        return redirect()->route('books.index')->with('success', "{$validRows->count()} buku berhasil diimport.");
    }

    public function template(): BinaryFileResponse
    {
        return response()->download($this->spreadsheet->templatePath(), 'template-buku.xlsx')->deleteFileAfterSend(true);
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

    private function saveImportedBook(array $row): void
    {
        $book = Book::create(['kode_buku' => $row['kode_buku'], 'judul' => $row['judul'], 'jenis_buku_id' => $row['_jenis_buku_id'], 'penerbit_id' => $row['penerbit'] ? Publisher::where('nama', $row['penerbit'])->value('id') : null, 'tahun_terbit' => $row['tahun_terbit'] ?? null, 'deskripsi' => $row['deskripsi'] ?? null]);
        $authors = collect(explode(',', (string) ($row['pengarang'] ?? '')))->map(fn (string $name) => trim($name))->filter()->map(fn (string $name) => Author::where('nama', $name)->value('id'))->filter()->values();
        $book->authors()->sync($authors);
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
