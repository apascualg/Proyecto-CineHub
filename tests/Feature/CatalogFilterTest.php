<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Title;
use App\Models\User;
use App\Models\Person;
use App\Models\Credit;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->actingAs(User::factory()->create());
    }

    public function test_it_filters_by_type(): void
    {
        Title::factory()->published()->create(['type' => 'movie']);
        Title::factory()->published()->create(['type' => 'series']);

        $response = $this->get(route('dashboard', ['type' => 'movie']));

        $response->assertOk()->assertViewHas('titles', function ($titles): bool {
            return $titles->isNotEmpty()
                && $titles->every(fn (Title $title): bool => $title->type === 'movie');
        });
    }

    public function test_it_filters_by_genre_and_year(): void
    {
        $genre = Genre::factory()->create(['slug' => 'drama']);
        $title = Title::factory()->published()->create([
            'release_date' => '2023-06-01',
        ]);
        $title->genres()->attach($genre);

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
        $rater = User::factory()->create();
        $title = Title::factory()->published()->create();
        Rating::factory()->create([
            'title_id' => $title->id,
            'user_id' => $rater->id,
            'score' => 9,
        ]);

        $response = $this->get(route('dashboard', ['rating' => 9]));

        $response->assertOk()->assertViewHas('titles', function ($titles): bool {
            return $titles->isNotEmpty()
                && $titles->every(fn (Title $title): bool => (float) $title->ratings_avg_score >= 9);
        });
    }

    public function test_it_filters_by_director_and_actor(): void
    {
        $title = Title::factory()->published()->create();
        $directorPerson = Person::factory()->create(['name' => 'Director Factoria']);
        $actorPerson = Person::factory()->create(['name' => 'Actor Factoria']);
        Credit::factory()->create([
            'title_id' => $title->id,
            'person_id' => $directorPerson->id,
            'role_type' => 'director',
        ]);
        Credit::factory()->create([
            'title_id' => $title->id,
            'person_id' => $actorPerson->id,
            'role_type' => 'actor',
        ]);

        $response = $this->get(route('dashboard', [
            'director' => $directorPerson->name,
            'actor' => $actorPerson->name,
        ]));

        $response->assertOk()
            ->assertViewHas('titles', fn ($titles): bool => $titles->contains($title));
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
        Title::factory()->published()->count(204)->create([
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
                return $titles->count() === 102
                    && $titles->every(fn (Title $title): bool => $title->type === 'movie');
            })
            ->assertSee('Mostrando del resultado')
            ->assertSee('<strong>103</strong> al', false)
            ->assertSee('<strong>204</strong> sobre', false)
            ->assertSee('<strong>204</strong>', false)
            ->assertSee('resultados');
        $response->assertSee('type=movie');
    }

    public function test_it_shows_an_empty_results_message_when_no_titles_match(): void
    {
        $this->get(route('dashboard', ['title' => 'No existe este título']))
            ->assertOk()
            ->assertSee('No hay resultados que mostrar.');
    }

    public function test_it_can_sort_catalog_results_by_title(): void
    {
        Title::factory()->published()->create(['title' => 'Zulu']);
        Title::factory()->published()->create(['title' => 'Alpha']);

        $this->get(route('dashboard', ['sort' => 'title']))
            ->assertOk()
            ->assertViewHas('titles', fn ($titles): bool => $titles->first()->title === 'Alpha');
    }
}
