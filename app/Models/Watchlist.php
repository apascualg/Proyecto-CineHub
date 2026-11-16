<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'type', 'description', 'is_public',
    ];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'watchlist_title')
            ->withPivot('added_at');
    }
}
