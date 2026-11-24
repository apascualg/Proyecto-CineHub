<?php

namespace App\Services;

use App\Models\Title;
use Illuminate\Support\Facades\Cache;

class TmdbCatalogBatchImporter
{
    private const BATCH_KEY_PREFIX = 'tmdb.catalog.v3.batch.';
    private const NEXT_BATCH_KEY = 'tmdb.catalog.v3.next-batch';

    public function __construct(
        private TmdbService $tmdb,
        private TmdbTitleImporter $importer,
    ) {
    }

    public function importBatch(int $batchNumber, int $size = 102): int
    {
        if ($batchNumber < 1) {
            return 0;
        }

        $batchKey = self::BATCH_KEY_PREFIX.$batchNumber;
        $lock = Cache::lock('tmdb.catalog.v3.import-lock', 300);

        return $lock->block(75, function () use ($batchKey, $batchNumber, $size): int {
            if (Cache::has($batchKey)) {
                $this->advanceNextBatchNumber();

                return (int) Cache::get($batchKey);
            }

            $movieCount = intdiv($size, 2);
            $seriesCount = $size - $movieCount;
            $imported = $this->importDiscoverResults('movie', $movieCount);
            $imported += $this->importDiscoverResults('series', $seriesCount);

            Cache::forever($batchKey, $imported);
            $this->advanceNextBatchNumber();

            return $imported;
        });
    }

    public function importNextBatch(int $size = 102): int
    {
        return $this->importBatch($this->nextBatchNumber(), $size);
    }

    public function nextBatchNumber(): int
    {
        $batchNumber = (int) Cache::get(self::NEXT_BATCH_KEY, 1);

        while (Cache::has(self::BATCH_KEY_PREFIX.$batchNumber)) {
            $batchNumber++;
        }

        Cache::forever(self::NEXT_BATCH_KEY, $batchNumber);

        return $batchNumber;
    }

    private function advanceNextBatchNumber(): void
    {
        $this->nextBatchNumber();
    }

    private function importDiscoverResults(string $type, int $limit): int
    {
        $imported = 0;
        $cursorKey = "tmdb.catalog.v3.cursor.{$type}";
        $cursor = Cache::get($cursorKey, ['page' => 1, 'offset' => 0]);

        while ($imported < $limit) {
            $results = $this->tmdb->discover($type, ['page' => $cursor['page']]);

            if ($results === []) {
                break;
            }

            foreach (array_slice($results, $cursor['offset']) as $result) {
                $tmdbId = (int) $result['id'];

                if (! Title::where('tmdb_id', $tmdbId)->exists()) {
                    $details = $type === 'series'
                        ? $this->tmdb->series($tmdbId)
                        : $this->tmdb->movie($tmdbId);
                    $this->importer->import($details, $type);
                    $imported++;
                }

                $cursor['offset']++;

                if ($cursor['offset'] >= count($results)) {
                    $cursor['page']++;
                    $cursor['offset'] = 0;
                }

                Cache::forever($cursorKey, $cursor);

                if ($imported >= $limit) {
                    break;
                }
            }

            if ($cursor['offset'] === 0) {
                usleep(100000);
            }
        }

        return $imported;
    }
}
