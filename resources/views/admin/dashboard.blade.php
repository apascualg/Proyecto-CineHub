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
            <div class="admin-stat"><strong>{{ $approvedReviews }}</strong><span>Reseñas aprobadas</span></div>
            <div class="admin-stat"><strong>{{ $hiddenReviews }}</strong><span>Reseñas ocultas</span></div>
            <div class="admin-stat"><strong>{{ $totalRatings }}</strong><span>Valoraciones</span></div>
            <div class="admin-stat"><strong>{{ $totalComments }}</strong><span>Comentarios</span></div>
            <div class="admin-stat"><strong>{{ $averageRating ? number_format($averageRating, 1) : '—' }}</strong><span>Nota media</span></div>
        </div>

        <section class="admin-top-rated">
            <h2>Títulos mejor valorados</h2>
            @forelse ($topRatedTitles as $title)
                <div class="admin-top-rated__item">
                    <a href="{{ route('titles.show', $title) }}">{{ $title->title }}</a>
                    <strong>{{ number_format($title->ratings_avg_score, 1) }}/10</strong>
                </div>
            @empty
                <p>Todavía no hay valoraciones.</p>
            @endforelse
        </section>

        <div class="admin-actions">
            <a class="admin-action" href="{{ route('admin.titles.index') }}">Gestionar títulos</a>
            <a class="admin-action" href="{{ route('admin.genres.index') }}">Gestionar géneros</a>
            <a class="admin-action" href="{{ route('admin.people.index') }}">Gestionar personas</a>
            <a class="admin-action" href="{{ route('admin.credits.index') }}">Gestionar créditos</a>
            <a class="admin-action" href="{{ route('admin.users.index') }}">Gestionar usuarios</a>
            <a class="admin-action" href="{{ route('admin.reviews.index') }}">Moderar reseñas</a>
            <a class="admin-action" href="{{ route('admin.tmdb.search') }}">Importar desde TMDB</a>
        </div>
    </section>
@endsection
