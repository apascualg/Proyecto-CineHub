@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Editar persona</h1>
        <form method="POST" action="{{ route('admin.people.update', $person) }}" class="admin-form">@csrf @method('PUT') @include('admin.people._form')<button class="admin-button">Guardar cambios</button></form>
    </section>
@endsection
