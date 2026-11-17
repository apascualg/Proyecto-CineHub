@extends('layouts.app')

@section('content')
    <div class="admin-shell">
        <div class="profile-heading">
            <p class="catalog-hero__eyebrow">Administración</p>
            <h1>Gestión de reseñas</h1>
            <p>Revisa el contenido enviado por la comunidad.</p>
        </div>

    @forelse ($reviews as $review)
        <article class="review-card admin-review-card">
            <header class="review-card__header">
                <div>
                    <h2>{{ $review->headline ?: 'Reseña sin título' }}</h2>
                    <p>{{ $review->user->name }} · {{ $review->title->title }}</p>
                </div>
                <span class="review-status">
                    {{ [
                        'approved' => 'Publicada',
                        'pending' => 'Pendiente de moderación',
                        'hidden' => 'Oculta',
                    ][$review->status] ?? 'Desconocido' }}
                </span>
            </header>
            <p>{{ $review->body }}</p>

            <div class="admin-review-actions">
                <form method="POST" action="{{ route('admin.reviews.status', $review) }}">
                    @csrf
                    @method('PATCH')
                    <label for="status-{{ $review->id }}">Estado</label>
                    <select id="status-{{ $review->id }}" name="status">
                        @foreach ([
                            'pending' => 'Pendiente de moderación',
                            'approved' => 'Publicada',
                            'hidden' => 'Oculta',
                        ] as $status => $label)
                            <option value="{{ $status }}" @selected($review->status === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Guardar estado</button>
                </form>

                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="danger-button">Eliminar reseña</button>
                </form>
            </div>
        </article>
    @empty
        <p class="catalog-empty">No hay reseñas para moderar.</p>
    @endforelse

    {{ $reviews->links() }}
    </div>
@endsection
