<?php

namespace Tests\Feature\Profile;

use App\Models\Rating;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_update_their_profile(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
        ]);

        $this->actingAs($user)
            ->put('/profile', [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_a_profile_email_must_be_unique(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'email' => 'taken@example.com',
        ]);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put('/profile', [
                'name' => $user->name,
                'email' => $otherUser->email,
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('email');
    }

    public function test_profile_shows_the_users_ratings_reviews_and_lists(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create(['title' => 'Título del perfil']);

        Rating::factory()->create([
            'user_id' => $user->id,
            'title_id' => $title->id,
            'score' => 8,
        ]);
        Review::factory()->approved()->create([
            'user_id' => $user->id,
            'title_id' => $title->id,
            'body' => 'Una reseña visible desde el perfil.',
        ]);
        $watchlist = Watchlist::factory()->create([
            'user_id' => $user->id,
            'type' => 'favorites',
            'name' => 'Favoritos',
        ]);
        $watchlist->titles()->attach($title);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Título del perfil')
            ->assertSee('Una reseña visible desde el perfil.')
            ->assertSee('Favoritos');
    }
}
