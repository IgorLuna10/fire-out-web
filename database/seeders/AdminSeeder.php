<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_DEFAULT_EMAIL', 'admin@fireout.com.br');
        $adminPassword = env('ADMIN_DEFAULT_PASSWORD');

        // Fallback to secure random password if not set via environment
        if (empty($adminPassword)) {
            $adminPassword = bin2hex(random_bytes(12));
            $this->command?->warn("No ADMIN_DEFAULT_PASSWORD set in .env. Generated temporary password: $adminPassword");
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'     => 'Fire Out Admin',
                'email'    => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role'     => 'admin',
                'phone'    => env('ADMIN_DEFAULT_PHONE', null),
            ]
        );
    }
}
