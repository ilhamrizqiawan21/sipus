<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookTypeRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => trim((string) $this->input('nama')),
            'kode' => $this->filled('kode') ? strtoupper(trim((string) $this->input('kode'))) : null,
        ]);
    }

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
        $bookType = $this->route('book_type');

        return ['nama' => ['required', 'string', 'max:100', Rule::unique('book_types', 'nama')->ignore($bookType)], 'kode' => ['nullable', 'string', 'max:30', Rule::unique('book_types', 'kode')->ignore($bookType)], 'deskripsi' => ['nullable', 'string'], 'aktif' => ['sometimes', 'boolean']];
    }
}
