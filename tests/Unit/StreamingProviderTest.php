<?php

namespace Tests\Unit;

use App\Support\StreamingProvider;
use PHPUnit\Framework\TestCase;

class StreamingProviderTest extends TestCase
{
    public function test_it_merges_netflix_and_ad_variants(): void
    {
        $providers = StreamingProvider::normalize([
            ['name' => 'Netflix', 'logo_url' => null],
            ['name' => 'Netflix Standard with Ads', 'logo_url' => null],
        ], 'Una película');

        $this->assertCount(1, $providers);
        $this->assertSame('Netflix', $providers[0]['name']);
        $this->assertStringContainsString('netflix.com', $providers[0]['url']);
    }

    public function test_it_merges_amazon_prime_video_variants(): void
    {
        $providers = StreamingProvider::normalize([
            ['name' => 'Amazon Prime Video', 'logo_url' => null],
            ['name' => 'Amazon Prime Video with Ads', 'logo_url' => null],
        ], 'Una serie');

        $this->assertCount(1, $providers);
        $this->assertSame('Amazon Prime Video', $providers[0]['name']);
        $this->assertStringContainsString('primevideo.com', $providers[0]['url']);
    }

    public function test_it_uses_the_tmdb_link_as_fallback(): void
    {
        $providers = StreamingProvider::normalize([
            ['name' => 'Proveedor desconocido', 'logo_url' => null],
        ], 'Una película', 'https://www.themoviedb.org/movie/123/watch');

        $this->assertSame(
            'https://www.themoviedb.org/movie/123/watch',
            $providers[0]['url'],
        );
    }
}
