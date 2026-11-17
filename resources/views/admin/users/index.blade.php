@extends('layouts.app')
@section('content')
    <section class="admin-page">
        <div class="admin-page__header"><h1>Usuarios</h1></div>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Reseñas</th><th>Valoraciones</th><th>Acciones</th></tr></thead>
            <tbody>
                @foreach ($users as $user)
                    <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role }}</td><td>{{ $user->reviews_count }}</td><td>{{ $user->ratings_count }}</td><td><a href="{{ route('admin.users.edit', $user) }}">Editar</a></td></tr>
                @endforeach
            </tbody>
        </table></div>
        {{ $users->links() }}
    </section>
@endsection
