<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TmdbService
{
    private PendingRequest $http;
    private ?string $apiKey = null;

    public function __construct()
    {
        $token = config('tmdb.token');
        $apiKey = config('tmdb.api_key');

        if (is_string($token) && trim($token) !== '') {
            $this->http = Http::baseUrl(config('tmdb.base_url'))
                ->withToken($token)
                ->acceptJson()
                ->timeout(15)
                ->withOptions(['verify' => $this->caBundle()]);

            return;
        }

        if (is_string($apiKey) && trim($apiKey) !== '') {
            $this->apiKey = $apiKey;
            $this->http = Http::baseUrl(config('tmdb.base_url'))
                ->acceptJson()
                ->timeout(15)
                ->withOptions(['verify' => $this->caBundle()]);

            return;
        }

        throw new RuntimeException('Configura TMDB_API_KEY o TMDB_API_TOKEN en el archivo .env.');
    }

    public function search(string $query, string $type = 'movie'): array
    {
        $endpoint = $type === 'series' ? '/search/tv' : '/search/movie';

        $response = $this->http->get($endpoint, $this->authenticatedParams([
            'query' => $query,
            'language' => 'es-ES',
            'include_adult' => false,
        ]));

        $response->throw();

        return $response->json('results', []);
    }

    public function movie(int $id): array
    {
        $response = $this->http->get("/movie/{$id}", $this->authenticatedParams([
            'language' => 'es-ES',
            'append_to_response' => 'credits',
        ]));

        $response->throw();

        return $response->json();
    }

    public function series(int $id): array
    {
        $response = $this->http->get("/tv/{$id}", $this->authenticatedParams([
            'language' => 'es-ES',
            'append_to_response' => 'credits',
        ]));

        $response->throw();

        return $response->json();
    }

    private function authenticatedParams(array $params): array
    {
        if ($this->apiKey !== null) {
            $params['api_key'] = $this->apiKey;
        }

        return $params;
    }

    private function caBundle(): string|bool
    {
        $caBundle = config('tmdb.ca_bundle');

        return is_string($caBundle) && is_file($caBundle)
            ? $caBundle
            : true;
    }
}
