<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
        $book = $this->route('book');

        return ['kode_buku' => ['nullable', 'string', 'max:30', Rule::unique('books', 'kode_buku')->ignore($book)], 'judul' => ['required', 'string', 'max:255'], 'jenis_buku_id' => ['required', 'integer', Rule::exists('book_types', 'id')->where(fn ($query) => $query->where('aktif', true))], 'penerbit_id' => ['nullable', 'integer', 'exists:publishers,id'], 'tahun_terbit' => ['nullable', 'integer', 'between:1000,2100'], 'deskripsi' => ['nullable', 'string'], 'authors' => ['nullable', 'array'], 'authors.*' => ['integer', 'exists:authors,id'], 'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']];
    }
}
