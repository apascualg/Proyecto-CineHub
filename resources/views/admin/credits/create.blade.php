@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Nuevo crédito</h1>
        <form method="POST" action="{{ route('admin.credits.store') }}" class="admin-form">@csrf @include('admin.credits._form')<button class="admin-button">Crear crédito</button></form>
    </section>
@endsection
