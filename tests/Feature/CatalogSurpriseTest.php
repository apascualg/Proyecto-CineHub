<?php

namespace Tests\Feature;

use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSurpriseTest extends TestCase
{
    use RefreshDatabase;

    public function test_surprise_redirects_to_a_published_title(): void
    {
        $user = User::factory()->create();
        Title::factory()->published()->create();
        Title::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($user)
            ->get(route('titles.surprise'))
            ->assertRedirect();

        $this->assertStringContainsString('/titles/', (string) $response->headers->get('Location'));
    }

    public function test_surprise_respects_catalog_filters(): void
    {
        $user = User::factory()->create();
        $matching = Title::factory()->published()->create([
            'type' => 'series',
            'title' => 'Serie sorpresa',
        ]);
        Title::factory()->published()->create([
            'type' => 'movie',
            'title' => 'Película descartada',
        ]);

        $response = $this->actingAs($user)
            ->get(route('titles.surprise', ['type' => 'series']))
            ->assertRedirect(route('titles.show', $matching));

        $this->assertStringContainsString('/titles/', (string) $response->headers->get('Location'));
    }
}
