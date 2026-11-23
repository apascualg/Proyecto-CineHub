@extends('layouts.app')

@section('content')
    <section class="admin-page">
        <div class="admin-page__header">
            <div>
                <p class="catalog-hero__eyebrow">TMDB</p>
                <h1>Importar contenido</h1>
            </div>
            <a class="admin-button" href="{{ route('admin.titles.index') }}">Volver a títulos</a>
        </div>

        @if ($error)
            <p class="admin-error">{{ $error }}</p>
        @endif

        @error('tmdb')
            <p class="admin-error">{{ $message }}</p>
        @enderror

        <form method="GET" action="{{ route('admin.tmdb.search') }}" class="catalog-search-form">
            <label for="query">Título</label>
            <input id="query" name="query" type="search" value="{{ $query }}" minlength="2" required>

            <label for="type">Tipo</label>
            <select id="type" name="type">
                <option value="movie" @selected($type === 'movie')>Película</option>
                <option value="series" @selected($type === 'series')>Serie</option>
            </select>

            <button class="admin-button" type="submit">Buscar</button>
        </form>

        @if ($query !== '')
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Resultado</th>
                            <th>Año</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                            <tr>
                                <td>
                                    {{ $type === 'series' ? ($result['name'] ?? '') : ($result['title'] ?? '') }}
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Str::substr(
                                        $type === 'series'
                                            ? ($result['first_air_date'] ?? '')
                                            : ($result['release_date'] ?? ''),
                                        0,
                                        4,
                                    ) ?: '—' }}
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.tmdb.import') }}">
                                        @csrf
                                        <input type="hidden" name="tmdb_id" value="{{ $result['id'] }}">
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <button class="admin-button" type="submit">Importar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3">No se encontraron resultados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
