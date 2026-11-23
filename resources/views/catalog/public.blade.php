@extends('layouts.app')

@section('title', 'Explorar catálogo · CineHub')

@section('content')
    <section class="catalog-shell">
        <div class="catalog-hero">
            <p class="catalog-hero__eyebrow">Catálogo público</p>
            <h1>Explora películas y series</h1>
        </div>

        <form method="GET" action="{{ route('catalog.explore') }}" class="catalog-search-form">
            <div class="catalog-toolbar">
                <div class="catalog-search">
                    <input type="search" name="title" value="{{ $filters['title'] ?? '' }}" placeholder="Buscar por título" aria-label="Buscar por título">
                    <button type="submit">Buscar</button>
                </div>
            </div>

            <div class="catalog-layout">
                <aside class="catalog-filters">
                    <div class="catalog-filters__header">
                        <h2>Filtros</h2>
                        <a href="{{ route('catalog.explore') }}" class="catalog-filters__reset">Limpiar</a>
                    </div>
                    <div class="filter-group">
                        <label for="public-type">Tipo</label>
                        <select id="public-type" name="type">
                            <option value="">Todos</option>
                            <option value="movie" @selected(($filters['type'] ?? '') === 'movie')>Películas</option>
                            <option value="series" @selected(($filters['type'] ?? '') === 'series')>Series</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="public-genre">Género</label>
                        <select id="public-genre" name="genre">
                            <option value="">Todos los géneros</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" @selected((string) ($filters['genre'] ?? '') === (string) $genre->id)>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @guest
                        <div class="guest-filter-promo">
                            <span class="guest-filter-promo__badge">Más filtros</span>
                            <h3>Inicia sesión para explorar más</h3>
                            <p>Podrás combinar búsqueda por:</p>
                            <ul>
                                <li>Año de estreno</li>
                                <li>Puntuación mínima</li>
                                <li>Director y actor</li>
                            </ul>
                            <a href="{{ route('login') }}">Iniciar sesión</a>
                        </div>
                    @endguest
                </aside>

                <section class="catalog-results">
                    <div class="catalog-results__topbar">
                        <p>Mostrando <strong>{{ $titles->total() }}</strong> resultados</p>
                        <label for="public-catalog-sort">
                            Ordenar
                            <select id="public-catalog-sort" name="sort" onchange="this.form.submit()">
                                <option value="release_desc" @selected($sort === 'release_desc')>Estreno: más reciente</option>
                                <option value="release_asc" @selected($sort === 'release_asc')>Estreno: más antiguo</option>
                                <option value="title" @selected($sort === 'title')>Título</option>
                                <option value="rating_desc" @selected($sort === 'rating_desc')>Mejor puntuación</option>
                                <option value="rating_asc" @selected($sort === 'rating_asc')>Peor puntuación</option>
                            </select>
                        </label>
                    </div>
                    <div class="catalog-grid">
                        @forelse ($titles as $title)
                            <a href="{{ route('catalog.show', $title) }}" class="movie-card__link">
                                <article class="movie-card">
                                    <div class="movie-card__poster @if (!$title->poster_path) poster--fallback @endif" @if ($title->poster_path) style="background-image: url('{{ $title->poster_path }}')" @endif></div>
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
                            <p class="catalog-empty">No hemos encontrado títulos con esos filtros.</p>
                        @endforelse
                    </div>
                    <div class="catalog-pagination">{{ $titles->links() }}</div>
                </section>
            </div>
        </form>
    </section>
@endsection
