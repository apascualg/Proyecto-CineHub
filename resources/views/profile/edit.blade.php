@extends('layouts.app')

@section('content')
    <div class="profile-shell">
        <div class="profile-heading">
            <p class="catalog-hero__eyebrow">Cuenta CineHub</p>
            <h1>Mi perfil</h1>
            <p>Gestiona tus datos y consulta toda tu actividad.</p>
        </div>

        <div class="profile-grid">
            <section class="profile-card profile-card--account">
                <h2>Datos personales</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                    @csrf
                    @method('PUT')

                    <label for="name">Nombre</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>

                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>

                    <button type="submit">Guardar cambios</button>
                </form>
            </section>

            <section class="profile-card">
                <div class="profile-card__heading">
                    <h2>Mis valoraciones</h2>
                    <span class="profile-card__count">{{ $user->ratings->count() }}</span>
                </div>
                @forelse ($user->ratings as $rating)
                    <div class="profile-list-item">
                        <a href="{{ route('titles.show', $rating->title) }}">{{ $rating->title->title }}</a>
                        <strong>{{ $rating->score }}/10</strong>
                    </div>
                @empty
                    <p class="empty-state">Todavía no has valorado ningún título.</p>
                @endforelse
            </section>

            <section class="profile-card profile-card--wide">
                <div class="profile-card__heading">
                    <h2>Mis reseñas</h2>
                    <span class="profile-card__count">{{ $user->reviews->count() }}</span>
                </div>
                @forelse ($user->reviews as $review)
                    <article class="profile-review">
                        <div>
                            <a href="{{ route('titles.show', $review->title) }}">{{ $review->title->title }}</a>
                            <span class="status-chip status-chip--{{ $review->status }}">
                                {{ $review->status === 'approved' ? 'Publicada' : ($review->status === 'hidden' ? 'Oculta' : 'Pendiente') }}
                            </span>
                        </div>
                        <p>{{ $review->body }}</p>
                    </article>
                @empty
                    <p class="empty-state">Todavía no has escrito ninguna reseña.</p>
                @endforelse
            </section>

            <section class="profile-card">
                <div class="profile-card__heading">
                    <h2>Mis listas</h2>
                    <a class="text-link" href="{{ route('lists.index') }}">Gestionar</a>
                </div>
                @foreach ($user->watchlists as $watchlist)
                    <div class="profile-list-item">
                        <span>{{ $watchlist->name }}</span>
                        <strong>{{ $watchlist->titles->count() }}</strong>
                    </div>
                @endforeach
            </section>
        </div>
    </div>
@endsection
