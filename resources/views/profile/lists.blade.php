@extends('layouts.app')

@section('content')
    <div class="profile-shell">
        <div class="profile-heading">
            <p class="catalog-hero__eyebrow">Tu biblioteca</p>
            <h1>Mis listas personales</h1>
            <p>Guarda títulos para volver a ellos cuando quieras.</p>
        </div>

        <div class="lists-grid">
            @foreach ($listNames as $type => $name)
                @php($watchlist = $watchlists->get($type))
                <section class="list-panel">
                    <div class="profile-card__heading">
                        <h2>{{ $name }}</h2>
                        <span class="profile-card__count">{{ $watchlist?->titles->count() ?? 0 }}</span>
                    </div>
                    @if ($watchlist?->titles->isNotEmpty())
                        @foreach ($watchlist->titles as $title)
                            <div class="list-item">
                                <a href="{{ route('titles.show', $title) }}">{{ $title->title }}</a>
                                <form method="POST" action="{{ route('lists.destroy', $title) }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <button type="submit" class="icon-action" aria-label="Quitar {{ $title->title }}">×</button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p class="empty-state">No tienes títulos en esta lista.</p>
                    @endif
                </section>
            @endforeach
        </div>

        <section class="custom-list-creator profile-card">
            <div class="profile-card__heading">
                <h2>Crear lista personalizada</h2>
            </div>
            <form method="POST" action="{{ route('lists.custom.store') }}" class="custom-list-form">
                @csrf
                <input name="name" type="text" maxlength="100" placeholder="Ej. Cine de verano" required>
                <input name="description" type="text" maxlength="500" placeholder="Descripción opcional">
                <button type="submit">Crear lista</button>
            </form>
        </section>

        @if ($customWatchlists->isNotEmpty())
            <section class="custom-lists">
                <div class="profile-card__heading">
                    <h2>Mis listas personalizadas</h2>
                </div>
                <div class="lists-grid">
                    @foreach ($customWatchlists as $watchlist)
                        <article class="list-panel">
                            <div class="profile-card__heading">
                                <h3>{{ $watchlist->name }}</h3>
                                <span class="profile-card__count">{{ $watchlist->titles->count() }}</span>
                            </div>
                            @if ($watchlist->description)
                                <p class="list-description">{{ $watchlist->description }}</p>
                            @endif
                            @foreach ($watchlist->titles as $title)
                                <div class="list-item">
                                    <a href="{{ route('titles.show', $title) }}">{{ $title->title }}</a>
                                    <form method="POST" action="{{ route('lists.destroy', $title) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="watchlist_id" value="{{ $watchlist->id }}">
                                        <button type="submit" class="icon-action" aria-label="Quitar {{ $title->title }}">×</button>
                                    </form>
                                </div>
                            @endforeach
                            <form method="POST" action="{{ route('lists.custom.destroy', $watchlist) }}" class="custom-list-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-button">Eliminar lista</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
