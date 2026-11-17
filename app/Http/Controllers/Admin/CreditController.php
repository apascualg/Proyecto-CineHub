<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCreditRequest;
use App\Http\Requests\Admin\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\Person;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreditController extends Controller
{
    public function index(): View
    {
        return view('admin.credits.index', [
            'credits' => Credit::with(['title', 'person'])->orderBy('title_id')->orderBy('billing_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.credits.create', [
            'titles' => Title::orderBy('title')->get(),
            'people' => Person::orderBy('name')->get(),
        ]);
    }

    public function store(StoreCreditRequest $request): RedirectResponse
    {
        Credit::create($request->validated());

        return redirect()->route('admin.credits.index')->with('status', 'Crédito creado correctamente.');
    }

    public function edit(Credit $credit): View
    {
        return view('admin.credits.edit', [
            'credit' => $credit,
            'titles' => Title::orderBy('title')->get(),
            'people' => Person::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateCreditRequest $request, Credit $credit): RedirectResponse
    {
        $credit->update($request->validated());

        return redirect()->route('admin.credits.index')->with('status', 'Crédito actualizado correctamente.');
    }

    public function destroy(Credit $credit): RedirectResponse
    {
        $credit->delete();

        return redirect()->route('admin.credits.index')->with('status', 'Crédito eliminado correctamente.');
    }
}
