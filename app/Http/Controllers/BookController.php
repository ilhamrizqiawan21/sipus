<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::paginate(15);
        if ($request->wantsJson()) {
            return response()->json($books);
        }
        return view('books.index', ['books' => $books]);
    }

    public function show(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        if ($request->wantsJson()) {
            return response()->json($book);
        }
        return view('books.show', ['book' => $book]);
    }

    public function create(Request $request)
    {
        return view('books.create');
    }

    public function edit(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', ['book' => $book]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'category_id' => 'nullable|integer',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = '/storage/' . $path;
        }

        $data['subtitle'] = $request->input('subtitle');
        $data['publication_year'] = $request->input('publication_year');
        $data['synopsis'] = $request->input('synopsis');

        // ensure category_id exists if DB requires it
        if (empty($data['category_id'])) {
            $data['category_id'] = $this->ensureDefaultCategory();
        }

        $book = Book::create($data);
        if ($request->wantsJson()) {
            return response()->json($book, 201);
        }
        session()->flash('success', 'Buku berhasil ditambahkan.');
        return redirect()->route('books.index');
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'category_id' => 'nullable|integer',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = '/storage/' . $path;
        }

        $data['subtitle'] = $request->input('subtitle');
        $data['publication_year'] = $request->input('publication_year');
        $data['synopsis'] = $request->input('synopsis');

        if (empty($data['category_id'])) {
            $data['category_id'] = $this->ensureDefaultCategory();
        }

        $book->update($data);
        if ($request->wantsJson()) {
            return response()->json($book);
        }
        session()->flash('success', 'Perubahan buku disimpan.');
        return redirect()->route('books.index');
    }

    public function destroy(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Deleted']);
        }
        session()->flash('success', 'Buku dihapus.');
        return redirect()->route('books.index');
    }

    public function importForm()
    {
        return view('books.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $rowCount = 0;
        $created = 0;
        $skipped = 0;
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle);
            if ($header) {
                // normalize header: trim, remove BOM, lowercase
                $header = array_map(function ($h) {
                    $h = trim($h);
                    // strip UTF-8 BOM if present
                    $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
                    return mb_strtolower($h);
                }, $header);

                while (($row = fgetcsv($handle)) !== false) {
                    $rowCount++;
                    if (count($row) !== count($header)) {
                        $skipped++;
                        continue;
                    }
                    $data = array_combine($header, $row);
                    $title = $data['title'] ?? $data['judul'] ?? null;
                    if (empty($title)) {
                        $skipped++;
                        continue;
                    }
                    $bookData = [
                        'title' => $title,
                        'subtitle' => $data['subtitle'] ?? null,
                        'isbn' => $data['isbn'] ?? null,
                        'category_id' => isset($data['category_id']) && is_numeric($data['category_id']) ? (int)$data['category_id'] : null,
                        'publication_year' => $data['publication_year'] ?? null,
                        'synopsis' => $data['synopsis'] ?? null,
                    ];
                    try {
                        if (empty($bookData['category_id'])) {
                            $bookData['category_id'] = $this->ensureDefaultCategory();
                        }
                        Book::create($bookData);
                        $created++;
                    } catch (\Throwable $e) {
                        $skipped++;
                        continue;
                    }
                }
            }
            fclose($handle);
        }
        session()->flash('success', "Import selesai. Baris diproses: {$rowCount}, dibuat: {$created}, dilewati: {$skipped}");
        return redirect()->route('books.index');
    }

    protected function ensureDefaultCategory()
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('categories', 'slug')) {
            $cat = Category::firstOrCreate(
                ['name' => 'Uncategorized'],
                ['slug' => 'uncategorized']
            );
        } else {
            $cat = Category::firstOrCreate(['name' => 'Uncategorized']);
        }
        return $cat->id;
    }
}
