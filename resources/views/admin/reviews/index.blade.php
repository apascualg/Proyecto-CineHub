@extends('layouts.app')
@section('content')
    <section class="admin-page">
        <div class="admin-page__header"><h1>Moderación de reseñas</h1></div>
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="admin-review-filter">
            <label for="review-status">Filtrar por estado</label>
            <select id="review-status" name="status">
                <option value="">Todas las reseñas</option>
                <option value="pending" @selected($selectedStatus === 'pending')>Pendientes</option>
                <option value="approved" @selected($selectedStatus === 'approved')>Publicadas</option>
                <option value="hidden" @selected($selectedStatus === 'hidden')>Ocultas</option>
                <option value="rejected" @selected($selectedStatus === 'rejected')>Rechazadas</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <form id="bulk-review-form" method="POST" action="{{ route('admin.reviews.bulk-status') }}" class="admin-review-filter">
            @csrf
            @method('PATCH')
            <label for="bulk-status">Acción para las reseñas seleccionadas</label>
            <select id="bulk-status" name="status">
                <option value="pending">Pendiente</option>
                <option value="approved">Aprobar</option>
                <option value="hidden">Ocultar</option>
                <option value="rejected">Rechazar</option>
            </select>
            <button type="submit">Aplicar</button>
        </form>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Seleccionar</th><th>Título</th><th>Usuario</th><th>Reseña</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td><input type="checkbox" name="review_ids[]" value="{{ $review->id }}" form="bulk-review-form"></td>
                        <td>{{ $review->title->title }}</td><td>{{ $review->user->name }}</td><td><strong>{{ $review->headline ?: 'Sin título' }}</strong><br>{{ $review->body }}</td>
                        <td><span class="admin-status admin-status--{{ $review->status }}">{{ [
                            'pending' => 'Pendiente',
                            'approved' => 'Publicada',
                            'hidden' => 'Oculta',
                            'rejected' => 'Rechazada',
                        ][$review->status] ?? 'Desconocida' }}</span></td>
                        <td class="admin-table__actions">
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">@csrf @method('PUT')<select name="status"><option value="pending" @selected($review->status === 'pending')>Pendiente</option><option value="approved" @selected($review->status === 'approved')>Aprobar</option><option value="hidden" @selected($review->status === 'hidden')>Ocultar</option><option value="rejected" @selected($review->status === 'rejected')>Rechazar</option></select><button type="submit">Guardar</button></form>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('¿Eliminar esta reseña?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Eliminar</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No hay reseñas.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        {{ $reviews->links() }}
    </section>
@endsection
