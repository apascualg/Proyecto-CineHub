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
    </div>
@endsection
