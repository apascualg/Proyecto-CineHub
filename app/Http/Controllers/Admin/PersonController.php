<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePersonRequest;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function index(): View
    {
        return view('admin.people.index', ['people' => Person::withCount('credits')->orderBy('name')->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.people.create');
    }

    public function store(StorePersonRequest $request): RedirectResponse
    {
        Person::create($request->validated());

        return redirect()->route('admin.people.index')->with('status', 'Persona creada correctamente.');
    }

    public function edit(Person $person): View
    {
        return view('admin.people.edit', compact('person'));
    }

    public function update(StorePersonRequest $request, Person $person): RedirectResponse
    {
        $person->update($request->validated());

        return redirect()->route('admin.people.index')->with('status', 'Persona actualizada correctamente.');
    }

    public function destroy(Person $person): RedirectResponse
    {
        $person->delete();

        return redirect()->route('admin.people.index')->with('status', 'Persona eliminada correctamente.');
    }
}
