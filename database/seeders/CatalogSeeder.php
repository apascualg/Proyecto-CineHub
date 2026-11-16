<?php

namespace Database\Seeders;

use App\Models\Credit;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $genres = collect([
            ['name' => 'Acción', 'slug' => 'accion'],
            ['name' => 'Animación', 'slug' => 'animacion'],
            ['name' => 'Aventura', 'slug' => 'aventura'],
            ['name' => 'Ciencia ficción', 'slug' => 'ciencia-ficcion'],
            ['name' => 'Comedia', 'slug' => 'comedia'],
            ['name' => 'Drama', 'slug' => 'drama'],
            ['name' => 'Terror', 'slug' => 'terror'],
            ['name' => 'Thriller', 'slug' => 'thriller'],
        ])->mapWithKeys(function (array $genre): array {
            $model = Genre::updateOrCreate(
                ['slug' => $genre['slug']],
                ['name' => $genre['name']],
            );

            return [$model->slug => $model];
        });

        $people = collect([
            'Alicia Moreno',
            'Bruno Vidal',
            'Clara Navarro',
            'Diego Ferrer',
            'Elena Campos',
            'Fabián Soler',
            'Gabriela Costa',
            'Hugo Molina',
            'Irene Ríos',
            'Javier Serra',
        ])->mapWithKeys(function (string $name): array {
            $person = Person::firstOrCreate(
                ['name' => $name],
                [
                    'biography' => 'Profesional del cine y la televisión.',
                    'birth_date' => fake()->dateTimeBetween('-70 years', '-25 years')->format('Y-m-d'),
                ],
            );

            return [$person->name => $person];
        });

        $titles = [
            [
                'type' => 'movie',
                'title' => 'Horizonte de ceniza',
                'slug' => 'horizonte-de-ceniza',
                'synopsis' => 'Una expedición descubre una señal imposible bajo una ciudad abandonada.',
                'release_date' => '2024-03-15',
                'duration_minutes' => 124,
                'genre_slugs' => ['ciencia-ficcion', 'thriller'],
            ],
            [
                'type' => 'movie',
                'title' => 'El último verano',
                'slug' => 'el-ultimo-verano',
                'synopsis' => 'Tres amigos se reencuentran para resolver una promesa pendiente.',
                'release_date' => '2023-07-21',
                'duration_minutes' => 108,
                'genre_slugs' => ['comedia', 'drama'],
            ],
            [
                'type' => 'movie',
                'title' => 'La ciudad sumergida',
                'slug' => 'la-ciudad-sumergida',
                'synopsis' => 'Una arqueóloga busca respuestas en una ciudad cubierta por el océano.',
                'release_date' => '2022-11-04',
                'duration_minutes' => 136,
                'genre_slugs' => ['aventura', 'drama'],
            ],
            [
                'type' => 'movie',
                'title' => 'Noche de acero',
                'slug' => 'noche-de-acero',
                'synopsis' => 'Un detective debe proteger a un testigo durante una noche interminable.',
                'release_date' => '2021-10-08',
                'duration_minutes' => 117,
                'genre_slugs' => ['accion', 'thriller'],
            ],
            [
                'type' => 'series',
                'title' => 'Archivos del norte',
                'slug' => 'archivos-del-norte',
                'synopsis' => 'Un equipo investiga sucesos extraños en pueblos aislados.',
                'release_date' => '2024-01-12',
                'seasons_count' => 2,
                'episodes_count' => 16,
                'genre_slugs' => ['terror', 'thriller'],
            ],
            [
                'type' => 'series',
                'title' => 'Órbita cero',
                'slug' => 'orbita-cero',
                'synopsis' => 'La tripulación de una estación espacial recibe un mensaje del futuro.',
                'release_date' => '2023-05-19',
                'seasons_count' => 3,
                'episodes_count' => 24,
                'genre_slugs' => ['ciencia-ficcion', 'aventura'],
            ],
            [
                'type' => 'series',
                'title' => 'Barrio central',
                'slug' => 'barrio-central',
                'synopsis' => 'Historias cruzadas de los vecinos de un barrio en transformación.',
                'release_date' => '2022-09-02',
                'seasons_count' => 1,
                'episodes_count' => 10,
                'genre_slugs' => ['comedia', 'drama'],
            ],
            [
                'type' => 'movie',
                'title' => 'Borrador pendiente',
                'slug' => 'borrador-pendiente',
                'synopsis' => 'Contenido de prueba no publicado para comprobar los filtros de estado.',
                'release_date' => '2025-01-01',
                'duration_minutes' => 95,
                'status' => 'draft',
                'genre_slugs' => ['drama'],
            ],
        ];

        foreach ($titles as $data) {
            $genreSlugs = $data['genre_slugs'];
            unset($data['genre_slugs']);

            $title = Title::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge([
                    'original_title' => $data['title'],
                    'status' => 'published',
                    'poster_path' => null,
                    'backdrop_path' => null,
                    'trailer_url' => null,
                ], $data),
            );

            $title->genres()->sync(
                collect($genreSlugs)->map(fn (string $slug) => $genres[$slug]->id)->all(),
            );

            $director = $people->values()->get($title->id % $people->count());
            Credit::updateOrCreate(
                [
                    'title_id' => $title->id,
                    'person_id' => $director->id,
                    'role_type' => $data['type'] === 'series' ? 'creator' : 'director',
                ],
                [
                    'character_name' => null,
                    'billing_order' => 1,
                ],
            );

            foreach ($people->values()->skip(1)->take(3) as $index => $person) {
                Credit::updateOrCreate(
                    [
                        'title_id' => $title->id,
                        'person_id' => $person->id,
                        'role_type' => 'actor',
                    ],
                    [
                        'character_name' => 'Personaje '.($index + 1),
                        'billing_order' => $index + 2,
                    ],
                );
            }
        }
    }
}
