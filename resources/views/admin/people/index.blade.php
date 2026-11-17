@extends('layouts.app')
@section('content')
    <section class="admin-page">
        <div class="admin-page__header"><h1>Personas</h1><a class="admin-button" href="{{ route('admin.people.create') }}">Nueva persona</a></div>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Nombre</th><th>Fecha de nacimiento</th><th>Créditos</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($people as $person)
                    <tr><td>{{ $person->name }}</td><td>{{ $person->birth_date?->format('d/m/Y') ?: '—' }}</td><td>{{ $person->credits_count }}</td><td class="admin-table__actions">
                        <a href="{{ route('admin.people.edit', $person) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.people.destroy', $person) }}" onsubmit="return confirm('¿Eliminar esta persona?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Eliminar</button></form>
                    </td></tr>
                @empty
                    <tr><td colspan="4">No hay personas.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        {{ $people->links() }}
    </section>
@endsection
