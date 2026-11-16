@extends('layouts.app')

@section('content')
    <section class="auth-card" aria-labelledby="register-title">
        <p class="auth-card__eyebrow">Únete a CineHub</p>
        <h1 id="register-title">Crear cuenta</h1>
        <p class="auth-card__intro">Guarda tus preferencias y encuentra contenido a tu medida.</p>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            <div class="auth-form__field">
                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <div class="auth-form__field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="auth-form__field">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" required autocomplete="new-password">
            </div>

            <div class="auth-form__field">
                <label for="password_confirmation">Repetir contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
            </div>

            <button type="submit" class="auth-form__submit">Registrarse</button>
        </form>

        <p class="auth-card__footer">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </section>
@endsection
