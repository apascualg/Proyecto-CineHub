<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id', 'source', 'tmdb_rating', 'type', 'title', 'original_title', 'slug', 'synopsis', 'release_date',
        'duration_minutes', 'seasons_count', 'episodes_count', 'poster_path',
        'backdrop_path', 'trailer_url', 'streaming_providers', 'status',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'tmdb_rating' => 'decimal:2',
            'streaming_providers' => 'array',
        ];
    }

    public function scopeWithRatingsSummary(Builder $query): Builder
    {
        return $query
            ->addSelect('titles.*')
            ->selectSub(
                DB::table('ratings')
                    ->selectRaw('
                        (1.0 * (COALESCE(SUM(score), 0) + COALESCE(titles.tmdb_rating, 0)))
                        / NULLIF(COUNT(*) + CASE WHEN titles.tmdb_rating IS NOT NULL THEN 1 ELSE 0 END, 0)
                    ')
                    ->whereColumn('ratings.title_id', 'titles.id'),
                'ratings_avg_score',
            )
            ->selectSub(
                DB::table('ratings')
                    ->selectRaw('COUNT(*) + CASE WHEN titles.tmdb_rating IS NOT NULL THEN 1 ELSE 0 END')
                    ->whereColumn('ratings.title_id', 'titles.id'),
                'ratings_count',
            );
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_title');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'credits')
            ->withPivot(['role_type', 'character_name', 'billing_order'])
            ->withTimestamps();
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function watchlists(): BelongsToMany
    {
        return $this->belongsToMany(Watchlist::class, 'watchlist_title')
            ->withPivot('added_at');
    }
}
