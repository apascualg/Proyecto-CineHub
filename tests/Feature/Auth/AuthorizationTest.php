<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_access_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Panel de administración');
    }

    public function test_a_regular_user_cannot_access_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_a_user_cannot_change_their_role_from_the_profile(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->put('/profile', [
            'name' => 'Updated User',
            'email' => $user->email,
            'role' => 'admin',
        ])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
            'role' => 'user',
        ]);
    }
}
