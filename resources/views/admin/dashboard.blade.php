@extends('layouts.app')

@section('content')
    <section class="admin-page">
        <div class="admin-page__header">
            <div>
                <p class="catalog-hero__eyebrow">Administración</p>
                <h1>Panel de administración</h1>
                <p>Gestiona el catálogo, las personas, los usuarios y la moderación.</p>
            </div>
        </div>

        <div class="admin-stats">
            <div class="admin-stat"><strong>{{ $totalTitles }}</strong><span>Títulos</span></div>
            <div class="admin-stat"><strong>{{ $publishedTitles }}</strong><span>Publicados</span></div>
            <div class="admin-stat"><strong>{{ $draftTitles }}</strong><span>Borradores</span></div>
            <div class="admin-stat"><strong>{{ $totalGenres }}</strong><span>Géneros</span></div>
            <div class="admin-stat"><strong>{{ $totalPeople }}</strong><span>Personas</span></div>
            <div class="admin-stat"><strong>{{ $totalUsers }}</strong><span>Usuarios</span></div>
            <div class="admin-stat"><strong>{{ $pendingReviews }}</strong><span>Reseñas pendientes</span></div>
        </div>

        <div class="admin-actions">
            <a class="admin-action" href="{{ route('admin.titles.index') }}">Gestionar títulos</a>
            <a class="admin-action" href="{{ route('admin.genres.index') }}">Gestionar géneros</a>
            <a class="admin-action" href="{{ route('admin.people.index') }}">Gestionar personas</a>
            <a class="admin-action" href="{{ route('admin.credits.index') }}">Gestionar créditos</a>
            <a class="admin-action" href="{{ route('admin.users.index') }}">Gestionar usuarios</a>
            <a class="admin-action" href="{{ route('admin.reviews.index') }}">Moderar reseñas</a>
        </div>
    </section>
@endsection
