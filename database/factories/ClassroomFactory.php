<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['nama' => fake()->unique()->bothify('Kelas ##'), 'tingkat' => fake()->randomElement(['VII', 'VIII', 'IX']), 'school_year_id' => SchoolYear::factory(), 'wali_nama' => fake()->name(), 'aktif' => true];
    }
}
