<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InitialMasterDataSeeder::class,
        ]);

        $defaultAdminName = trim((string) config('sipus.default_admin.name', 'Administrator SIPUS')) ?: 'Administrator SIPUS';
        $defaultAdminEmail = trim((string) config('sipus.default_admin.email', 'admin@sipus.local')) ?: 'admin@sipus.local';
        $defaultAdminPassword = (string) config('sipus.default_admin.password', 'password');

        if ($defaultAdminPassword === '') {
            $defaultAdminPassword = 'password';
        }

        User::updateOrCreate([
            'email' => $defaultAdminEmail,
        ], [
            'name' => $defaultAdminName,
            'password' => $defaultAdminPassword,
        ]);
    }
}
