<?php

namespace Database\Factories;

use App\Models\Title;
use App\Models\Watchlist;
use App\Models\WatchlistTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WatchlistTitle>
 */
class WatchlistTitleFactory extends Factory
{
    protected $model = WatchlistTitle::class;

    public function definition(): array
    {
        return [
            'watchlist_id' => Watchlist::factory(),
            'title_id' => Title::factory(),
            'added_at' => now(),
        ];
    }
}
