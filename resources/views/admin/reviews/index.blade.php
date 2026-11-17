@extends('layouts.app')
@section('content')
    <section class="admin-page">
        <div class="admin-page__header"><h1>Moderación de reseñas</h1></div>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Título</th><th>Usuario</th><th>Reseña</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td>{{ $review->title->title }}</td><td>{{ $review->user->name }}</td><td><strong>{{ $review->headline ?: 'Sin título' }}</strong><br>{{ $review->body }}</td>
                        <td><span class="admin-status admin-status--{{ $review->status }}">{{ $review->status }}</span></td>
                        <td class="admin-table__actions">
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">@csrf @method('PUT')<select name="status"><option value="pending" @selected($review->status === 'pending')>Pendiente</option><option value="approved" @selected($review->status === 'approved')>Aprobar</option><option value="hidden" @selected($review->status === 'hidden')>Ocultar</option><option value="rejected" @selected($review->status === 'rejected')>Rechazar</option></select><button type="submit">Guardar</button></form>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('¿Eliminar esta reseña?')">@csrf @method('DELETE')<button class="admin-danger" type="submit">Eliminar</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay reseñas.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        {{ $reviews->links() }}
    </section>
@endsection
