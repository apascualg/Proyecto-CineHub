<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_only_runs_the_tmdb_catalog_seeder(): void
    {
        $this->seed();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('titles', 0);
    }

    public function test_database_seeder_is_repeatable(): void
    {
        $this->seed();
        $this->seed();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('titles', 0);
    }
}
