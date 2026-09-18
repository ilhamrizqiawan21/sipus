<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Publisher>
 */
class PublisherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['nama' => fake()->unique()->company(), 'alamat' => fake()->address(), 'telepon' => fake()->phoneNumber(), 'email' => fake()->safeEmail(), 'website' => fake()->url(), 'catatan' => fake()->sentence()];
    }
}
