<?php

namespace App\Models;

use Database\Factories\InventoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode_inventaris', 'nama_barang', 'jenis', 'jumlah', 'satuan', 'lokasi', 'kondisi', 'status', 'tanggal_perolehan', 'harga_perolehan', 'catatan'])]
class Inventory extends Model
{
    /** @use HasFactory<InventoryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'tanggal_perolehan' => 'date',
            'harga_perolehan' => 'decimal:2',
        ];
    }
}
