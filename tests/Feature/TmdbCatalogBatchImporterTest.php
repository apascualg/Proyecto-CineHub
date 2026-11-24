<?php

namespace Tests\Feature;

use App\Models\Title;
use App\Services\TmdbCatalogBatchImporter;
use App\Services\TmdbService;
use App\Services\TmdbTitleImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class TmdbCatalogBatchImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_batch_imports_new_titles_and_resumes_after_the_previous_cursor(): void
    {
        $tmdb = Mockery::mock(TmdbService::class);
        $tmdb->shouldReceive('discover')
            ->andReturnUsing(function (string $type, array $parameters): array {
                $base = $type === 'movie' ? 1000 : 2000;
                $offset = (($parameters['page'] ?? 1) - 1) * 20;

                return array_map(
                    fn (int $index): array => ['id' => $base + $offset + $index],
                    range(1, 20),
                );
            });
        $tmdb->shouldReceive('movie')
            ->andReturnUsing(fn (int $id): array => ['id' => $id]);
        $tmdb->shouldReceive('series')
            ->andReturnUsing(fn (int $id): array => ['id' => $id]);

        $importer = Mockery::mock(TmdbTitleImporter::class);
        $importer->shouldReceive('import')
            ->times(8)
            ->andReturnUsing(function (array $data, string $type): Title {
                return Title::factory()->published()->create([
                    'tmdb_id' => $data['id'],
                    'source' => 'tmdb',
                    'type' => $type,
                ]);
            });

        $batchImporter = new TmdbCatalogBatchImporter($tmdb, $importer);

        $this->assertSame(4, $batchImporter->importBatch(1, 4));
        $this->assertSame(4, $batchImporter->importBatch(2, 4));
        $this->assertSame(4, $batchImporter->importBatch(1, 4));
        $this->assertDatabaseCount('titles', 8);
    }
}
