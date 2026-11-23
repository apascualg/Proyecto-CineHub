<?php

namespace Tests\Feature\Admin;

use App\Models\Credit;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_and_delete_a_title(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $title = Title::factory()->create(['title' => 'Título antiguo']);

        $this->actingAs($admin)
            ->put(route('admin.titles.update', $title), [
                'type' => 'series',
                'title' => 'Título actualizado',
                'original_title' => 'Updated title',
                'slug' => 'titulo-actualizado',
                'status' => 'published',
                'streaming_providers' => 'Netflix, Disney+',
            ])
            ->assertRedirect(route('admin.titles.index'));

        $this->assertDatabaseHas('titles', [
            'id' => $title->id,
            'title' => 'Título actualizado',
            'type' => 'series',
            'status' => 'published',
        ]);
        $this->assertSame(
            ['Netflix', 'Disney+'],
            collect($title->fresh()->streaming_providers)->pluck('name')->all(),
        );

        $this->actingAs($admin)
            ->delete(route('admin.titles.destroy', $title))
            ->assertRedirect(route('admin.titles.index'));

        $this->assertDatabaseMissing('titles', ['id' => $title->id]);
    }

    public function test_title_creation_validates_required_and_allowed_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.titles.create'))
            ->post(route('admin.titles.store'), [
                'type' => 'documentary',
                'title' => '',
                'slug' => 'invalid slug',
                'status' => 'unknown',
            ])
            ->assertRedirect(route('admin.titles.create'))
            ->assertSessionHasErrors(['type', 'title', 'slug', 'status']);
    }

    public function test_admin_can_create_update_and_delete_a_genre(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.genres.store'), [
                'name' => 'Genero nuevo',
                'slug' => 'genero-nuevo',
            ])
            ->assertRedirect(route('admin.genres.index'));

        $genre = Genre::where('slug', 'genero-nuevo')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.genres.update', $genre), [
                'name' => 'Genero editado',
                'slug' => 'genero-editado',
            ])
            ->assertRedirect(route('admin.genres.index'));

        $this->assertDatabaseHas('genres', ['id' => $genre->id, 'slug' => 'genero-editado']);

        $this->actingAs($admin)
            ->delete(route('admin.genres.destroy', $genre))
            ->assertRedirect(route('admin.genres.index'));

        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
    }

    public function test_admin_can_create_update_and_delete_a_person(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.people.store'), ['name' => 'Persona nueva'])
            ->assertRedirect(route('admin.people.index'));

        $person = Person::where('name', 'Persona nueva')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.people.update', $person), ['name' => 'Persona editada'])
            ->assertRedirect(route('admin.people.index'));

        $this->assertDatabaseHas('people', ['id' => $person->id, 'name' => 'Persona editada']);

        $this->actingAs($admin)
            ->delete(route('admin.people.destroy', $person))
            ->assertRedirect(route('admin.people.index'));

        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }

    public function test_admin_can_create_update_and_delete_a_credit(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $title = Title::factory()->create();
        $person = Person::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.credits.store'), [
                'title_id' => $title->id,
                'person_id' => $person->id,
                'role_type' => 'actor',
                'character_name' => 'Personaje inicial',
                'billing_order' => 1,
            ])
            ->assertRedirect(route('admin.credits.index'));

        $credit = Credit::where('title_id', $title->id)->where('person_id', $person->id)->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.credits.update', $credit), [
                'title_id' => $title->id,
                'person_id' => $person->id,
                'role_type' => 'director',
                'billing_order' => 1,
            ])
            ->assertRedirect(route('admin.credits.index'));

        $this->assertDatabaseHas('credits', ['id' => $credit->id, 'role_type' => 'director']);

        $this->actingAs($admin)
            ->delete(route('admin.credits.destroy', $credit))
            ->assertRedirect(route('admin.credits.index'));

        $this->assertDatabaseMissing('credits', ['id' => $credit->id]);
    }

    public function test_regular_users_cannot_access_catalog_administration(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.titles.index'))
            ->assertForbidden();
    }

    public function test_admin_can_update_another_users_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_resource_validation_rejects_invalid_genres_and_credits(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.genres.create'))
            ->post(route('admin.genres.store'), [
                'name' => '',
                'slug' => 'slug invalido',
            ])
            ->assertRedirect(route('admin.genres.create'))
            ->assertSessionHasErrors(['name', 'slug']);

        $this->actingAs($admin)
            ->from(route('admin.credits.create'))
            ->post(route('admin.credits.store'), [
                'title_id' => 999999,
                'person_id' => 999999,
                'role_type' => 'writer',
                'billing_order' => 0,
            ])
            ->assertRedirect(route('admin.credits.create'))
            ->assertSessionHasErrors(['title_id', 'person_id', 'role_type', 'billing_order']);
    }
}
