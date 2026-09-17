<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sipus:reset-admin-password
                            {username : Username admin yang akan diubah}
                            {--password= : Password baru, minimal 8 karakter}')]
#[Description('Mengatur ulang password akun admin SIPUS.')]
class ResetAdminPassword extends Command
{
    public function handle(): int
    {
        $user = User::query()
            ->where('username', $this->argument('username'))
            ->where('role', 'admin')
            ->first();

        if ($user === null) {
            $this->error('Akun admin tidak ditemukan.');

            return self::FAILURE;
        }

        $password = $this->option('password');
        $password = is_string($password) && $password !== ''
            ? $password
            : $this->secret('Password baru');

        if (! is_string($password) || mb_strlen($password) < 8) {
            $this->error('Password minimal 8 karakter.');

            return self::FAILURE;
        }

        $user->update(['password' => $password]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'admin_password_reset',
            'description' => 'Password admin diatur ulang melalui command.',
        ]);

        $this->info('Password admin berhasil diatur ulang.');

        return self::SUCCESS;
    }
}
