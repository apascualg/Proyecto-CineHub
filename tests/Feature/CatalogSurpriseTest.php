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
}
