<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WatchlistController extends Controller
{
    private const TYPES = [
        'favorites' => 'Favoritos',
        'pending' => 'Pendientes',
        'watched' => 'Vistos',
    ];

    public function index(): View
    {
        $watchlists = Auth::user()
            ->watchlists()
            ->whereIn('type', array_keys(self::TYPES))
            ->with('titles')
            ->get()
            ->keyBy('type');

        return view('profile.lists', [
            'watchlists' => $watchlists,
            'listNames' => self::TYPES,
        ]);
    }

    public function store(Request $request, Title $title): RedirectResponse
    {
        abort_unless($title->status === 'published', 404);

        $validated = $request->validate([
            'type' => ['required', 'in:'.implode(',', array_keys(self::TYPES))],
        ]);

        $watchlist = Auth::user()->watchlists()->firstOrCreate(
            ['type' => $validated['type']],
            [
                'name' => self::TYPES[$validated['type']],
                'description' => 'Lista personal de '.strtolower(self::TYPES[$validated['type']]).'.',
                'is_public' => false,
            ],
        );

        $watchlist->titles()->syncWithoutDetaching([$title->id]);

        return redirect()->route('titles.show', $title)
            ->with('status', 'El título se ha añadido a tu lista.');
    }

    public function destroy(Request $request, Title $title): RedirectResponse
    {
        abort_unless($title->status === 'published', 404);

        $validated = $request->validate([
            'type' => ['required', 'in:'.implode(',', array_keys(self::TYPES))],
        ]);

        Auth::user()
            ->watchlists()
            ->where('type', $validated['type'])
            ->first()?->titles()
            ->detach($title->id);

        return redirect()->route('titles.show', $title)
            ->with('status', 'El título se ha eliminado de tu lista.');
    }
}
