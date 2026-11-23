<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id', 'source', 'type', 'title', 'original_title', 'slug', 'synopsis', 'release_date',
        'duration_minutes', 'seasons_count', 'episodes_count', 'poster_path',
        'backdrop_path', 'trailer_url', 'status',
    ];

    protected function casts(): array
    {
        return ['release_date' => 'date'];
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
