<?php

namespace Database\Factories;

use App\Models\BookType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookType>
 */
class BookTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['nama' => fake()->unique()->words(2, true), 'kode' => fake()->unique()->lexify('BK-???'), 'deskripsi' => fake()->sentence(), 'aktif' => true];
    }
}
