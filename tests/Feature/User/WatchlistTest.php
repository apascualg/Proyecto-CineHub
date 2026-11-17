<?php

namespace Tests\Feature\User;

use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_add_a_title_to_a_list_without_duplicates(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('lists.store', $title), ['type' => 'favorites'])
            ->assertRedirect(route('titles.show', $title));

        $this->actingAs($user)
            ->post(route('lists.store', $title), ['type' => 'favorites'])
            ->assertRedirect(route('titles.show', $title));

        $this->assertDatabaseCount('watchlists', 1);
        $this->assertDatabaseCount('watchlist_title', 1);
        $this->assertDatabaseHas('watchlists', [
            'user_id' => $user->id,
            'type' => 'favorites',
            'name' => 'Favoritos',
        ]);
    }

    public function test_a_user_can_view_and_remove_titles_from_their_lists(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('lists.store', $title), ['type' => 'watched']);

        $this->actingAs($user)
            ->get(route('lists.index'))
            ->assertOk()
            ->assertSee($title->title);

        $this->actingAs($user)
            ->delete(route('lists.destroy', $title), ['type' => 'watched'])
            ->assertRedirect(route('titles.show', $title));

        $this->assertDatabaseCount('watchlist_title', 0);
    }

    public function test_users_only_see_their_own_lists(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($owner)
            ->post(route('lists.store', $title), ['type' => 'favorites']);

        $this->actingAs($otherUser)
            ->get(route('lists.index'))
            ->assertOk()
            ->assertDontSee($title->title);
    }

    public function test_a_user_can_create_a_custom_list_and_add_a_title_to_it(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('lists.custom.store'), [
                'name' => 'Cine de verano',
                'description' => 'Películas para las vacaciones.',
            ])
            ->assertRedirect(route('lists.index'));

        $watchlist = $user->watchlists()->where('type', 'custom')->firstOrFail();

        $this->actingAs($user)
            ->post(route('lists.store', $title), ['watchlist_id' => $watchlist->id])
            ->assertRedirect(route('titles.show', $title));

        $this->assertDatabaseHas('watchlists', [
            'id' => $watchlist->id,
            'name' => 'Cine de verano',
            'type' => 'custom',
        ]);
        $this->assertDatabaseHas('watchlist_title', [
            'watchlist_id' => $watchlist->id,
            'title_id' => $title->id,
        ]);
    }

    public function test_a_user_cannot_manage_another_users_custom_list(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $watchlist = $owner->watchlists()->create([
            'name' => 'Privada',
            'type' => 'custom',
            'is_public' => false,
        ]);
        $title = Title::factory()->published()->create();

        $this->actingAs($otherUser)
            ->post(route('lists.store', $title), ['watchlist_id' => $watchlist->id])
            ->assertNotFound();

        $this->actingAs($otherUser)
            ->delete(route('lists.custom.destroy', $watchlist))
            ->assertForbidden();
    }
}
