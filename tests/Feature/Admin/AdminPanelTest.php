<?php

namespace Tests\Feature\Admin;

use App\Models\Genre;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_title_with_genres(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $genre = Genre::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.titles.store'), [
            'type' => 'movie',
            'title' => 'Título de prueba',
            'original_title' => 'Test title',
            'slug' => 'titulo-de-prueba',
            'status' => 'draft',
            'genres' => [$genre->id],
        ]);

        $response->assertRedirect(route('admin.titles.index'));
        $this->assertDatabaseHas('titles', ['slug' => 'titulo-de-prueba', 'status' => 'draft']);
        $this->assertDatabaseHas('genre_title', ['genre_id' => $genre->id]);
    }

    public function test_admin_can_toggle_a_title_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $title = Title::factory()->published()->create();

        $this->actingAs($admin)
            ->patch(route('admin.titles.toggle-status', $title))
            ->assertRedirect();

        $this->assertDatabaseHas('titles', ['id' => $title->id, 'status' => 'draft']);
    }

    public function test_admin_can_moderate_a_review(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $review = Review::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->put(route('admin.reviews.update', $review), ['status' => 'approved'])
            ->assertRedirect();

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'status' => 'approved']);
    }

    public function test_admin_cannot_remove_their_own_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'user',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }
}
