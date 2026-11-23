@extends('layouts.app')

@section('title', $title->title.' · CineHub')

@section('content')
    <article class="title-detail">
        <a href="{{ route('dashboard') }}" class="title-detail__back">Volver al catálogo</a>

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
                <div class="cast-section__heading">
                    <h2>Reparto principal</h2>
                    <span>{{ $title->credits->count() }}</span>
                </div>

                <div class="cast-list">
                    @foreach ($title->credits->take(8) as $credit)
                        <article class="cast-card">
                            <div
                                class="cast-card__photo @if (!$credit->person->photo_path) cast-card__photo--fallback @endif"
                                @if ($credit->person->photo_path) style="background-image: url('{{ $credit->person->photo_path }}')" @endif
                                role="img"
                                aria-label="Foto de {{ $credit->person->name }}"
                            >
                                @if (!$credit->person->photo_path)
                                    <span>{{ collect(explode(' ', trim($credit->person->name)))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span>
                                @endif
                            </div>
                            <div class="cast-card__body">
                                <h3>{{ $credit->person->name }}</h3>
                                <p>{{ $credit->character_name ?: 'Reparto' }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                @if ($userLists->where('type', 'custom')->isNotEmpty())
                    <form method="POST" action="{{ route('lists.store', $title) }}" class="custom-list-add">
                        @csrf
                        <label for="custom-list">Añadir a una lista personalizada</label>
                        <select id="custom-list" name="watchlist_id" required>
                            <option value="">Selecciona una lista</option>
                            @foreach ($userLists->where('type', 'custom') as $customList)
                                <option value="{{ $customList->id }}">{{ $customList->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit">Añadir</button>
                    </form>
                @endif
            </section>
        @endif

        <div class="title-detail__content">
            <section class="interaction-panel">
                <h2>Valora este título</h2>
                <form method="POST" action="{{ route('titles.ratings.store', $title) }}" class="rating-form">
                    @csrf
                    <label for="score">Tu puntuación</label>
                    <div class="rating-form__controls">
                        <select id="score" name="score" required>
                            <option value="">Selecciona una puntuación</option>
                            @for ($score = 1; $score <= 10; $score++)
                                <option value="{{ $score }}" @selected((int) $userRating === $score)>{{ $score }}/10</option>
                            @endfor
                        </select>
                        <button type="submit">{{ $userRating ? 'Actualizar valoración' : 'Guardar valoración' }}</button>
                    </div>
                </form>
            </section>

            <section class="interaction-panel">
                <h2>{{ $userReview ? 'Tu reseña' : 'Escribe una reseña' }}</h2>
                <form method="POST" action="{{ $userReview ? route('reviews.update', $userReview) : route('titles.reviews.store', $title) }}" class="review-form">
                    @csrf
                    @if ($userReview)
                        @method('PUT')
                    @endif
                    <label for="headline">Título de la reseña <span>(opcional)</span></label>
                    <input id="headline" name="headline" type="text" maxlength="120" value="{{ old('headline', $userReview?->headline) }}" placeholder="¿Qué te ha parecido?" />
                    <label for="body">Reseña</label>
                    <textarea id="body" name="body" rows="6" minlength="10" maxlength="5000" required placeholder="Escribe tu opinión...">{{ old('body', $userReview?->body) }}</textarea>
                    <button type="submit">{{ $userReview ? 'Actualizar reseña' : 'Publicar reseña' }}</button>
                </form>
                @if ($userReview)
                    <form method="POST" action="{{ route('reviews.destroy', $userReview) }}" class="review-delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar mi reseña</button>
                    </form>
                    <p class="review-status">
                        Estado:
                        {{ [
                            'approved' => 'publicada',
                            'pending' => 'pendiente de moderación',
                            'hidden' => 'oculta',
                        ][$userReview->status] ?? 'desconocido' }}
                    </p>
                @endif
            </section>
        </div>

        <section class="interaction-panel">
            <h2>Mis listas</h2>
            <div class="list-actions">
                @foreach ([
                    'favorites' => 'Favoritos',
                    'pending' => 'Pendientes',
                    'watched' => 'Vistos',
                ] as $type => $label)
                    @php($inList = $userLists->get($type)?->titles->contains($title) ?? false)
                    <form method="POST" action="{{ $inList ? route('lists.destroy', $title) : route('lists.store', $title) }}">
                        @csrf
                        @if ($inList)
                            @method('DELETE')
                        @endif
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit">{{ $inList ? 'Quitar de' : 'Añadir a' }} {{ $label }}</button>
                    </form>
                @endforeach
            </div>
        </section>

        <section class="reviews-section">
            <div class="reviews-section__heading">
                <h2>Reseñas de la comunidad</h2>
                <span>{{ $reviews->count() }}</span>
            </div>

            @forelse ($reviews as $review)
                <article class="review-card">
                    <div class="review-card__header">
                        <div>
                            <h3>{{ $review->headline ?: 'Reseña sin título' }}</h3>
                            <p>Por {{ $review->user->name }} · {{ $review->created_at->format('d/m/Y') }}</p>
                        </div>
                        @if ($review->status !== 'approved')
                            <span class="review-status">
                                {{ $review->status === 'hidden' ? 'Oculta' : 'Pendiente de moderación' }}
                            </span>
                        @endif
                    </div>
                    <p>{{ $review->body }}</p>
                    <div class="review-comments">
                        <h4>Comentarios</h4>
                        @foreach ($review->comments as $comment)
                            <div class="review-comment">
                                <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->body }}</p>
                                @if ($comment->user_id === auth()->id())
                                        <div class="comment-actions">
                                            <form method="POST" action="{{ route('comments.update', $comment) }}" class="comment-edit-form">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="body" value="{{ $comment->body }}" minlength="2" maxlength="1000" required>
                                                <button type="submit">Guardar</button>
                                            </form>
                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">Eliminar</button>
                                            </form>
                                        </div>
                                @endif
                            </div>
                        @endforeach
                        <form method="POST" action="{{ route('reviews.comments.store', $review) }}">
                            @csrf
                            <label for="comment-{{ $review->id }}">Añadir comentario</label>
                            <textarea id="comment-{{ $review->id }}" name="body" rows="2" minlength="2" maxlength="1000" required></textarea>
                            <button type="submit">Comentar</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="catalog-empty">Todavía no hay reseñas aprobadas para este título.</p>
            @endforelse
        </section>
    </article>
@endsection
