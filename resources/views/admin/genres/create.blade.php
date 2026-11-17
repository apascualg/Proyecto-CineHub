@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Nuevo género</h1>
        <form method="POST" action="{{ route('admin.genres.store') }}" class="admin-form">@csrf @include('admin.genres._form')<button class="admin-button">Crear género</button></form>
    </section>
@endsection
