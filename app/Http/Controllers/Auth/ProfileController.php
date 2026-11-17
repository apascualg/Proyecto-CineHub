<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user()->load([
            'ratings.title',
            'reviews.title',
            'watchlists.titles',
        ]);

        return view('profile.edit', [
            'user' => $user,
            'stats' => [
                'ratings' => $user->ratings->count(),
                'reviews' => $user->reviews->count(),
                'comments' => $user->comments()->count(),
                'saved_titles' => $user->watchlists
                    ->flatMap(fn ($watchlist) => $watchlist->titles)
                    ->unique('id')
                    ->count(),
                'average_rating' => $user->ratings->avg('score'),
            ],
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Perfil actualizado correctamente.');
    }
}
