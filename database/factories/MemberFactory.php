<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['nomor_anggota' => fake()->unique()->numerify('SIPUS-#####'), 'jenis_anggota' => 'siswa', 'nama' => fake()->name(), 'nis_nip' => fake()->unique()->numerify('##########'), 'kelas_id' => Classroom::factory(), 'jenis_kelamin' => fake()->randomElement(['L', 'P']), 'tanggal_daftar' => now()->toDateString(), 'status' => true];
    }
}
