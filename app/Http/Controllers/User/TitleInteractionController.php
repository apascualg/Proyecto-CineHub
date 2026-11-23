<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Review;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TitleInteractionController extends Controller
{
    public function show(Title $title): View
    {
        abort_unless($title->status === 'published', 404);

        $title->load([
            'genres',
            'credits' => fn ($query) => $query
                ->where('role_type', 'actor')
                ->with('person')
                ->orderBy('billing_order'),
        ])
            ->loadAvg('ratings', 'score')
            ->loadCount('ratings');

        $reviews = $title->reviews()
            ->with(['user', 'comments.user'])
            ->where(function ($query): void {
                $query->where('status', 'approved')
                    ->orWhere('user_id', Auth::id());
            })
            ->latest()
            ->get();

        $userRating = $title->ratings()
            ->where('user_id', Auth::id())
            ->value('score');

        $userReview = $title->reviews()
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        $userLists = Auth::user()
            ->watchlists()
            ->with('titles')
            ->get()
            ->keyBy('type');

        return view('titles.show', compact('title', 'reviews', 'userRating', 'userReview', 'userLists'));
    }

    public function storeRating(Request $request, Title $title): RedirectResponse
    {
        abort_unless($title->status === 'published', 404);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'between:1,10'],
        ]);

        $title->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['score' => $validated['score']],
        );

        return redirect()->route('titles.show', $title)
            ->with('status', 'Tu valoración se ha guardado correctamente.');
    }

    public function storeReview(Request $request, Title $title): RedirectResponse
    {
        abort_unless($title->status === 'published', 404);

        $validated = $request->validate([
            'headline' => ['nullable', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $title->reviews()->create([
            ...$validated,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('titles.show', $title)
            ->with('status', 'Tu reseña se ha enviado y está pendiente de moderación.');
    }

    public function updateReview(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'headline' => ['nullable', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $review->update([
            ...$validated,
            'status' => 'pending',
        ]);

        return redirect()->route('titles.show', $review->title)
            ->with('status', 'Tu reseña se ha actualizado y vuelve a estar pendiente de moderación.');
    }

    public function destroyReview(Review $review): RedirectResponse
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $title = $review->title;
        $review->delete();

        return redirect()->route('titles.show', $title)
            ->with('status', 'Tu reseña se ha eliminado correctamente.');
    }

    public function storeComment(Request $request, Review $review): RedirectResponse
    {
        abort_unless(
            $review->status === 'approved' || $review->user_id === Auth::id(),
            404,
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $review->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        return redirect()->route('titles.show', $review->title)
            ->with('status', 'Tu comentario se ha publicado correctamente.');
    }

    public function destroyComment(Comment $comment): RedirectResponse
    {
        abort_unless($comment->user_id === Auth::id(), 403);

        $title = $comment->review->title;
        $comment->delete();

        return redirect()->route('titles.show', $title)
            ->with('status', 'Tu comentario se ha eliminado correctamente.');
    }

    public function updateComment(Request $request, Comment $comment): RedirectResponse
    {
        abort_unless($comment->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $comment->update($validated);
        $title = $comment->review->title;

        return redirect()->route('titles.show', $title)
            ->with('status', 'Tu comentario se ha actualizado correctamente.');
    }
}
