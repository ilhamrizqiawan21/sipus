<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookCopyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $copy = $this->route('book_copy');

        return ['book_id' => ['required', 'integer', 'exists:books,id'], 'kode_inventaris' => ['required', 'string', 'max:40', Rule::unique('book_copies', 'kode_inventaris')->ignore($copy)], 'lokasi_rak' => ['nullable', 'string', 'max:100'], 'kondisi' => ['required', Rule::in(['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])], 'status' => ['required', Rule::in(['tersedia', 'dipinjam', 'diperbaiki', 'hilang', 'dihapus'])], 'tanggal_masuk' => ['nullable', 'date'], 'harga_perolehan' => ['nullable', 'numeric', 'min:0'], 'catatan' => ['nullable', 'string']];
    }
}
