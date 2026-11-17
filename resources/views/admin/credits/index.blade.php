@extends('layouts.app')
@section('content')
    <section class="admin-page">
        <div class="admin-page__header"><h1>Créditos</h1><a class="admin-button" href="{{ route('admin.credits.create') }}">Nuevo crédito</a></div>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Título</th><th>Persona</th><th>Rol</th><th>Personaje</th><th>Orden</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($credits as $credit)
                    <tr><td>{{ $credit->title->title }}</td><td>{{ $credit->person->name }}</td><td>{{ $credit->role_type }}</td><td>{{ $credit->character_name ?: '—' }}</td><td>{{ $credit->billing_order ?: '—' }}</td><td class="admin-table__actions">
                        <a href="{{ route('admin.credits.edit', $credit) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.credits.destroy', $credit) }}" onsubmit="return confirm('¿Eliminar este crédito?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Eliminar</button></form>
                    </td></tr>
                @empty
                    <tr><td colspan="6">No hay créditos.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        {{ $credits->links() }}
    </section>
@endsection
