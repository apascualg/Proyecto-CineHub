@extends('layouts.app')

@section('content')
    <section class="catalog-shell">
        <div class="catalog-hero">
            <p class="catalog-hero__eyebrow">Descubre</p>
            <h1>Encuentra tu próxima película o serie</h1>
            <a href="{{ route('titles.surprise', request()->except('page', 'sort')) }}" class="surprise-button">Sorpréndeme</a>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="catalog-search-form">
            <div class="catalog-toolbar">
                <div class="catalog-search">
                    <input type="search" name="title" value="{{ $filters['title'] ?? '' }}" placeholder="Buscar por título" aria-label="Buscar por título" />
                    <button type="submit">Buscar</button>
                </div>
            </div>

            <div class="catalog-layout">
                <aside class="catalog-filters">
                <div class="catalog-filters__header">
                    <h2>Filtros</h2>
                    <a href="{{ route('dashboard') }}" class="catalog-filters__reset">Limpiar</a>
                </div>

                <div class="filter-group">
                    <label for="type">Tipo</label>
                    <select id="type" name="type">
                        <option value="">Todos</option>
                        <option value="movie" @selected(($filters['type'] ?? '') === 'movie')>Películas</option>
                        <option value="series" @selected(($filters['type'] ?? '') === 'series')>Series</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="genre">Género</label>
                    <select id="genre" name="genre">
                        <option value="">Todos los géneros</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}" @selected((string) ($filters['genre'] ?? '') === (string) $genre->id)>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <span class="filter-group__label">Año de estreno</span>
                    <div class="filter-year-range">
                        <label for="year-from">Desde</label>
                        <input id="year-from" name="year_from" value="{{ $filters['year_from'] ?? '' }}" type="number" min="1888" max="2100" placeholder="2024" />
                        <label for="year-to">Hasta</label>
                        <input id="year-to" name="year_to" value="{{ $filters['year_to'] ?? '' }}" type="number" min="1888" max="2100" placeholder="2026" />
                    </div>
                </div>

                <div class="filter-group">
                    <label for="rating">Puntuación</label>
                    <select id="rating" name="rating">
                        <option value="">Cualquier puntuación</option>
                        <option value="7" @selected(($filters['rating'] ?? '') == 7)>7.0 o más</option>
                        <option value="8" @selected(($filters['rating'] ?? '') == 8)>8.0 o más</option>
                        <option value="9" @selected(($filters['rating'] ?? '') == 9)>9.0 o más</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="director">Director</label>
                    <input id="director" name="director" value="{{ $filters['director'] ?? '' }}" type="text" placeholder="Nombre del director" />
                </div>

                <div class="filter-group">
                    <label for="actor">Actor</label>
                    <input id="actor" name="actor" value="{{ $filters['actor'] ?? '' }}" type="text" placeholder="Nombre del actor" />
                </div>
            </aside>

            <section class="catalog-results">
                <div class="catalog-results__topbar">
                    <p>Mostrando <strong>{{ $titles->total() }}</strong> resultados</p>
                    <label for="catalog-sort">
                        Ordenar
                        <select id="catalog-sort" name="sort" onchange="this.form.submit()">
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
                        <a href="{{ route('titles.show', $title) }}" class="movie-card__link">
                        <article class="movie-card">
                            <div class="movie-card__poster @if (!$title->poster_path) poster--fallback @endif" @if ($title->poster_path) style="background-image: url('{{ $title->poster_path }}')" @endif></div>
                            <div class="movie-card__body">
                                <div class="movie-card__meta">
                                    <span class="movie-card__type">{{ $title->type === 'series' ? 'Serie' : 'Película' }}</span>
                                    <span class="movie-card__rating">{{ $title->ratings_avg_score ? number_format($title->ratings_avg_score, 1) : 'Sin nota' }}</span>
                                </div>
                                <h3>{{ $title->title }}</h3>
                                <p>{{ $title->genres->pluck('name')->join(' · ') ?: 'Sin género' }} · {{ $title->release_date?->format('Y') ?: 'Sin año' }}</p>
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
