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
}
