@extends('layouts.app')

@section('content')
    <section class="catalog-shell">
        <div class="catalog-hero">
            <p class="catalog-hero__eyebrow">Descubre</p>
            <h1>Encuentra tu próxima película o serie</h1>
            <a href="{{ route('titles.surprise', request()->except('page', 'sort')) }}" class="surprise-button">Sorpréndeme</a>
        </div>

        <form method="GET" action="{{ auth()->check() ? route('dashboard') : route('catalog.explore') }}" class="catalog-search-form">
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
                    <a href="{{ auth()->check() ? route('dashboard') : route('catalog.explore') }}" class="catalog-filters__reset">Limpiar</a>
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

                @auth
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
                @else
                <div class="guest-filter-promo">
                    <span class="guest-filter-promo__badge">Más filtros</span>
                    <h3>Inicia sesión para desbloquear el resto de filtros y funcionalidades</h3>
                    <p>Accede a filtros por año, puntuación, director y actor. También podrás valorar películas, escribir reseñas y crear listas personales.</p>
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                </div>
                @endauth
            </aside>

            <section class="catalog-results">
                <div class="catalog-results__topbar">
                    <p aria-live="polite">
                        @if ($titles->total() > 0)
                            Mostrando del resultado <strong>{{ $titles->firstItem() }}</strong> al
                            <strong>{{ $titles->lastItem() }}</strong> sobre
                            <strong>{{ $titles->total() }}</strong>
                            {{ $titles->total() === 1 ? 'resultado' : 'resultados' }}
                        @else
                            No hay resultados que mostrar.
                        @endif
                    </p>
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

                @php
                    $currentPage = $titles->currentPage();
                    $lastPage = $titles->lastPage();

                    if ($lastPage <= 3) {
                        $firstVisiblePage = 1;
                        $lastVisiblePage = $lastPage;
                    } elseif ($lastPage <= 4) {
                        $firstVisiblePage = max(1, min($currentPage - 1, $lastPage - 2));
                        $lastVisiblePage = $firstVisiblePage + 2;
                    } else {
                        $firstVisiblePage = max(1, min($currentPage - 2, $lastPage - 4));
                        $lastVisiblePage = $firstVisiblePage + 4;
                    }

                    $visiblePages = $titles->getUrlRange($firstVisiblePage, $lastVisiblePage);
                @endphp

                @if ($titles->hasPages())
                    <nav class="catalog-pagination catalog-pagination--top" aria-label="Paginación del catálogo">
                        @if ($titles->onFirstPage())
                            <span class="catalog-pagination__link catalog-pagination__link--disabled" aria-disabled="true">Anterior</span>
                        @else
                            <a class="catalog-pagination__link" href="{{ $titles->previousPageUrl() }}" rel="prev">Anterior</a>
                        @endif

                        @foreach ($visiblePages as $page => $url)
                            @if ($page === $currentPage)
                                <span class="catalog-pagination__link catalog-pagination__link--active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="catalog-pagination__link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($titles->hasMorePages())
                            <a class="catalog-pagination__link catalog-pagination__link--next" href="{{ $titles->nextPageUrl() }}" rel="next">Siguiente</a>
                        @else
                            <span class="catalog-pagination__link catalog-pagination__link--disabled" aria-disabled="true">Siguiente</span>
                        @endif
                    </nav>
                @endif

                <div class="catalog-grid">
                    @forelse ($titles as $title)
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
                                <p>{{ $title->genres->pluck('name')->join(' · ') ?: 'Sin género' }} · {{ $title->release_date?->format('Y') ?: 'Sin año' }}</p>
                            </div>
                        </article>
                        </a>
                    @empty
                        <p class="catalog-empty">No hemos encontrado títulos con esos filtros.</p>
                    @endforelse
                </div>
                @if ($titles->hasPages())
                    <nav class="catalog-pagination catalog-pagination--bottom" aria-label="Paginación del catálogo">
                        @if ($titles->onFirstPage())
                            <span class="catalog-pagination__link catalog-pagination__link--disabled" aria-disabled="true">Anterior</span>
                        @else
                            <a class="catalog-pagination__link" href="{{ $titles->previousPageUrl() }}" rel="prev">Anterior</a>
                        @endif

                        @foreach ($visiblePages as $page => $url)
                            @if ($page === $currentPage)
                                <span class="catalog-pagination__link catalog-pagination__link--active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="catalog-pagination__link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($titles->hasMorePages())
                            <a class="catalog-pagination__link catalog-pagination__link--next" href="{{ $titles->nextPageUrl() }}" rel="next">Siguiente</a>
                        @else
                            <span class="catalog-pagination__link catalog-pagination__link--disabled" aria-disabled="true">Siguiente</span>
                        @endif
                    </nav>
                @endif
            </section>
            </div>
        </form>
    </section>
@endsection
