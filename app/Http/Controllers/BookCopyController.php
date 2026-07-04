<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use App\Models\Book;
use App\Models\BookCondition;
use App\Models\BookCopyStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookCopyController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureDefaultStatuses();
        $copies = BookCopy::with('book', 'status', 'condition')->paginate(20);
        return view('copies.index', compact('copies'));
    }

    public function create()
    {
        $books = Book::select('id','title')->orderBy('title')->get();
        $this->ensureDefaultStatuses();
        $statuses = BookCopyStatus::orderBy('name')->get();
        $conditions = BookCondition::orderBy('name')->get();
        return view('copies.create', compact('books', 'statuses', 'conditions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'barcode' => ['required', 'string', 'max:100', Rule::unique('book_copies', 'barcode')->whereNull('deleted_at')],
            'location' => 'nullable|string|max:255',
            'status_id' => 'nullable|exists:book_copy_statuses,id',
            'condition_id' => 'nullable|exists:book_conditions,id',
            'acquisition_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data['status_id'] = $data['status_id'] ?? $this->defaultStatusId();
        BookCopy::create($data);
        session()->flash('success', 'Eksamplar berhasil ditambahkan.');
        return redirect()->route('copies.index');
    }

    public function edit($id)
    {
        $copy = BookCopy::with('status', 'condition')->findOrFail($id);
        $books = Book::select('id','title')->orderBy('title')->get();
        $this->ensureDefaultStatuses();
        $statuses = BookCopyStatus::orderBy('name')->get();
        $conditions = BookCondition::orderBy('name')->get();
        return view('copies.edit', compact('copy', 'books', 'statuses', 'conditions'));
    }

    public function update(Request $request, $id)
    {
        $copy = BookCopy::findOrFail($id);
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'barcode' => ['required', 'string', 'max:100', Rule::unique('book_copies', 'barcode')->ignore($copy->id)->whereNull('deleted_at')],
            'location' => 'nullable|string|max:255',
            'status_id' => 'nullable|exists:book_copy_statuses,id',
            'condition_id' => 'nullable|exists:book_conditions,id',
            'acquisition_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data['status_id'] = $data['status_id'] ?? $this->defaultStatusId();
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

    private function defaultStatusId(): ?int
    {
        $this->ensureDefaultStatuses();
        return BookCopyStatus::where('is_available', true)->value('id') ?? BookCopyStatus::value('id');
    }

    private function ensureDefaultStatuses(): void
    {
        BookCopyStatus::firstOrCreate(
            ['name' => 'Available'],
            ['description' => 'Siap dipinjam', 'is_available' => true, 'is_active' => true]
        );
        BookCopyStatus::firstOrCreate(
            ['name' => 'Borrowed'],
            ['description' => 'Sedang dipinjam', 'is_available' => false, 'is_active' => true]
        );
    }
}
