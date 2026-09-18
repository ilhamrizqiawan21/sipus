<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryRequest extends FormRequest
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
        $inventory = $this->route('inventory');

        return ['kode_inventaris' => ['required', 'string', 'max:40', Rule::unique('inventories', 'kode_inventaris')->ignore($inventory)], 'nama_barang' => ['required', 'string', 'max:150'], 'jenis' => ['required', 'string', 'max:100'], 'jumlah' => ['required', 'integer', 'min:0'], 'satuan' => ['required', 'string', 'max:30'], 'lokasi' => ['nullable', 'string', 'max:100'], 'kondisi' => ['required', Rule::in(['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])], 'status' => ['required', Rule::in(['aktif', 'diperbaiki', 'dihapus'])], 'tanggal_perolehan' => ['nullable', 'date'], 'harga_perolehan' => ['nullable', 'numeric', 'min:0'], 'catatan' => ['nullable', 'string']];
    }
}
