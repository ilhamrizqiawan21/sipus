<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
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
        $member = $this->route('member');
        $userId = $member?->user_id;

        return [
            'nomor_anggota' => ['required', 'string', 'max:40', Rule::unique('members', 'nomor_anggota')->ignore($member)],
            'jenis_anggota' => ['required', Rule::in(['siswa', 'guru'])],
            'nama' => ['required', 'string', 'max:100'],
            'nis_nip' => ['nullable', 'string', 'max:50', Rule::unique('members', 'nis_nip')->ignore($member)],
            'kelas_id' => ['nullable', 'integer', 'exists:classes,id'],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'tanggal_daftar' => ['required', 'date'],
            'status' => ['sometimes', 'boolean'],
            'username' => ['nullable', 'string', 'min:3', 'max:50', Rule::unique('users', 'username')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', Rule::requiredIf(fn () => filled($this->input('username')) && $userId === null)],
        ];
    }
}
