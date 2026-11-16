<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Database\Seeder;

class DemoInteractionsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@cinehub.test')->firstOrFail();
        $user = User::where('email', 'user@cinehub.test')->firstOrFail();
        $titles = Title::where('status', 'published')->orderBy('id')->get();

        foreach ($titles->take(4) as $index => $title) {
            Rating::updateOrCreate(
                ['user_id' => $user->id, 'title_id' => $title->id],
                ['score' => 7 + ($index % 4)],
            );
        }

        $review = Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'title_id' => $titles->firstOrFail()->id,
                'headline' => 'Una buena experiencia',
            ],
            [
                'body' => 'Una reseña de demostración para comprobar la ficha del contenido.',
                'status' => 'approved',
            ],
        );

        Comment::firstOrCreate(
            [
                'user_id' => $admin->id,
                'review_id' => $review->id,
                'body' => 'Comentario de demostración.',
            ],
        );

        $watchlist = Watchlist::updateOrCreate(
            ['user_id' => $user->id, 'type' => 'favorites'],
            [
                'name' => 'Favoritos',
                'description' => 'Lista de favoritos de demostración.',
                'is_public' => false,
            ],
        );

        $watchlist->titles()->syncWithoutDetaching(
            $titles->take(3)->pluck('id')->all(),
        );
    }
}
