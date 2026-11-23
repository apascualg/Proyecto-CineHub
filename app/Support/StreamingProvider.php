<?php

namespace App\Support;

use Illuminate\Support\Str;

class StreamingProvider
{
    public static function normalize(array $providers, ?string $title = null, ?string $tmdbLink = null): array
    {
        $normalized = [];

        foreach ($providers as $provider) {
            $name = is_array($provider)
                ? trim((string) ($provider['name'] ?? ''))
                : trim((string) $provider);

            if ($name === '') {
                continue;
            }

            $canonical = self::canonicalName($name);
            $key = mb_strtolower($canonical);

            if (isset($normalized[$key])) {
                continue;
            }

            $providedUrl = is_array($provider) ? ($provider['url'] ?? null) : null;
            $normalized[$key] = [
                'name' => $canonical,
                'logo_url' => is_array($provider) ? ($provider['logo_url'] ?? null) : null,
                'url' => $providedUrl ?: self::officialUrl($canonical, $title) ?: $tmdbLink,
            ];
        }

        return array_values($normalized);
    }

    public static function canonicalName(string $name): string
    {
        $compact = mb_strtolower(trim($name));
        $compact = preg_replace('/\s+(with ads|ads|con anuncios)$/i', '', $compact);

        return match (true) {
            str_contains($compact, 'netflix') => 'Netflix',
            str_contains($compact, 'amazon prime video') || str_contains($compact, 'prime video') => 'Amazon Prime Video',
            str_contains($compact, 'amazon video') => 'Amazon Prime Video',
            str_contains($compact, 'disney') => 'Disney+',
            str_contains($compact, 'hbo max') || str_contains($compact, 'max') => 'HBO Max',
            str_contains($compact, 'apple tv') => 'Apple TV',
            str_contains($compact, 'paramount') => 'Paramount+',
            str_contains($compact, 'movistar') => 'Movistar Plus+',
            str_contains($compact, 'filmin') => 'Filmin',
            str_contains($compact, 'skyshowtime') => 'SkyShowtime',
            str_contains($compact, 'atresplayer') => 'Atresplayer',
            str_contains($compact, 'rtve') => 'RTVE Play',
            str_contains($compact, 'pluto') => 'Pluto TV',
            str_contains($compact, 'rakuten') => 'Rakuten TV',
            str_contains($compact, 'mubi') => 'MUBI',
            str_contains($compact, 'youtube') => 'YouTube',
            default => trim($name),
        };
    }

    public static function officialUrl(string $name, ?string $title): ?string
    {
        $titleQuery = $title ? rawurlencode($title) : null;

        return match ($name) {
            'Netflix' => $titleQuery
                ? 'https://www.netflix.com/es/search?q='.$titleQuery
                : 'https://www.netflix.com/es/',
            'Amazon Prime Video' => $titleQuery
                ? 'https://www.primevideo.com/search/ref=atv_nb_sr?phrase='.$titleQuery
                : 'https://www.primevideo.com/',
            'Disney+' => $titleQuery
                ? 'https://www.disneyplus.com/es-es/search?q='.$titleQuery
                : 'https://www.disneyplus.com/es-es/',
            'HBO Max' => $titleQuery
                ? 'https://play.max.com/search?q='.$titleQuery
                : 'https://play.max.com/',
            'Apple TV' => $titleQuery
                ? 'https://tv.apple.com/es/search?term='.$titleQuery
                : 'https://tv.apple.com/es/',
            'Paramount+' => $titleQuery
                ? 'https://www.paramountplus.com/es/search/?query='.$titleQuery
                : 'https://www.paramountplus.com/es/',
            'Movistar Plus+' => $titleQuery
                ? 'https://www.movistarplus.es/buscador?buscar='.$titleQuery
                : 'https://www.movistarplus.es/',
            'Filmin' => $titleQuery
                ? 'https://www.filmin.es/buscar?q='.$titleQuery
                : 'https://www.filmin.es/',
            'SkyShowtime' => 'https://www.skyshowtime.com/es/',
            'Atresplayer' => $titleQuery
                ? 'https://www.atresplayer.com/buscar/?q='.$titleQuery
                : 'https://www.atresplayer.com/',
            'RTVE Play' => 'https://www.rtve.es/play/',
            'Pluto TV' => 'https://pluto.tv/es/search',
            'Rakuten TV' => $titleQuery
                ? 'https://rakuten.tv/es/search?q='.$titleQuery
                : 'https://rakuten.tv/es',
            'MUBI' => $titleQuery
                ? 'https://mubi.com/es/search?q='.$titleQuery
                : 'https://mubi.com/es',
            'YouTube' => $titleQuery
                ? 'https://www.youtube.com/results?search_query='.$titleQuery
                : 'https://www.youtube.com/',
            default => null,
        };
    }
}
