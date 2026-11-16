@extends('layouts.app')

@section('content')
    <h1>Crear cuenta</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>

        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required>

        <label for="password_confirmation">Repetir contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>

        <button type="submit">Registrarse</button>
    </form>
@endsection
