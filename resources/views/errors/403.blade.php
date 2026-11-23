@extends('layouts.app')

@section('title', 'Acceso denegado · CineHub')

@section('content')
    <section class="error-page">
        <span>403</span>
        <h1>No tienes permiso para acceder</h1>
        <p>Esta sección está reservada para usuarios autorizados.</p>
        <a href="{{ route(auth()->check() ? 'dashboard' : 'catalog.explore') }}" class="surprise-button">Volver al catálogo</a>
    </section>
@endsection
