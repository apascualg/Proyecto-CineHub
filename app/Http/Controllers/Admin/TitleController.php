<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTitleRequest;
use App\Http\Requests\Admin\UpdateTitleRequest;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TitleController extends Controller
{
    public function index(): View
    {
        return view('admin.titles.index', [
            'titles' => Title::with('genres')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.titles.create', ['genres' => Genre::orderBy('name')->get()]);
    }

    public function store(StoreTitleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $genres = $validated['genres'] ?? [];
        unset($validated['genres']);
        $validated['streaming_providers'] = $this->parseStreamingProviders(
            $validated['streaming_providers'] ?? null,
        );

        DB::transaction(function () use ($validated, $genres): void {
            $title = Title::create($validated);
            $title->genres()->sync($genres);
        });

        return redirect()->route('admin.titles.index')->with('status', 'Título creado correctamente.');
    }

    public function edit(Title $title): View
    {
        return view('admin.titles.edit', [
            'title' => $title->load('genres'),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTitleRequest $request, Title $title): RedirectResponse
    {
        $validated = $request->validated();
        $genres = $validated['genres'] ?? [];
        unset($validated['genres']);
        $validated['streaming_providers'] = $this->parseStreamingProviders(
            $validated['streaming_providers'] ?? null,
        );

        DB::transaction(function () use ($title, $validated, $genres): void {
            $title->update($validated);
            $title->genres()->sync($genres);
        });

        return redirect()->route('admin.titles.index')->with('status', 'Título actualizado correctamente.');
    }

    public function destroy(Title $title): RedirectResponse
    {
        $title->delete();

        return redirect()->route('admin.titles.index')->with('status', 'Título eliminado correctamente.');
    }

    public function toggleStatus(Title $title): RedirectResponse
    {
        $title->update(['status' => $title->status === 'published' ? 'draft' : 'published']);

        return back()->with('status', 'Estado del título actualizado correctamente.');
    }

    private function parseStreamingProviders(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return collect(preg_split('/[,;\n]+/', $value))
            ->map(fn (string $name): string => trim($name))
            ->filter()
            ->unique(fn (string $name): string => mb_strtolower($name))
            ->map(fn (string $name): array => ['name' => $name, 'logo_url' => null])
            ->values()
            ->all();
    }
}
