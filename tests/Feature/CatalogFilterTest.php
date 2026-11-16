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
}
