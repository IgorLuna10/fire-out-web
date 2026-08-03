<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fireout.com.br'],
            [
                'name'     => 'Fire Out Admin',
                'email'    => 'fireout@fireout.com.br',
                'password' => Hash::make('FireOut@1996!'),
                'role'     => 'admin',
                'phone'    => '(21) 96696-9836',
            ]
        );
    }
}
