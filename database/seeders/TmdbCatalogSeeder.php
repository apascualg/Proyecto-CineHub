<?php

namespace Database\Seeders;

use App\Services\TmdbService;
use App\Services\TmdbTitleImporter;
use Illuminate\Database\Seeder;
use Throwable;

class TmdbCatalogSeeder extends Seeder
{
    private const GENRES = [
        28 => 'Acción',
        12 => 'Aventura',
        16 => 'Animación',
        35 => 'Comedia',
        80 => 'Crimen',
        18 => 'Drama',
        14 => 'Fantasía',
        27 => 'Terror',
        878 => 'Ciencia ficción',
    ];

    public function run(): void
    {
        $tmdb = app(TmdbService::class);
        $importer = app(TmdbTitleImporter::class);

        $this->importTopRated($tmdb, $importer, 'movie', 50);

        foreach (self::GENRES as $genreId => $genreName) {
            $this->importTopByGenre($tmdb, $importer, 'movie', $genreId, 20);
            $this->importTopByGenre($tmdb, $importer, 'series', $genreId, 20);
        }

        $this->command?->info('Catálogo TMDB importado o actualizado.');
    }

    private function importTopRated(
        TmdbService $tmdb,
        TmdbTitleImporter $importer,
        string $type,
        int $limit,
    ): void {
        $this->importDiscoverResults(
            $tmdb,
            $importer,
            $type,
            $limit,
            [],
        );
    }

    private function importTopByGenre(
        TmdbService $tmdb,
        TmdbTitleImporter $importer,
        string $type,
        int $genreId,
        int $limit,
    ): void {
        $this->importDiscoverResults(
            $tmdb,
            $importer,
            $type,
            $limit,
            ['with_genres' => $genreId],
        );
    }

    private function importDiscoverResults(
        TmdbService $tmdb,
        TmdbTitleImporter $importer,
        string $type,
        int $limit,
        array $parameters,
    ): void {
        $imported = 0;

        for ($page = 1; $imported < $limit; $page++) {
            $results = $tmdb->discover($type, [
                ...$parameters,
                'page' => $page,
            ]);

            if ($results === []) {
                break;
            }

            foreach ($results as $result) {
                try {
                    $details = $type === 'series'
                        ? $tmdb->series((int) $result['id'])
                        : $tmdb->movie((int) $result['id']);

                    $importer->import($details, $type);
                    $imported++;
                } catch (Throwable $exception) {
                    report($exception);
                }

                if ($imported >= $limit) {
                    break;
                }
            }

            usleep(250000);
        }
    }
}
