<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TmdbService
{
    private PendingRequest $http;

    public function __construct()
    {
        $token = config('tmdb.token');
        if (! is_string($token) || trim($token) === '') {
            throw new RuntimeException('TMDB_API_TOKEN no está configurado.');
        }

        $this->http = Http::baseUrl(config('tmdb.base_url'))
            ->withToken($token)
            ->acceptJson()
            ->timeout(15);
    }

    public function search(string $query, string $type = 'movie'): array
    {
        $endpoint = $type === 'series' ? '/search/tv' : '/search/movie';

        $response = $this->http->get($endpoint, [
            'query' => $query,
            'language' => 'es-ES',
            'include_adult' => false,
        ]);

        $response->throw();

        return $response->json('results', []);
    }

    public function movie(int $id): array
    {
        $response = $this->http->get("/movie/{$id}", [
            'language' => 'es-ES',
            'append_to_response' => 'credits',
        ]);

        $response->throw();

        return $response->json();
    }

    public function series(int $id): array
    {
        $response = $this->http->get("/tv/{$id}", [
            'language' => 'es-ES',
            'append_to_response' => 'credits',
        ]);

        $response->throw();

        return $response->json();
    }
}
