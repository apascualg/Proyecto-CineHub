<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TmdbService;
use App\Services\TmdbTitleImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class TmdbImportController extends Controller
{
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'query' => ['nullable', 'string', 'min:2', 'max:255'],
            'type' => ['nullable', 'in:movie,series'],
        ]);

        $results = [];
        $error = null;

        if (! empty($validated['query'])) {
            try {
                $results = app(TmdbService::class)->search(
                    $validated['query'],
                    $validated['type'] ?? 'movie',
                );
            } catch (Throwable $exception) {
                report($exception);
                $error = $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'No se pudo consultar TMDB. Revisa el token y la conexión.';
            }
        }

        return view('admin.tmdb.search', [
            'results' => $results,
            'query' => $validated['query'] ?? '',
            'type' => $validated['type'] ?? 'movie',
            'error' => $error,
        ]);
    }

    public function import(Request $request, TmdbTitleImporter $importer): RedirectResponse
    {
        $validated = $request->validate([
            'tmdb_id' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:movie,series'],
        ]);

        try {
            $tmdb = app(TmdbService::class);
            $data = $validated['type'] === 'series'
                ? $tmdb->series($validated['tmdb_id'])
                : $tmdb->movie($validated['tmdb_id']);

            $title = $importer->import($data, $validated['type']);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'tmdb' => $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'No se pudo importar el título desde TMDB.',
            ]);
        }

        return redirect()
            ->route('admin.titles.edit', $title)
            ->with('status', 'Título importado o actualizado desde TMDB.');
    }
}
