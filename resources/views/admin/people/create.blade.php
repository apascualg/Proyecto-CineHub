@extends('layouts.app')
@section('content')
    <section class="admin-page"><h1>Nueva persona</h1>
        <form method="POST" action="{{ route('admin.people.store') }}" class="admin-form">@csrf @include('admin.people._form')<button class="admin-button">Crear persona</button></form>
    </section>
@endsection
