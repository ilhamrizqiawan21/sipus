<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use App\Models\Book;
use App\Models\BookCondition;
use App\Models\BookCopyStatus;
use App\Models\Bookshelf;
use App\Models\BookSource;
use App\Models\BorrowingItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookCopyController extends Controller
{
    public function index(Request $request)
    {
        $copies = BookCopy::with('book', 'status', 'condition', 'bookshelf')
            ->orderBy('inventory_code')
            ->paginate(20);

        return view('copies.index', compact('copies'));
    }

    public function create()
    {
        return view('copies.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'inventory_code' => ['required', 'string', 'max:30', Rule::unique('book_copies', 'inventory_code')->whereNull('deleted_at')],
            'barcode' => ['nullable', 'string', 'max:50', Rule::unique('book_copies', 'barcode')->whereNull('deleted_at')],
            'bookshelf_id' => 'nullable|exists:bookshelves,id',
            'condition_id' => 'required|exists:book_conditions,id',
            'source_id' => 'required|exists:book_sources,id',
            'status_id' => 'required|exists:book_copy_statuses,id',
            'acquisition_date' => 'nullable|date',
            'acquisition_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        BookCopy::create($data);
        session()->flash('success', 'Eksamplar berhasil ditambahkan.');
        return redirect()->route('copies.index');
    }

    public function edit($id)
    {
        $copy = BookCopy::with('status', 'condition', 'bookshelf', 'source')->findOrFail($id);

        return view('copies.edit', $this->formData(['copy' => $copy]));
    }

    public function update(Request $request, $id)
    {
        $copy = BookCopy::findOrFail($id);
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'inventory_code' => ['required', 'string', 'max:30', Rule::unique('book_copies', 'inventory_code')->ignore($copy->id)->whereNull('deleted_at')],
            'barcode' => ['nullable', 'string', 'max:50', Rule::unique('book_copies', 'barcode')->ignore($copy->id)->whereNull('deleted_at')],
            'bookshelf_id' => 'nullable|exists:bookshelves,id',
            'condition_id' => 'required|exists:book_conditions,id',
            'source_id' => 'required|exists:book_sources,id',
            'status_id' => 'required|exists:book_copy_statuses,id',
            'acquisition_date' => 'nullable|date',
            'acquisition_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['updated_by'] = auth()->id();
        $copy->update($data);
        session()->flash('success', 'Eksamplar diperbarui.');
        return redirect()->route('copies.index');
    }

    public function destroy($id)
    {
        $copy = BookCopy::findOrFail($id);
        $hasActiveLoan = BorrowingItem::where('book_copy_id', $copy->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($hasActiveLoan) {
            return back()->withErrors(['copy' => 'Eksemplar sedang dipinjam dan tidak bisa dihapus.']);
        }

        $copy->delete();
        session()->flash('success', 'Eksamplar dihapus.');
        return redirect()->route('copies.index');
    }

    private function formData(array $extra = []): array
    {
        return array_merge([
            'books' => Book::select('id', 'title')->orderBy('title')->get(),
            'statuses' => BookCopyStatus::where('is_active', true)->orderBy('name')->get(),
            'conditions' => BookCondition::where('is_active', true)->orderBy('name')->get(),
            'bookshelves' => Bookshelf::where('is_active', true)->orderBy('code')->get(),
            'sources' => BookSource::where('is_active', true)->orderBy('name')->get(),
            'defaultStatusId' => BookCopyStatus::where('code', 'available')->value('id'),
            'defaultConditionId' => BookCondition::where('name', 'Baik')->value('id'),
            'defaultSourceId' => BookSource::orderBy('name')->value('id'),
        ], $extra);
    }

}
