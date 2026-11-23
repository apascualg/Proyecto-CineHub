@extends('layouts.app')

@section('content')
    <section class="auth-card" aria-labelledby="reset-title">
        <p class="auth-card__eyebrow">Nueva contraseña</p>
        <h1 id="reset-title">Restablecer contraseña</h1>

        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="auth-form__field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email">
            </div>
            <div class="auth-form__field">
                <label for="password">Nueva contraseña</label>
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
            </div>
            <div class="auth-form__field">
                <label for="password_confirmation">Repetir contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password">
            </div>
            <button type="submit" class="auth-form__submit">Guardar contraseña</button>
        </form>
    </section>
@endsection
