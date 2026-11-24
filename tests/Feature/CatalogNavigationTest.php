<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_explore_redirects_authenticated_users_to_the_dashboard_and_home_remains_the_landing_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/explore')->assertRedirect(route('dashboard'));
        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.route('home').'" class="cine-brand"', false)
            ->assertSee('href="'.route('dashboard').'" class="surprise-button"', false)
            ->assertSee('Últimos títulos')
            ->assertSee('Mejor valorados')
            ->assertSee('Listas personales');
    }

    public function test_explore_redirects_guests_to_login_through_the_dashboard(): void
    {
        $this->get('/explore')->assertRedirect(route('dashboard'));
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_navigation_calls_the_dashboard_explore(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('href="'.route('dashboard').'">Explorar</a>', false)
            ->assertSee('>Explorar</a>', false)
            ->assertDontSee('>Inicio</a>', false);
    }
}
