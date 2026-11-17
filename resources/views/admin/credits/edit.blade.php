@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Editar crédito</h1>
        <form method="POST" action="{{ route('admin.credits.update', $credit) }}" class="admin-form">@csrf @method('PUT') @include('admin.credits._form')<button class="admin-button">Guardar cambios</button></form>
    </section>
@endsection
