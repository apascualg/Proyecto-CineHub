@extends('layouts.app')

@section('content')
    <h1>Bienvenido a CineHub</h1>
    <p>Has iniciado sesión como {{ auth()->user()->name }}.</p>
@endsection
