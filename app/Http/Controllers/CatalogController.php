<?php

namespace App\Http\Controllers;

use App\Jobs\AutoloadTmdbCatalog;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function home(): View
    {
        $this->queueCatalogAutoload();

        $featured = Title::query()
            ->where('status', 'published')
            ->with(['genres'])
            ->withRatingsSummary()
            ->latest('release_date')
            ->limit(8)
            ->get();

        $topRated = Title::query()
            ->where('status', 'published')
            ->with(['genres'])
            ->withRatingsSummary()
            ->havingRaw('ratings_avg_score IS NOT NULL')
            ->orderByDesc('ratings_avg_score')
            ->limit(8)
            ->get();

        return view('welcome', compact('featured', 'topRated'));
    }

    public function publicShow(Title $title): View
    {
        $title = Title::query()
            ->withRatingsSummary()
            ->findOrFail($title->getKey());
        abort_unless($title->status === 'published', 404);
        $title->load([
            'genres',
            'credits' => fn ($query) => $query
                ->whereIn('role_type', ['actor', 'director', 'creator'])
                ->with('person')
                ->orderBy('billing_order'),
        ]);

        $related = Title::query()
            ->where('status', 'published')
            ->whereKeyNot($title->id)
            ->whereHas('genres', fn ($query) => $query->whereIn(
                'genres.id',
                $title->genres->pluck('id'),
            ))
            ->with(['genres'])
            ->withRatingsSummary()
            ->limit(6)
            ->get();

        $reviews = $title->reviews()
            ->with(['user', 'comments.user'])
            ->where('status', 'approved')
            ->latest()
            ->limit(20)
            ->get();

        return view('catalog.show-public', compact('title', 'related', 'reviews'));
    }

    public function suggestions(Request $request)
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        return Title::query()
            ->where('status', 'published')
            ->where('title', 'like', '%' . $validated['q'] . '%')
            ->orderBy('title')
            ->limit(8)
            ->get(['id', 'title', 'type']);
    }
    public function surprise(Request $request): RedirectResponse
    {
        $filters = $this->validatedFilters($request);
        $title = $this->catalogQuery($filters)
            ->inRandomOrder()
            ->firstOrFail();

        if (auth()->guest()) {
            return redirect()->route('catalog.show', $title);
        }

        return redirect()->route('titles.show', $title);
    }

    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $sort = $request->validate([
            'sort' => ['nullable', 'in:release_desc,release_asc,title,rating_desc,rating_asc'],
        ])['sort'] ?? 'release_desc';

        $this->queueCatalogAutoload();

        $query = $this->catalogQuery($filters);

        $query = match ($sort) {
            'release_asc' => $query->orderBy('release_date'),
            'title' => $query->orderBy('title'),
            'rating_desc' => $query->orderByRaw('ratings_avg_score IS NULL')->orderByDesc('ratings_avg_score'),
            'rating_asc' => $query->orderByRaw('ratings_avg_score IS NULL')->orderBy('ratings_avg_score'),
            default => $query->latest('release_date'),
        };

        $titles = $query->paginate(102)->withQueryString();
        $genres = Genre::query()->orderBy('name')->get();

        return view('dashboard', compact('titles', 'genres', 'filters', 'sort'));
    }

    private function queueCatalogAutoload(): void
    {
        $hasTmdbCredentials = filled(config('tmdb.token')) || filled(config('tmdb.api_key'));

        if (
            $hasTmdbCredentials
            && Title::query()->where('status', 'published')->count() < AutoloadTmdbCatalog::MAX_CATALOG_TITLES
        ) {
            AutoloadTmdbCatalog::dispatch();
        }
    }

    private function validatedFilters(Request $request): array
    {
        $rules = [
            'title' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:movie,series'],
            'genre' => ['nullable', 'integer', 'exists:genres,id'],
        ];

        if (auth()->check()) {
            $rules += [
                'year_from' => ['nullable', 'integer', 'min:1888', 'max:2100'],
                'year_to' => ['nullable', 'integer', 'min:1888', 'max:2100', 'gte:year_from'],
                'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
                'director' => ['nullable', 'string', 'max:255'],
                'actor' => ['nullable', 'string', 'max:255'],
            ];
        }

        return $request->validate($rules);
    }

    private function catalogQuery(array $filters)
    {
        $query = Title::query()
            ->where('status', 'published')
            ->with(['genres', 'credits.person'])
            ->withRatingsSummary();

        $query->when($filters['title'] ?? null, function ($query, string $title): void {
            $query->where(function ($query) use ($title): void {
                $query->where('title', 'like', "%{$title}%")
                    ->orWhere('original_title', 'like', "%{$title}%");
            });
        });

        $query->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type));
        $query->when($filters['genre'] ?? null, fn ($query, int $genre) => $query->whereHas('genres', fn ($query) => $query->whereKey($genre)));
        $query->when($filters['year_from'] ?? null, fn ($query, int $year) => $query->whereYear('release_date', '>=', $year));
        $query->when($filters['year_to'] ?? null, fn ($query, int $year) => $query->whereYear('release_date', '<=', $year));
        $query->when($filters['rating'] ?? null, fn ($query, float $rating) => $query->having('ratings_avg_score', '>=', $rating));
        $query->when($filters['director'] ?? null, function ($query, string $director): void {
            $query->whereHas('credits', fn ($query) => $query
                ->where('role_type', 'director')
                ->whereHas('person', fn ($query) => $query->where('name', 'like', "%{$director}%")));
        });
        $query->when($filters['actor'] ?? null, function ($query, string $actor): void {
            $query->whereHas('credits', fn ($query) => $query
                ->where('role_type', 'actor')
                ->whereHas('person', fn ($query) => $query->where('name', 'like', "%{$actor}%")));
        });

        return $query;
    }
}
