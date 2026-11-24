@extends('layouts.app')

@section('title', 'CineHub')

@section('content')
    <section class="home-hero">
        <div class="home-hero__orb home-hero__orb--one"></div>
        <div class="home-hero__orb home-hero__orb--two"></div>
        <div class="home-hero__copy">
            <p class="catalog-hero__eyebrow">Tu guía de cine y televisión</p>
            <h1>Encuentra tu próxima historia favorita</h1>
            <p>Explora películas y series, consulta sus fichas y descubre qué están valorando otros usuarios.</p>
            <div class="home-hero__actions">
                <a href="{{ route('dashboard') }}" class="surprise-button" title="Descubre películas y series">Explorar catálogo</a>
                @guest
                    <a href="{{ route('register') }}" class="home-secondary-action" title="Activa listas, valoraciones y filtros avanzados">Crear cuenta</a>
                @else
                    <a href="{{ route('titles.surprise') }}" class="home-secondary-action" title="Elige una película o serie al azar">Sorpréndeme</a>
                @endguest
            </div>
            <div class="home-hero__benefits" aria-label="Ventajas de CineHub">
                <span title="Guarda películas y series en tus listas">★ Listas personales</span>
                <span title="Puntúa del 1 al 10 y escribe reseñas">★ Valoraciones y reseñas</span>
                <span title="Filtra por año, puntuación, director y actor">★ Filtros avanzados</span>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="home-section__heading">
            <div>
                <p class="catalog-hero__eyebrow">Novedades</p>
                <h2>Últimos títulos</h2>
            </div>
            <a href="{{ route('dashboard') }}">Ver todo</a>
        </div>
        <div class="catalog-grid">
            @forelse ($featured as $title)
                <a href="{{ route(auth()->check() ? 'titles.show' : 'catalog.show', $title) }}" class="movie-card__link">
                    <article class="movie-card">
                        @if ($title->poster_path)
                            <img class="movie-card__poster movie-card__poster--image" src="{{ $title->poster_path }}" alt="Portada de {{ $title->title }}" loading="lazy">
                        @else
                            <div class="movie-card__poster poster--fallback" aria-hidden="true"></div>
                        @endif
                        <div class="movie-card__body">
                            <div class="movie-card__meta">
                                <span class="movie-card__type">{{ $title->type === 'series' ? 'Serie' : 'Película' }}</span>
                                <span class="movie-card__rating">{{ $title->ratings_avg_score ? number_format($title->ratings_avg_score, 1) : 'Sin nota' }}</span>
                            </div>
                            <h3>{{ $title->title }}</h3>
                            <p>{{ $title->genres->pluck('name')->join(' · ') ?: 'Sin género' }}</p>
                        </div>
                    </article>
                </a>
            @empty
                <p class="catalog-empty">Todavía no hay títulos publicados.</p>
            @endforelse
        </div>
    </section>

    <section class="home-section">
        <div class="home-section__heading">
            <div>
                <p class="catalog-hero__eyebrow">Comunidad</p>
                <h2>Mejor valorados</h2>
            </div>
        </div>
        <div class="catalog-grid">
            @foreach ($topRated as $title)
                <a href="{{ route(auth()->check() ? 'titles.show' : 'catalog.show', $title) }}" class="movie-card__link">
                    <article class="movie-card">
                        @if ($title->poster_path)
                            <img class="movie-card__poster movie-card__poster--image" src="{{ $title->poster_path }}" alt="Portada de {{ $title->title }}" loading="lazy">
                        @else
                            <div class="movie-card__poster poster--fallback" aria-hidden="true"></div>
                        @endif
                        <div class="movie-card__body">
                            <div class="movie-card__meta">
                                <span class="movie-card__type">{{ $title->type === 'series' ? 'Serie' : 'Película' }}</span>
                                <span class="movie-card__rating">{{ number_format($title->ratings_avg_score, 1) }}</span>
                            </div>
                            <h3>{{ $title->title }}</h3>
                            <p>{{ $title->genres->pluck('name')->join(' · ') ?: 'Sin género' }}</p>
                        </div>
                    </article>
                </a>
            @endforeach
        </div>
    </section>
@endsection
