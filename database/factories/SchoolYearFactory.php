<?php

namespace Database\Factories;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolYear>
 */
class SchoolYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['nama' => fake()->unique()->numerify('20##/20##'), 'semester' => fake()->randomElement(['1', '2']), 'is_aktif' => false, 'mulai' => now()->startOfYear(), 'selesai' => now()->endOfYear()];
    }
}
