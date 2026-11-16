<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenreTitle extends Model
{
    public $timestamps = false;

    protected $table = 'genre_title';

    protected $fillable = ['title_id', 'genre_id'];

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}
