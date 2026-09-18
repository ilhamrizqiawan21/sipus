<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchoolYearRequest extends FormRequest
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
        $schoolYear = $this->route('school_year');

        return ['nama' => ['required', 'string', 'max:20', Rule::unique('school_years', 'nama')->where(fn ($query) => $query->where('semester', $this->input('semester')))->ignore($schoolYear)], 'semester' => ['required', Rule::in(['1', '2'])], 'is_aktif' => ['sometimes', 'boolean'], 'mulai' => ['required', 'date'], 'selesai' => ['required', 'date', 'after_or_equal:mulai']];
    }
}
