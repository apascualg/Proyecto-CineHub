@extends('layouts.app')

@section('title', $title->title.' · CineHub')

@section('content')
    <article class="title-detail">
        <a href="{{ route('catalog.explore') }}" class="title-detail__back">Volver al catálogo</a>
        <header class="title-detail__header">
            <div class="title-detail__poster @if (!$title->poster_path) poster--fallback @endif" @if ($title->poster_path) style="background-image: url('{{ $title->poster_path }}')" @endif></div>
            <div class="title-detail__intro">
                <span class="movie-card__type">{{ $title->type === 'series' ? 'Serie' : 'Película' }}</span>
                <h1>{{ $title->title }}</h1>
                @if ($title->original_title && $title->original_title !== $title->title)
                    <p class="title-detail__original">{{ $title->original_title }}</p>
                @endif
                <p class="title-detail__meta">{{ $title->release_date?->format('Y') ?: 'Sin año' }} · {{ $title->genres->pluck('name')->join(' · ') ?: 'Sin género' }}</p>
                <p class="title-detail__synopsis">{{ $title->synopsis ?: 'Este título todavía no tiene sinopsis.' }}</p>
                <div class="title-detail__score">
                    <strong>{{ $title->ratings_avg_score ? number_format($title->ratings_avg_score, 1) : 'Sin nota' }}</strong>
                    <span>{{ $title->ratings_count }} valoraciones</span>
                </div>
                @guest
                    <a class="surprise-button" href="{{ route('login') }}">Inicia sesión para valorar</a>
                @endguest
            </div>
        </header>

        @if (!empty($title->streaming_providers))
            <section class="streaming-section">
                <h2>Plataformas de streaming</h2>
                <div class="streaming-providers">
                    @foreach ($title->streaming_providers as $provider)
                        <div class="streaming-provider">
                            @if (!empty($provider['logo_url']))
                                <img src="{{ $provider['logo_url'] }}" alt="{{ $provider['name'] }}" loading="lazy">
                            @else
                                <span class="streaming-provider__fallback">{{ mb_substr($provider['name'], 0, 1) }}</span>
                            @endif
                            <span>{{ $provider['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($title->credits->isNotEmpty())
            <section class="cast-section">
                <div class="cast-section__heading"><h2>Reparto y equipo</h2></div>
                <div class="cast-list">
                    @foreach ($title->credits->take(8) as $credit)
                        <article class="cast-card">
                            <div class="cast-card__body">
                                <h3>{{ $credit->person->name }}</h3>
                                <p>{{ $credit->character_name ?: ucfirst($credit->role_type) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($related->isNotEmpty())
            <section class="related-titles">
                <h2>Títulos relacionados</h2>
                <div class="catalog-grid">
                    @foreach ($related as $item)
                        <a href="{{ route('catalog.show', $item) }}" class="movie-card__link">
                            <article class="movie-card">
                                <div class="movie-card__poster poster--fallback"></div>
                                <div class="movie-card__body">
                                    <h3>{{ $item->title }}</h3>
                                    <p>{{ $item->genres->pluck('name')->join(' · ') }}</p>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection
