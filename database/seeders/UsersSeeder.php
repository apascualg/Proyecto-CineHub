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
            ],
        );

        User::updateOrCreate(
            ['email' => 'user@cinehub.test'],
            [
                'name' => 'CineHub User',
                'role' => 'user',
                'password' => 'password',
            ],
        );
    }
}
