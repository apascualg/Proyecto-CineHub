<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cinehub.test'],
            [
                'name' => 'CineHub Admin',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'root@cinehub.test'],
            [
                'name' => 'CineHub Root',
                'role' => 'admin',
                'password' => 'root',
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'user@cinehub.test'],
            [
                'name' => 'CineHub User',
                'role' => 'user',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
    }
}
