<?php

namespace Database\Seeders;

use App\Services\TmdbService;
use App\Services\TmdbCatalogBatchImporter;
use Illuminate\Database\Seeder;
use RuntimeException;

class TmdbCatalogSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            $tmdb = app(TmdbService::class);
        } catch (RuntimeException $exception) {
            $this->command?->warn($exception->getMessage());

            return;
        }

        app(TmdbCatalogBatchImporter::class)->importBatch(1, 102);

        $this->command?->info('Catálogo TMDB importado o actualizado.');
    }
}
