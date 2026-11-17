<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGenreRequest;
use App\Http\Requests\Admin\UpdateGenreRequest;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(): View
    {
        return view('admin.genres.index', ['genres' => Genre::withCount('titles')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.genres.create');
    }

    public function store(StoreGenreRequest $request): RedirectResponse
    {
        Genre::create($request->validated());

        return redirect()->route('admin.genres.index')->with('status', 'Género creado correctamente.');
    }

    public function edit(Genre $genre): View
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(UpdateGenreRequest $request, Genre $genre): RedirectResponse
    {
        $genre->update($request->validated());

        return redirect()->route('admin.genres.index')->with('status', 'Género actualizado correctamente.');
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        $genre->titles()->detach();
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('status', 'Género eliminado correctamente.');
    }
}
