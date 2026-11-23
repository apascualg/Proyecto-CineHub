<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['tmdb_id', 'name', 'biography', 'birth_date', 'photo_path'];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'credits')
            ->withPivot(['role_type', 'character_name', 'billing_order'])
            ->withTimestamps();
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }
}
