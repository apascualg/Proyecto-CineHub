@extends('layouts.app')

@section('content')
    <section class="auth-card" aria-labelledby="verify-title">
        <p class="auth-card__eyebrow">Verificación necesaria</p>
        <h1 id="verify-title">Verifica tu correo</h1>
        <p class="auth-card__intro">Te hemos enviado un enlace de verificación al correo registrado.</p>

        <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
            @csrf
            <button type="submit" class="auth-form__submit">Reenviar enlace</button>
        </form>
    </section>
@endsection
