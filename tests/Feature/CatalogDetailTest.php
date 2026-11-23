<?php

namespace Tests\Feature;

use App\Models\Credit;
use App\Models\Genre;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_view_a_published_title_with_its_catalog_data(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create([
            'title' => 'Ficha CineHub',
            'original_title' => 'CineHub Detail',
            'synopsis' => 'Sinopsis de la ficha de prueba.',
        ]);
        $genre = Genre::factory()->create(['name' => 'Genero ficha']);
        $actor = Credit::factory()->actor()->create([
            'title_id' => $title->id,
        ]);
        $title->genres()->attach($genre);
        Rating::factory()->create([
            'user_id' => $user->id,
            'title_id' => $title->id,
            'score' => 8,
        ]);
        $review = Review::factory()->approved()->create([
            'title_id' => $title->id,
            'body' => 'Resena aprobada visible en la ficha.',
        ]);

        $this->actingAs($user)
            ->get(route('titles.show', $title))
            ->assertOk()
            ->assertSee($title->title)
            ->assertSee($title->original_title)
            ->assertSee($title->synopsis)
            ->assertSee($genre->name)
            ->assertSee($actor->person->name)
            ->assertSee('8.0')
            ->assertSee($review->body);
    }

    public function test_a_draft_title_is_not_available_in_the_public_detail(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->create(['status' => 'draft']);

        $this->actingAs($user)
            ->get(route('titles.show', $title))
            ->assertNotFound();
    }
}
