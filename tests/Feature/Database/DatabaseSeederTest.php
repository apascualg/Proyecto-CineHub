<?php

namespace Tests\Feature\Database;

use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_demo_users_and_catalog(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@cinehub.test',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'user@cinehub.test',
            'role' => 'user',
        ]);
        $this->assertDatabaseCount('genres', 8);
        $this->assertDatabaseCount('people', 10);
        $this->assertDatabaseCount('titles', 8);
        $this->assertDatabaseCount('credits', 32);
        $this->assertDatabaseCount('ratings', 4);
        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseCount('watchlists', 1);
        $this->assertDatabaseCount('watchlist_title', 3);
    }

    public function test_database_seeder_is_repeatable(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('titles', 8);
        $this->assertDatabaseCount('ratings', 4);
        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseCount('watchlists', 1);
        $this->assertDatabaseCount('watchlist_title', 3);
    }

    public function test_seeded_titles_have_genres_and_credits(): void
    {
        $this->seed();

        $title = Title::where('slug', 'horizonte-de-ceniza')->firstOrFail();

        $this->assertGreaterThanOrEqual(2, $title->genres()->count());
        $this->assertGreaterThanOrEqual(4, $title->credits()->count());
        $this->assertTrue(User::where('email', 'user@cinehub.test')->firstOrFail()
            ->reviews()
            ->whereKey(Review::firstOrFail()->id)
            ->exists());
    }
}
