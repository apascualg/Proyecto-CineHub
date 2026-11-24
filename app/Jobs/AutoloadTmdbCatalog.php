<?php

namespace App\Jobs;

use App\Models\Title;
use App\Services\TmdbCatalogBatchImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoloadTmdbCatalog implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public const MAX_CATALOG_TITLES = 2040;

    private const BATCH_SIZE = 102;

    public int $tries = 3;

    public int $timeout = 80;

    public int $uniqueFor = 1800;

    public function uniqueId(): string
    {
        return 'tmdb-catalog-autoload';
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(TmdbCatalogBatchImporter $importer): void
    {
        $count = Title::query()->where('status', 'published')->count();
        if ($count >= self::MAX_CATALOG_TITLES) {
            return;
        }

        $imported = $importer->importNextBatch(min(self::BATCH_SIZE, self::MAX_CATALOG_TITLES - $count));

        if (
            $imported > 0
            && Title::query()->where('status', 'published')->count() < self::MAX_CATALOG_TITLES
        ) {
            self::dispatch();
        }
    }
}
