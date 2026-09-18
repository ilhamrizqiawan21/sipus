<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublisherRequest extends FormRequest
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
        $publisher = $this->route('publisher');

        return ['nama' => ['required', 'string', 'max:150', Rule::unique('publishers', 'nama')->ignore($publisher)], 'alamat' => ['nullable', 'string'], 'telepon' => ['nullable', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:255'], 'website' => ['nullable', 'url', 'max:255'], 'catatan' => ['nullable', 'string']];
    }
}
