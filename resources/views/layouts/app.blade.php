<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'CineHub' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="cine-app">
        <header class="cine-header">
            <div class="cine-header__inner">
                <a href="{{ url('/') }}" class="cine-brand" aria-label="CineHub inicio">
                    <span class="cine-brand__cine">Cine</span><span class="cine-brand__hub">Hub</span>
                </a>

                <nav class="cine-nav" aria-label="Navegación principal">
                    @auth
                        <a href="{{ route('dashboard') }}">Inicio</a>
                        <a href="{{ route('profile.edit') }}">Perfil</a>
                        <a href="{{ route('lists.index') }}">Mis listas</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Administración</a>
                            <a href="{{ route('admin.reviews.index') }}">Reseñas</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="cine-nav__logout">
                            @csrf
                            <button type="submit">Cerrar sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="cine-nav__button">Registrarse</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="cine-page">
            @if (session('status'))
                <p class="cine-flash" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div class="cine-alert" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </body>
</html>
