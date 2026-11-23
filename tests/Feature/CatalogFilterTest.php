<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->actingAs(User::where('email', 'user@cinehub.test')->firstOrFail());
    }

    public function test_it_filters_by_type(): void
    {
        $response = $this->get(route('dashboard', ['type' => 'movie']));

        $response->assertOk()->assertViewHas('titles', function ($titles): bool {
            return $titles->isNotEmpty()
                && $titles->every(fn (Title $title): bool => $title->type === 'movie');
        });
    }

    public function test_it_filters_by_genre_and_year(): void
    {
        $genre = Genre::where('slug', 'drama')->firstOrFail();

        $response = $this->get(route('dashboard', [
            'genre' => $genre->id,
            'year_from' => 2022,
            'year_to' => 2024,
        ]));

        $response->assertOk()->assertViewHas('titles', function ($titles) use ($genre): bool {
            return $titles->isNotEmpty()
                && $titles->every(function (Title $title) use ($genre): bool {
                    return $title->genres->contains($genre)
                        && $title->release_date?->year >= 2022
                        && $title->release_date?->year <= 2024;
                });
        });
    }

    public function test_it_filters_by_minimum_rating(): void
    {
        $response = $this->get(route('dashboard', ['rating' => 9]));

        $response->assertOk()->assertViewHas('titles', function ($titles): bool {
            return $titles->isNotEmpty()
                && $titles->every(fn (Title $title): bool => (float) $title->ratings_avg_score >= 9);
        });
    }

    public function test_it_filters_by_director_and_actor(): void
    {
        $title = Title::with('credits.person')->firstOrFail();
        $director = $title->credits->firstWhere('role_type', 'director');
        $actor = $title->credits->firstWhere('role_type', 'actor');

        $this->assertNotNull($director);
        $this->assertNotNull($actor);

        $response = $this->get(route('dashboard', [
            'director' => $director->person->name,
            'actor' => $actor->person->name,
        ]));

        $response->assertOk()->assertViewHas('titles', function ($titles) use ($director, $actor): bool {
            return $titles->isNotEmpty()
                && $titles->every(function (Title $title) use ($director, $actor): bool {
                    return $title->credits->contains(
                        fn ($credit): bool => $credit->role_type === 'director'
                            && $credit->person->name === $director->person->name,
                    )
                    && $title->credits->contains(
                        fn ($credit): bool => $credit->role_type === 'actor'
                            && $credit->person->name === $actor->person->name,
                    );
                });
        });
    }

    public function test_it_searches_by_title_and_original_title(): void
    {
        $title = Title::factory()->published()->create([
            'title' => 'Busqueda CineHub',
            'original_title' => 'CineHub Search',
        ]);
        Title::factory()->published()->create([
            'title' => 'Otra pelicula',
            'original_title' => 'Different title',
        ]);

        $this->get(route('dashboard', ['title' => 'Busqueda CineHub']))
            ->assertOk()
            ->assertViewHas('titles', fn ($titles): bool => $titles->contains($title));

        $this->get(route('dashboard', ['title' => 'CineHub Search']))
            ->assertOk()
            ->assertViewHas('titles', fn ($titles): bool => $titles->contains($title));
    }

    public function test_it_excludes_draft_titles_from_the_public_catalog(): void
    {
        $draft = Title::factory()->create([
            'title' => 'Borrador no visible',
            'status' => 'draft',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('titles', fn ($titles): bool => !$titles->contains($draft));
    }

    public function test_it_paginates_catalog_results_and_preserves_filters(): void
    {
        Title::factory()->published()->count(13)->create([
            'type' => 'movie',
            'title' => fake()->unique()->sentence(3).' Pagination',
            'release_date' => '2024-01-01',
        ]);

        $response = $this->get(route('dashboard', [
            'type' => 'movie',
            'title' => 'Pagination',
            'page' => 2,
        ]));

        $response->assertOk()
            ->assertViewHas('titles', function ($titles): bool {
                return $titles->count() === 1
                    && $titles->every(fn (Title $title): bool => $title->type === 'movie');
            });
        $response->assertSee('type=movie');
    }
}
