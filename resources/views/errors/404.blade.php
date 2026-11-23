@extends('layouts.app')

@section('title', 'Página no encontrada · CineHub')

@section('content')
    <section class="error-page">
        <span>404</span>
        <h1>No hemos encontrado esa página</h1>
        <p>Puede haber cambiado la dirección o ya no estar disponible.</p>
        <a href="{{ route(auth()->check() ? 'dashboard' : 'catalog.explore') }}" class="surprise-button">Volver al catálogo</a>
    </section>
@endsection
