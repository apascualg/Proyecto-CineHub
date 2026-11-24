<?php

namespace Tests\Feature;

use App\Jobs\AutoloadTmdbCatalog;
use App\Models\Title;
use App\Services\TmdbCatalogBatchImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class AutoloadTmdbCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_queues_the_next_batch_until_the_catalog_limit_is_reached(): void
    {
        Queue::fake();
        Title::factory()->published()->create();
        $importer = Mockery::mock(TmdbCatalogBatchImporter::class);
        $importer->shouldReceive('importNextBatch')->with(102)->once()->andReturn(102);

        (new AutoloadTmdbCatalog())->handle($importer);

        Queue::assertPushed(AutoloadTmdbCatalog::class, 1);
    }

    public function test_it_stops_importing_at_2040_published_titles(): void
    {
        Queue::fake();
        Title::factory()->published()->count(2040)->create();
        $importer = Mockery::mock(TmdbCatalogBatchImporter::class);
        $importer->shouldNotReceive('importNextBatch');

        (new AutoloadTmdbCatalog())->handle($importer);

        Queue::assertNothingPushed();
    }
}
