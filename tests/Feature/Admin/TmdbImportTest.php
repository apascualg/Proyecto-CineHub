<?php

namespace Tests\Feature\Admin;

use App\Models\Credit;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TmdbImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'tmdb.token' => 'test-token',
            'tmdb.api_key' => null,
            'tmdb.base_url' => 'https://api.themoviedb.org/3',
            'tmdb.image_base_url' => 'https://image.tmdb.org/t/p',
        ]);
    }

    public function test_admin_can_search_and_import_a_movie_from_tmdb(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Http::fake([
            'api.themoviedb.org/3/search/movie*' => Http::response([
                'results' => [
                    [
                        'id' => 123456,
                        'title' => 'Película desde TMDB',
                        'original_title' => 'TMDB Movie',
                        'release_date' => '2025-01-10',
                    ],
                ],
            ], 200),
            'api.themoviedb.org/3/movie/123456*' => Http::response([
                'id' => 123456,
                'title' => 'Película desde TMDB',
                'original_title' => 'TMDB Movie',
                'overview' => 'Sinopsis importada desde TMDB.',
                'release_date' => '2025-01-10',
                'runtime' => 125,
                'poster_path' => '/poster.jpg',
                'backdrop_path' => '/backdrop.jpg',
                'genres' => [
                    ['id' => 18, 'name' => 'Drama'],
                ],
                'credits' => [
                    'cast' => [
                        [
                            'id' => 22,
                            'name' => 'Actor TMDB',
                            'character' => 'Protagonista',
                            'profile_path' => '/actor.jpg',
                        ],
                    ],
                    'crew' => [
                        [
                            'id' => 33,
                            'name' => 'Director TMDB',
                            'job' => 'Director',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.tmdb.search', [
                'query' => 'Película',
                'type' => 'movie',
            ]))
            ->assertOk()
            ->assertSee('Película desde TMDB');

        $this->actingAs($admin)
            ->post(route('admin.tmdb.import'), [
                'tmdb_id' => 123456,
                'type' => 'movie',
            ])
            ->assertRedirect();

        $title = Title::where('tmdb_id', 123456)->firstOrFail();
        $this->assertSame('movie', $title->type);
        $this->assertSame('tmdb', $title->source);
        $this->assertSame(125, $title->duration_minutes);
        $this->assertDatabaseHas('genres', ['tmdb_id' => 18, 'name' => 'Drama']);
        $this->assertDatabaseHas('people', ['tmdb_id' => 22, 'name' => 'Actor TMDB']);
        $this->assertDatabaseHas('people', ['tmdb_id' => 33, 'name' => 'Director TMDB']);
        $this->assertDatabaseHas('credits', [
            'title_id' => $title->id,
            'role_type' => 'actor',
        ]);
        $this->assertDatabaseHas('credits', [
            'title_id' => $title->id,
            'role_type' => 'director',
        ]);
    }

    public function test_reimporting_a_tmdb_title_updates_it_without_duplicates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $title = Title::factory()->create([
            'tmdb_id' => 555,
            'source' => 'tmdb',
            'title' => 'Título antiguo',
        ]);

        Http::fake([
            'api.themoviedb.org/3/movie/555*' => Http::response([
                'id' => 555,
                'title' => 'Título actualizado',
                'original_title' => 'Updated title',
                'overview' => 'Sinopsis nueva.',
                'release_date' => '2024-02-01',
                'runtime' => 98,
                'genres' => [],
                'credits' => [],
            ], 200),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.tmdb.import'), [
                'tmdb_id' => 555,
                'type' => 'movie',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('titles', 1);
        $this->assertDatabaseHas('titles', [
            'id' => $title->id,
            'tmdb_id' => 555,
            'title' => 'Título actualizado',
            'duration_minutes' => 98,
        ]);
    }

    public function test_a_regular_user_cannot_search_or_import_tmdb_data(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.tmdb.search'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('admin.tmdb.import'), [
                'tmdb_id' => 1,
                'type' => 'movie',
            ])
            ->assertForbidden();
    }

    public function test_missing_tmdb_token_returns_a_search_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        config([
            'tmdb.token' => null,
            'tmdb.api_key' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.tmdb.search', [
                'query' => 'Matrix',
                'type' => 'movie',
            ]))
            ->assertOk()
            ->assertSee('Configura TMDB_API_KEY o TMDB_API_TOKEN');
    }

    public function test_api_key_mode_is_supported(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        config([
            'tmdb.token' => null,
            'tmdb.api_key' => 'test-v3-key',
        ]);

        Http::fake([
            'api.themoviedb.org/3/search/movie*' => Http::response([
                'results' => [],
            ], 200),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.tmdb.search', [
                'query' => 'Matrix',
                'type' => 'movie',
            ]))
            ->assertOk();
    }

    public function test_api_key_is_added_to_every_tmdb_request(): void
    {
        config([
            'tmdb.token' => null,
            'tmdb.api_key' => 'test-v3-key',
        ]);

        Http::fake([
            '*' => Http::response(['results' => []], 200),
        ]);

        $results = app(\App\Services\TmdbService::class)->search('Matrix', 'movie');

        $this->assertSame([], $results);
        Http::assertSent(function ($request): bool {
            parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

            return ($query['api_key'] ?? null) === 'test-v3-key';
        });
    }

}
