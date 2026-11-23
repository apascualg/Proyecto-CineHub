@extends('layouts.app')

@section('content')
    <section class="auth-card" aria-labelledby="forgot-title">
        <p class="auth-card__eyebrow">Recuperación de cuenta</p>
        <h1 id="forgot-title">¿Has olvidado tu contraseña?</h1>
        <p class="auth-card__intro">Te enviaremos un enlace seguro para restablecerla.</p>

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            <div class="auth-form__field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            </div>
            <button type="submit" class="auth-form__submit">Enviar enlace</button>
        </form>

        <p class="auth-card__footer"><a href="{{ route('login') }}">Volver a iniciar sesión</a></p>
    </section>
@endsection
