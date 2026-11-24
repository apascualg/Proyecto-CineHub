<?php

namespace Tests\Feature;

use App\Jobs\AutoloadTmdbCatalog;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CatalogNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        config(['tmdb.token' => 'test-token']);
    }

    public function test_explore_is_public_and_home_remains_the_landing_page(): void
    {
        $this->actingAs(User::factory()->create());
        Title::factory()->published()->count(10)->create();

        $this->get('/explore')->assertOk();
        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.route('home').'" class="cine-brand"', false)
            ->assertSee('href="'.route('catalog.explore').'" class="surprise-button', false)
            ->assertSee('Últimos títulos')
            ->assertSee('Mejor valorados')
            ->assertSee('Listas personales');
    }

    public function test_explore_is_available_to_guests_with_the_login_promo(): void
    {
        $this->get('/explore')
            ->assertOk()
            ->assertSee('Inicia sesión para desbloquear el resto de filtros y funcionalidades')
            ->assertDontSee('name="director"', false);
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

    public function test_opening_catalog_queues_automatic_catalog_import(): void
    {
        Title::factory()->published()->count(205)->create();

        $response = $this->get(route('catalog.explore', ['page' => 2]))->assertOk();

        Queue::assertPushed(AutoloadTmdbCatalog::class, 1);

        $this->assertSame(
            2,
            substr_count($response->getContent(), 'aria-current="page">2</span>'),
        );
    }

    public function test_pagination_shows_at_most_five_numbers_centered_on_later_pages(): void
    {
        Title::factory()->published()->count(612)->create();

        $response = $this->get(route('catalog.explore', ['page' => 4]))->assertOk();
        $content = $response->getContent();

        $this->assertSame(2, substr_count($content, 'aria-current="page">4</span>'));
        foreach ([2, 3, 5, 6] as $pageNumber) {
            $this->assertSame(2, substr_count($content, '>'.$pageNumber.'</a>'));
        }
    }

    public function test_catalog_with_fewer_than_five_pages_shows_three_page_numbers(): void
    {
        Title::factory()->published()->count(408)->create();

        $response = $this->get(route('catalog.explore'))->assertOk();
        $content = $response->getContent();

        $this->assertSame(2, substr_count($content, 'aria-current="page">1</span>'));
        foreach ([2, 3] as $pageNumber) {
            $this->assertSame(2, substr_count($content, '>'.$pageNumber.'</a>'));
        }
        $this->assertStringNotContainsString('>4</a>', $content);
    }

    public function test_first_catalog_page_shows_five_page_numbers_once_five_pages_exist(): void
    {
        Title::factory()->published()->count(510)->create();

        $response = $this->get(route('catalog.explore'))->assertOk();
        $content = $response->getContent();

        $this->assertSame(2, substr_count($content, 'aria-current="page">1</span>'));
        foreach ([2, 3, 4, 5] as $pageNumber) {
            $this->assertSame(2, substr_count($content, '>'.$pageNumber.'</a>'));
        }
        $this->assertStringNotContainsString('>6</a>', $content);
    }
}
