<?php

namespace Tests\Feature\User;

use App\Models\Comment;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TitleInteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_and_update_a_rating_without_duplicates(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('titles.ratings.store', $title), ['score' => 7])
            ->assertRedirect(route('titles.show', $title));

        $this->actingAs($user)
            ->post(route('titles.ratings.store', $title), ['score' => 9])
            ->assertRedirect(route('titles.show', $title));

        $this->assertDatabaseCount('ratings', 1);
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'title_id' => $title->id,
            'score' => 9,
        ]);
    }

    public function test_a_rating_must_be_between_one_and_ten(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($user)
            ->from(route('titles.show', $title))
            ->post(route('titles.ratings.store', $title), ['score' => 11])
            ->assertRedirect(route('titles.show', $title))
            ->assertSessionHasErrors('score');

        $this->assertDatabaseCount('ratings', 0);
    }

    public function test_a_user_can_create_update_and_delete_their_review(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('titles.reviews.store', $title), [
                'headline' => 'Primera opinión',
                'body' => 'Una reseña suficientemente extensa para superar la validación.',
            ])
            ->assertRedirect(route('titles.show', $title));

        $review = Review::query()->where('user_id', $user->id)->firstOrFail();
        $this->assertSame('pending', $review->status);

        $this->actingAs($user)
            ->put(route('reviews.update', $review), [
                'headline' => 'Opinión actualizada',
                'body' => 'El texto actualizado también supera la longitud mínima requerida.',
            ])
            ->assertRedirect(route('titles.show', $title));

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'headline' => 'Opinión actualizada',
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->delete(route('reviews.destroy', $review))
            ->assertRedirect(route('titles.show', $title));

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_a_user_cannot_update_or_delete_another_users_review(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->put(route('reviews.update', $review), [
                'headline' => 'Intento no autorizado',
                'body' => 'Este cambio no debería permitirse nunca.',
            ])
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('reviews.destroy', $review))
            ->assertForbidden();

        $this->assertDatabaseHas('reviews', ['id' => $review->id]);
    }

    public function test_pending_reviews_are_only_visible_to_their_author(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $title = Title::factory()->published()->create();
        $review = Review::factory()->create([
            'user_id' => $author->id,
            'title_id' => $title->id,
            'body' => 'Contenido pendiente de moderación.',
            'status' => 'pending',
        ]);

        $this->actingAs($author)
            ->get(route('titles.show', $title))
            ->assertOk()
            ->assertSee($review->body);

        $this->actingAs($otherUser)
            ->get(route('titles.show', $title))
            ->assertOk()
            ->assertDontSee($review->body);
    }

    public function test_approved_reviews_are_visible_in_the_title_page(): void
    {
        $user = User::factory()->create();
        $title = Title::factory()->published()->create();
        $review = Review::factory()->approved()->create([
            'title_id' => $title->id,
            'body' => 'Esta reseña ya ha sido aprobada.',
        ]);

        $this->actingAs($user)
            ->get(route('titles.show', $title))
            ->assertOk()
            ->assertSee($review->body);
    }

    public function test_a_user_can_comment_on_a_visible_review_and_delete_their_comment(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->approved()->create();

        $this->actingAs($user)
            ->post(route('reviews.comments.store', $review), [
                'body' => 'Estoy de acuerdo con esta reseña.',
            ])
            ->assertRedirect(route('titles.show', $review->title));

        $comment = Comment::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user)
            ->delete(route('comments.destroy', $comment))
            ->assertRedirect(route('titles.show', $review->title));

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_a_user_cannot_comment_on_another_users_pending_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['status' => 'pending']);

        $this->actingAs($user)
            ->post(route('reviews.comments.store', $review), [
                'body' => 'Este comentario no debería publicarse.',
            ])
            ->assertNotFound();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_a_user_cannot_delete_another_users_comment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->delete(route('comments.destroy', $comment))
            ->assertForbidden();

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_a_user_can_update_their_own_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'body' => 'Comentario antiguo',
        ]);

        $this->actingAs($user)
            ->put(route('comments.update', $comment), [
                'body' => 'Comentario actualizado',
            ])
            ->assertRedirect(route('titles.show', $comment->review->title));

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Comentario actualizado',
        ]);
    }
}
