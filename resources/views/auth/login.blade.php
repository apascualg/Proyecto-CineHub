@extends('layouts.app')

@section('content')
    <section class="auth-card" aria-labelledby="login-title">
        <p class="auth-card__eyebrow">Bienvenido a CineHub</p>
        <h1 id="login-title">Iniciar sesión</h1>
        <p class="auth-card__intro">Accede para descubrir tu próxima película o serie.</p>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            <div class="auth-form__field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            </div>

            <div class="auth-form__field">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" required autocomplete="current-password">
            </div>

            <label class="auth-form__remember">
                <input name="remember" type="checkbox" value="1">
                <span>Recordarme</span>
            </label>

            <button type="submit" class="auth-form__submit">Entrar</button>
        </form>

        <p class="auth-card__footer"><a href="{{ route('password.request') }}">¿Has olvidado tu contraseña?</a></p>

        <p class="auth-card__footer">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
    </section>
@endsection
