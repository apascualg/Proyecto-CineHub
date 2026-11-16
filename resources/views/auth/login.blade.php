@extends('layouts.app')

@section('content')
    <h1>Iniciar sesión</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required>

        <label>
            <input name="remember" type="checkbox" value="1">
            Recordarme
        </label>

        <button type="submit">Entrar</button>
    </form>
@endsection
