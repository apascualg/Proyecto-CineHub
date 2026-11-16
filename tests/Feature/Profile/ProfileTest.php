<?php

namespace Tests\Feature\Profile;

use App\Models\User;
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
}
