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
        return view('books.create', [
            'categories' => $this->bookFormCategories(),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', [
            'book' => $book,
            'categories' => $this->bookFormCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'category_id' => 'nullable|integer|exists:categories,id',
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
            'category_id' => 'nullable|integer|exists:categories,id',
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
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('file');
        $rowCount = 0;
        $created = 0;
        $skipped = 0;

        try {
            $rows = $this->readImportRows($file->getRealPath(), $file->getClientOriginalExtension());
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['file' => 'File import tidak bisa dibaca. Pastikan formatnya CSV atau XLSX dengan baris header.'])
                ->withInput();
        }

        foreach ($rows as $data) {
            $rowCount++;
            $title = $this->emptyToNull($data['title'] ?? $data['judul'] ?? null);
            if (empty($title)) {
                $skipped++;
                continue;
            }

            $bookData = [
                'title' => $title,
                'subtitle' => $this->emptyToNull($data['subtitle'] ?? null),
                'isbn' => $this->emptyToNull($data['isbn'] ?? null),
                'category_id' => $this->resolveImportCategoryId($data),
                'publication_year' => $this->normalizePublicationYear($data['publication_year'] ?? $data['tahun_terbit'] ?? null),
                'synopsis' => $this->emptyToNull($data['synopsis'] ?? $data['sinopsis'] ?? null),
            ];

            try {
                Book::create($bookData);
                $created++;
            } catch (\Throwable $e) {
                $skipped++;
                continue;
            }
        }

        if ($rowCount === 0) {
            return back()
                ->withErrors(['file' => 'File import tidak berisi data buku.'])
                ->withInput();
        }

        session()->flash('success', "Import selesai. Baris diproses: {$rowCount}, dibuat: {$created}, dilewati: {$skipped}");
        return redirect()->route('books.index');
    }

    protected function readImportRows(string $path, string $extension): array
    {
        $extension = strtolower($extension);

        if ($extension === 'xlsx') {
            return $this->readXlsxRows($path);
        }

        return $this->readCsvRows($path);
    }

    protected function readCsvRows(string $path): array
    {
        $rows = [];

        if (($handle = fopen($path, 'r')) === false) {
            return $rows;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return $rows;
        }

        $header = $this->normalizeImportHeader($header);
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), null);
            }

            if (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }

            $rows[] = array_combine($header, $row);
        }
        fclose($handle);

        return $rows;
    }

    protected function readXlsxRows(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = $this->readXlsxSharedStrings($zip);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $sheet = simplexml_load_string($sheetXml);
        if (!$sheet || !isset($sheet->sheetData->row)) {
            return [];
        }

        $rawRows = [];
        foreach ($sheet->sheetData->row as $row) {
            $values = [];
            foreach ($row->c as $cell) {
                $cellRef = (string) $cell['r'];
                $columnIndex = $this->xlsxColumnIndex($cellRef);
                $values[$columnIndex] = $this->xlsxCellValue($cell, $sharedStrings);
            }

            if (!empty($values)) {
                ksort($values);
                $rawRows[] = array_replace(array_fill(0, max(array_keys($values)) + 1, null), $values);
            }
        }

        if (empty($rawRows)) {
            return [];
        }

        $header = $this->normalizeImportHeader(array_shift($rawRows));
        $rows = [];
        foreach ($rawRows as $row) {
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), null);
            }

            if (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }

            $rows[] = array_combine($header, $row);
        }

        return $rows;
    }

    protected function readXlsxSharedStrings(\ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }

        $sharedStrings = [];
        $strings = simplexml_load_string($xml);
        if (!$strings) {
            return [];
        }

        foreach ($strings->si as $string) {
            if (isset($string->t)) {
                $sharedStrings[] = (string) $string->t;
                continue;
            }

            $parts = [];
            foreach ($string->r as $run) {
                $parts[] = (string) $run->t;
            }
            $sharedStrings[] = implode('', $parts);
        }

        return $sharedStrings;
    }

    protected function xlsxCellValue(\SimpleXMLElement $cell, array $sharedStrings): ?string
    {
        $type = (string) $cell['t'];

        if ($type === 'inlineStr') {
            return isset($cell->is->t) ? (string) $cell->is->t : null;
        }

        $value = isset($cell->v) ? (string) $cell->v : null;
        if ($value === null) {
            return null;
        }

        if ($type === 's') {
            return $sharedStrings[(int) $value] ?? null;
        }

        return $value;
    }

    protected function xlsxColumnIndex(string $cellRef): int
    {
        preg_match('/^[A-Z]+/i', $cellRef, $matches);
        $letters = strtoupper($matches[0] ?? 'A');
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return $index - 1;
    }

    protected function normalizeImportHeader(array $header): array
    {
        return array_map(function ($h) {
            $h = trim((string) $h);
            $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
            return mb_strtolower($h);
        }, $header);
    }

    protected function resolveImportCategoryId(array $data): int
    {
        $categoryName = $this->emptyToNull($data['category'] ?? $data['kategori'] ?? null);
        if ($categoryName) {
            return Category::firstOrCreate(['name' => $categoryName])->id;
        }

        $categoryId = $this->emptyToNull($data['category_id'] ?? null);
        if ($categoryId && is_numeric($categoryId)) {
            $category = Category::find((int) $categoryId);
            if ($category) {
                return $category->id;
            }
        }

        return $this->ensureDefaultCategory();
    }

    protected function normalizePublicationYear($year): ?int
    {
        $year = $this->emptyToNull($year);
        if (!$year || !is_numeric($year)) {
            return null;
        }

        $year = (int) $year;
        return $year >= 1000 && $year <= 9999 ? $year : null;
    }

    protected function emptyToNull($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
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

    protected function bookFormCategories()
    {
        return Category::orderBy('name')->get();
    }
}
