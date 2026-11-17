@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Editar usuario</h1>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
            @csrf @method('PUT')
            <div class="admin-form__grid">
                <div><label for="name">Nombre</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required /></div>
                <div><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required /></div>
                <div><label for="role">Rol</label><select id="role" name="role" required><option value="user" @selected(old('role', $user->role) === 'user')>Usuario</option><option value="admin" @selected(old('role', $user->role) === 'admin')>Administrador</option></select></div>
                <div><label for="password">Nueva contraseña</label><input id="password" name="password" type="password" minlength="8" /></div>
                <div><label for="password_confirmation">Confirmar contraseña</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" /></div>
            </div>
            <button class="admin-button">Guardar cambios</button>
        </form>
    </section>
@endsection
