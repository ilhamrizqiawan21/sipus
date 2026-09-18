<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookCopy>
 */
class BookCopyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['book_id' => Book::factory(), 'kode_inventaris' => fake()->unique()->bothify('INV-BK-#####'), 'lokasi_rak' => fake()->bothify('Rak ?-##'), 'kondisi' => 'baik', 'status' => 'tersedia', 'tanggal_masuk' => now()->toDateString(), 'harga_perolehan' => fake()->numberBetween(10000, 500000)];
    }
}
