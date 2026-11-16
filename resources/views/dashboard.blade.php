@extends('layouts.app')

@section('content')
    <section class="catalog-shell">
        <div class="catalog-hero">
            <p class="catalog-hero__eyebrow">Descubre</p>
            <h1>Encuentra tu próxima película o serie</h1>
        </div>

        <div class="catalog-toolbar">
            <div class="catalog-search">
                <input type="text" placeholder="Busca avanzada" aria-label="Buscar contenido" />
                <button type="button">Buscar</button>
            </div>
        </div>

        <div class="catalog-layout">
            <aside class="catalog-filters">
                <div class="catalog-filters__header">
                    <h2>Filtros</h2>
                    <button type="button" class="catalog-filters__reset">Limpiar</button>
                </div>

                <div class="filter-group">
                    <label for="type">Tipo</label>
                    <select id="type">
                        <option>Películas</option>
                        <option>Series</option>
                        <option>Todos</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="genre">Género</label>
                    <select id="genre">
                        <option>Acción</option>
                        <option>Drama</option>
                        <option>Ciencia ficción</option>
                        <option>Animación</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="year">Año</label>
                    <select id="year">
                        <option>2024</option>
                        <option>2023</option>
                        <option>2022</option>
                        <option>2021+</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="rating">Puntuación</label>
                    <select id="rating">
                        <option>7.0 o más</option>
                        <option>8.0 o más</option>
                        <option>9.0 o más</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="director">Director</label>
                    <input id="director" type="text" placeholder="Nombre del director" />
                </div>

                <div class="filter-group">
                    <label for="actor">Actor</label>
                    <input id="actor" type="text" placeholder="Nombre del actor" />
                </div>
            </aside>

            <section class="catalog-results">
                <div class="catalog-results__topbar">
                    <p>Mostrando <strong>24</strong> resultados</p>
                    <div class="catalog-results__sort">
                        <span>Ordenar por:</span>
                        <button type="button">Más relevantes</button>
                    </div>
                </div>

                <div class="catalog-grid">
                    <article class="movie-card">
                        <div class="movie-card__poster poster--one"></div>
                        <div class="movie-card__body">
                            <div class="movie-card__meta">
                                <span class="movie-card__type">Película</span>
                                <span class="movie-card__rating">8.7</span>
                            </div>
                            <h3>La ciudad de las sombras</h3>
                            <p>Drama · 2024</p>
                        </div>
                    </article>

                    <article class="movie-card">
                        <div class="movie-card__poster poster--two"></div>
                        <div class="movie-card__body">
                            <div class="movie-card__meta">
                                <span class="movie-card__type">Serie</span>
                                <span class="movie-card__rating">9.1</span>
                            </div>
                            <h3>Seis pasos a la luna</h3>
                            <p>Ciencia ficción · 2023</p>
                        </div>
                    </article>

                    <article class="movie-card">
                        <div class="movie-card__poster poster--three"></div>
                        <div class="movie-card__body">
                            <div class="movie-card__meta">
                                <span class="movie-card__type">Película</span>
                                <span class="movie-card__rating">8.2</span>
                            </div>
                            <h3>Velocidad del amanecer</h3>
                            <p>Acción · 2022</p>
                        </div>
                    </article>

                    <article class="movie-card">
                        <div class="movie-card__poster poster--four"></div>
                        <div class="movie-card__body">
                            <div class="movie-card__meta">
                                <span class="movie-card__type">Serie</span>
                                <span class="movie-card__rating">8.5</span>
                            </div>
                            <h3>Horizonte paralelo</h3>
                            <p>Drama · 2024</p>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </section>
@endsection
