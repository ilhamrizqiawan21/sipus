<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['kode_inventaris' => fake()->unique()->bothify('INV-#####'), 'nama_barang' => fake()->words(2, true), 'jenis' => 'Perlengkapan', 'jumlah' => fake()->numberBetween(1, 20), 'satuan' => 'unit', 'lokasi' => 'Ruang perpustakaan', 'kondisi' => 'baik', 'status' => 'aktif', 'tanggal_perolehan' => now()->toDateString(), 'harga_perolehan' => fake()->numberBetween(10000, 1000000)];
    }
}
