<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['kode_buku' => fake()->unique()->bothify('BK-#####'), 'judul' => fake()->sentence(4), 'jenis_buku_id' => BookType::factory(), 'tahun_terbit' => fake()->numberBetween(2000, 2026), 'deskripsi' => fake()->paragraph()];
    }
}
