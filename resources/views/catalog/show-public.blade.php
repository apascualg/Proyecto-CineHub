@extends('layouts.app')

@section('title', $title->title.' · CineHub')

@section('content')
    <article class="title-detail">
        <a href="{{ route('catalog.explore') }}" class="title-detail__back">Volver al catálogo</a>
        <header class="title-detail__header">
            @if ($title->poster_path)
                <img class="title-detail__poster" src="{{ $title->poster_path }}" alt="Portada de {{ $title->title }}">
            @else
                <div class="title-detail__poster title-detail__poster--fallback poster--fallback" aria-hidden="true"></div>
            @endif
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
                    @foreach (\App\Support\StreamingProvider::normalize(
                        $title->streaming_providers,
                        $title->title,
                    ) as $provider)
                        <a
                            class="streaming-provider"
                            href="{{ $provider['url'] ?: '#' }}"
                            @if (!empty($provider['url'])) target="_blank" rel="noopener noreferrer" @endif
                            aria-label="Ver {{ $title->title }} en {{ $provider['name'] }}"
                        >
                            @if (!empty($provider['logo_url']))
                                <img src="{{ $provider['logo_url'] }}" alt="{{ $provider['name'] }}" loading="lazy">
                            @else
                                <span class="streaming-provider__fallback">{{ mb_substr($provider['name'], 0, 1) }}</span>
                            @endif
                            <span>{{ $provider['name'] }}</span>
                        </a>
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
                            @if ($credit->person->photo_path)
                                <img class="cast-card__photo" src="{{ $credit->person->photo_path }}" alt="Foto de {{ $credit->person->name }}" loading="lazy">
                            @else
                                <div class="cast-card__photo cast-card__photo--fallback" role="img" aria-label="Sin foto de {{ $credit->person->name }}">
                                    {{ collect(explode(' ', trim($credit->person->name)))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}
                                </div>
                            @endif
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
                                @if ($item->poster_path)
                                    <img class="movie-card__poster movie-card__poster--image" src="{{ $item->poster_path }}" alt="Portada de {{ $item->title }}" loading="lazy">
                                @else
                                    <div class="movie-card__poster movie-card__poster--fallback poster--fallback" aria-hidden="true"></div>
                                @endif
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
