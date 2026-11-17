@extends('layouts.app')

@section('content')
    <section class="admin-page">
        <div class="admin-page__header"><h1>Géneros</h1><a class="admin-button" href="{{ route('admin.genres.create') }}">Nuevo género</a></div>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Nombre</th><th>Slug</th><th>Títulos</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($genres as $genre)
                    <tr><td>{{ $genre->name }}</td><td>{{ $genre->slug }}</td><td>{{ $genre->titles_count }}</td><td class="admin-table__actions">
                        <a href="{{ route('admin.genres.edit', $genre) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.genres.destroy', $genre) }}" onsubmit="return confirm('¿Eliminar este género?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Eliminar</button></form>
                    </td></tr>
                @empty
                    <tr><td colspan="4">No hay géneros.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </section>
@endsection
