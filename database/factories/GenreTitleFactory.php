<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\GenreTitle;
use App\Models\Title;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GenreTitle>
 */
class GenreTitleFactory extends Factory
{
    protected $model = GenreTitle::class;

    public function definition(): array
    {
        return [
            'title_id' => Title::factory(),
            'genre_id' => Genre::factory(),
        ];
    }
}
