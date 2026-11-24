<?php

namespace App\Services;

use App\Models\Credit;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Title;
use App\Support\StreamingProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TmdbTitleImporter
{
    public function import(array $data, string $type): Title
    {
        return DB::transaction(function () use ($data, $type): Title {
            $tmdbId = (int) $data['id'];
            $titleValue = $type === 'series'
                ? ($data['name'] ?? 'Sin título')
                : ($data['title'] ?? 'Sin título');
            $originalTitle = $type === 'series'
                ? ($data['original_name'] ?? null)
                : ($data['original_title'] ?? null);
            $releaseDate = $type === 'series'
                ? ($data['first_air_date'] ?? null)
                : ($data['release_date'] ?? null);

            $existing = Title::where('tmdb_id', $tmdbId)->first();
            $slug = $existing?->slug ?? $this->uniqueSlug($titleValue);

            $attributes = [
                'source' => 'tmdb',
                'type' => $type,
                'title' => $titleValue,
                'original_title' => $originalTitle,
                'slug' => $slug,
                'synopsis' => $data['overview'] ?? null,
                'release_date' => $releaseDate ?: null,
                'duration_minutes' => $type === 'movie'
                    ? ($data['runtime'] ?: null)
                    : ($data['episode_run_time'][0] ?? null),
                'seasons_count' => $type === 'series'
                    ? ($data['number_of_seasons'] ?? null)
                    : null,
                'episodes_count' => $type === 'series'
                    ? ($data['number_of_episodes'] ?? null)
                    : null,
                'poster_path' => isset($data['poster_path'])
                    ? rtrim(config('tmdb.image_base_url'), '/').'/w500'.$data['poster_path']
                    : null,
                'backdrop_path' => isset($data['backdrop_path'])
                    ? rtrim(config('tmdb.image_base_url'), '/').'/original'.$data['backdrop_path']
                    : null,
                'trailer_url' => null,
                'streaming_providers' => $this->streamingProviders(
                    $data,
                    $titleValue,
                ),
                'status' => 'published',
            ];

            if (array_key_exists('vote_average', $data)) {
                $attributes['tmdb_rating'] = $this->tmdbRating($data['vote_average']);
            }

            $title = Title::updateOrCreate(
                ['tmdb_id' => $tmdbId],
                $attributes,
            );

            $genreIds = collect($data['genres'] ?? [])
                ->map(function (array $genre): int {
                    $tmdbId = (int) $genre['id'];
                    $name = $genre['name'];
                    $genreModel = Genre::where('tmdb_id', $tmdbId)->first()
                        ?? Genre::where('name', $name)->first();

                    if (! $genreModel) {
                        $genreModel = new Genre();
                    }

                    $genreModel->fill([
                        'tmdb_id' => $tmdbId,
                        'name' => $name,
                        'slug' => $genreModel->exists
                            ? $genreModel->slug
                            : $this->uniqueGenreSlug($name, $tmdbId),
                    ]);
                    $genreModel->save();

                    return $genreModel->id;
                })
                ->all();

            $title->genres()->sync($genreIds);

            $credits = $data['credits'] ?? [];
            foreach (array_slice($credits['cast'] ?? [], 0, 12) as $index => $cast) {
                $this->importCredit($title, $cast, 'actor', $cast['character'] ?? null, $index + 1);
            }

            foreach (array_slice($credits['crew'] ?? [], 0, 20) as $crew) {
                $role = $crew['job'] ?? '';
                $roleType = in_array($role, ['Director', 'Creator', 'Developed by'], true)
                    ? ($role === 'Director' ? 'director' : 'creator')
                    : null;

                if ($roleType !== null) {
                    $this->importCredit($title, $crew, $roleType, null, 1);
                }
            }

            return $title;
        });
    }

    private function importCredit(Title $title, array $personData, string $roleType, ?string $characterName, int $billingOrder): void
    {
        $tmdbId = (int) $personData['id'];
        $person = Person::where('tmdb_id', $tmdbId)->first()
            ?? Person::where('name', $personData['name'])->first();

        if (! $person) {
            $person = new Person();
        }

        $person->fill([
            'tmdb_id' => $tmdbId,
            'name' => $personData['name'],
            'photo_path' => isset($personData['profile_path'])
                ? rtrim(config('tmdb.image_base_url'), '/').'/w185'.$personData['profile_path']
                : $person->photo_path,
        ]);
        $person->save();

        Credit::updateOrCreate(
            [
                'title_id' => $title->id,
                'person_id' => $person->id,
                'role_type' => $roleType,
            ],
            [
                'character_name' => $characterName,
                'billing_order' => $billingOrder,
            ],
        );
    }

    private function tmdbRating(mixed $rating): ?float
    {
        if (! is_numeric($rating)) {
            return null;
        }

        $rating = (float) $rating;

        return $rating > 0 && $rating <= 10
            ? round($rating, 2)
            : null;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'titulo';
        $slug = $base;
        $counter = 2;

        while (Title::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function uniqueGenreSlug(string $name, int $tmdbId): string
    {
        $existing = Genre::where('tmdb_id', $tmdbId)->first();
        if ($existing) {
            return $existing->slug;
        }

        $base = Str::slug($name) ?: 'genero';
        $slug = $base;
        $counter = 2;

        while (Genre::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function streamingProviders(array $data, string $title): array
    {
        $regions = $data['watch/providers']['results'] ?? [];
        $region = config('tmdb.region', 'ES');
        $providerData = $regions[$region] ?? ($regions['US'] ?? null);
        $providers = $providerData['flatrate'] ?? [];
        $tmdbLink = $providerData['link'] ?? null;

        $normalized = collect($providers)
            ->map(fn (array $provider): array => [
                'name' => $provider['provider_name'],
                'logo_url' => isset($provider['logo_path'])
                    ? rtrim(config('tmdb.image_base_url'), '/').'/w92'.$provider['logo_path']
                    : null,
            ])
            ->all();

        return StreamingProvider::normalize($normalized, $title, $tmdbLink);
    }
}
