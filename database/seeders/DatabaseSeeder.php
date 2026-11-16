<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Title;
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
        User::factory()->create([
            'name' => 'CineHub Admin',
            'email' => 'admin@cinehub.test',
            'role' => 'admin',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'CineHub User',
            'email' => 'user@cinehub.test',
            'role' => 'user',
            'password' => 'password',
        ]);

        $genres = collect(['Acción', 'Drama', 'Ciencia ficción', 'Animación'])
            ->mapWithKeys(fn (string $name) => [
                $name => Genre::updateOrCreate(
                    ['slug' => str($name)->slug()],
                    ['name' => $name],
                ),
            ]);

        $catalog = [
            [
                'title' => 'La ciudad de las sombras',
                'slug' => 'la-ciudad-de-las-sombras',
                'type' => 'movie',
                'release_date' => '2024-04-12',
                'genre' => 'Drama',
            ],
            [
                'title' => 'Seis pasos a la luna',
                'slug' => 'seis-pasos-a-la-luna',
                'type' => 'series',
                'release_date' => '2023-09-01',
                'genre' => 'Ciencia ficción',
            ],
            [
                'title' => 'Velocidad del amanecer',
                'slug' => 'velocidad-del-amanecer',
                'type' => 'movie',
                'release_date' => '2022-06-24',
                'genre' => 'Acción',
            ],
            [
                'title' => 'Horizonte paralelo',
                'slug' => 'horizonte-paralelo',
                'type' => 'series',
                'release_date' => '2024-11-08',
                'genre' => 'Drama',
            ],
        ];

        foreach ($catalog as $item) {
            $genre = $genres->get($item['genre']);
            unset($item['genre']);

            $title = Title::updateOrCreate(
                ['slug' => $item['slug']],
                [...$item, 'status' => 'published'],
            );

            $title->genres()->syncWithoutDetaching([$genre->id]);
        }
    }
}
