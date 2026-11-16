@extends('layouts.app')

@section('content')
    <h1>Mi perfil</h1>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>

        <button type="submit">Guardar cambios</button>
    </form>
@endsection
