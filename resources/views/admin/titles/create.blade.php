@extends('layouts.app')

@section('content')
    <section class="admin-page">
        <h1>Nuevo título</h1>
        <form method="POST" action="{{ route('admin.titles.store') }}" class="admin-form">
            @csrf
            @include('admin.titles._form')
            <button class="admin-button" type="submit">Crear título</button>
        </form>
    </section>
@endsection
