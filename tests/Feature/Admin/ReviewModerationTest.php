<?php

namespace Tests\Feature\Admin;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_view_and_moderate_reviews(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $review = Review::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee($review->body);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.status', $review), ['status' => 'approved'])
            ->assertRedirect(route('admin.reviews.index'));

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'approved',
        ]);
    }

    public function test_a_regular_user_cannot_access_review_moderation(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.reviews.index'))
            ->assertForbidden();
    }

    public function test_admin_can_hide_and_delete_a_review(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $review = Review::factory()->approved()->create();

        $this->actingAs($admin)
            ->patch(route('admin.reviews.status', $review), ['status' => 'hidden'])
            ->assertRedirect(route('admin.reviews.index'));

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'hidden',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.reviews.destroy', $review))
            ->assertRedirect();

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
