@extends('layouts.app')

@section('content')
    <section class="admin-page">
        <div class="admin-page__header">
            <h1>Películas y series</h1>
            <div class="admin-table__actions">
                <a class="admin-button" href="{{ route('admin.tmdb.search') }}">Importar desde TMDB</a>
                <a class="admin-button" href="{{ route('admin.titles.create') }}">Nuevo título</a>
            </div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Título</th><th>Tipo</th><th>Estado</th><th>Géneros</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse ($titles as $title)
                        <tr>
                            <td>{{ $title->title }}</td>
                            <td>{{ $title->type === 'series' ? 'Serie' : 'Película' }}</td>
                            <td><span class="admin-status admin-status--{{ $title->status }}">{{ $title->status === 'published' ? 'Publicado' : 'Borrador' }}</span></td>
                            <td>{{ $title->genres->pluck('name')->join(', ') ?: '—' }}</td>
                            <td class="admin-table__actions">
                                <a href="{{ route('admin.titles.edit', $title) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.titles.toggle-status', $title) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">{{ $title->status === 'published' ? 'Desactivar' : 'Publicar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.titles.destroy', $title) }}" onsubmit="return confirm('¿Eliminar este título?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No hay títulos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $titles->links() }}
    </section>
@endsection
