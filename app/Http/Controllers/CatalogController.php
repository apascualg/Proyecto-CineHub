<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function surprise(Request $request): RedirectResponse
    {
        $filters = $this->validatedFilters($request);
        $title = $this->catalogQuery($filters)
            ->inRandomOrder()
            ->firstOrFail();

        return redirect()->route('titles.show', $title);
    }

    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $sort = $request->validate([
            'sort' => ['nullable', 'in:release_desc,release_asc,title,rating_desc,rating_asc'],
        ])['sort'] ?? 'release_desc';

        $query = $this->catalogQuery($filters);

        $query = match ($sort) {
            'release_asc' => $query->orderBy('release_date'),
            'title' => $query->orderBy('title'),
            'rating_desc' => $query->orderByRaw('ratings_avg_score IS NULL')->orderByDesc('ratings_avg_score'),
            'rating_asc' => $query->orderByRaw('ratings_avg_score IS NULL')->orderBy('ratings_avg_score'),
            default => $query->latest('release_date'),
        };

        $titles = $query->paginate(100)->withQueryString();
        $genres = Genre::query()->orderBy('name')->get();

        return view('dashboard', compact('titles', 'genres', 'filters', 'sort'));
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:movie,series'],
            'genre' => ['nullable', 'integer', 'exists:genres,id'],
            'year_from' => ['nullable', 'integer', 'min:1888', 'max:2100'],
            'year_to' => ['nullable', 'integer', 'min:1888', 'max:2100', 'gte:year_from'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'director' => ['nullable', 'string', 'max:255'],
            'actor' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function catalogQuery(array $filters)
    {
        $query = Title::query()
            ->where('status', 'published')
            ->with(['genres', 'credits.person'])
            ->withAvg('ratings', 'score');

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
