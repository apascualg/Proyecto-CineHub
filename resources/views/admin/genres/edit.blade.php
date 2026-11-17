@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Editar género</h1>
        <form method="POST" action="{{ route('admin.genres.update', $genre) }}" class="admin-form">@csrf @method('PUT') @include('admin.genres._form')<button class="admin-button">Guardar cambios</button></form>
    </section>
@endsection
