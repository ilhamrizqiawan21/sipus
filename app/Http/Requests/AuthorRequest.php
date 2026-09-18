<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorRequest extends FormRequest
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
        $author = $this->route('author');

        return ['nama' => ['required', 'string', 'max:150', Rule::unique('authors', 'nama')->ignore($author)], 'bio' => ['nullable', 'string'], 'catatan' => ['nullable', 'string']];
    }
}
