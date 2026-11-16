<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchlistTitle extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'watchlist_title';

    protected $fillable = ['watchlist_id', 'title_id', 'added_at'];

    protected function casts(): array
    {
        return ['added_at' => 'datetime'];
    }

    public function watchlist(): BelongsTo
    {
        return $this->belongsTo(Watchlist::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }
}
