<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', ['users' => User::withCount(['reviews', 'ratings'])->latest()->paginate(20)]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($user->is($request->user()) && $request->validated('role') !== 'admin') {
            return back()->withErrors(['role' => 'No puedes quitarte tu propio rol de administrador.'])->withInput();
        }

        $data = $request->safe()->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }
        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Usuario actualizado correctamente.');
    }
}
