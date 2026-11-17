@extends('layouts.app')

@section('content')
    <section class="admin-page">
        <h1>Editar título</h1>
        <form method="POST" action="{{ route('admin.titles.update', $title) }}" class="admin-form">
            @csrf
            @method('PUT')
            @include('admin.titles._form')
            <button class="admin-button" type="submit">Guardar cambios</button>
        </form>
    </section>
@endsection
