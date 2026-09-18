<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
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
        $classroom = $this->route('classroom');

        return ['nama' => ['required', 'string', 'max:50', Rule::unique('classes', 'nama')->where(fn ($query) => $query->where('school_year_id', $this->integer('school_year_id')))->ignore($classroom)], 'tingkat' => ['required', 'string', 'max:20'], 'school_year_id' => ['required', 'integer', 'exists:school_years,id'], 'wali_nama' => ['nullable', 'string', 'max:255'], 'aktif' => ['sometimes', 'boolean']];
    }
}
