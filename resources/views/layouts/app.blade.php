<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="@yield('body-class')">
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-danger">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('map') }}">
                    <strong>JNDPA</strong> · VOST Portugal
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('map') }}">Início</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('stats') }}">Estatísticas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Sobre</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('media') }}">Media</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('error.new') }}">Reportar Erro</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('panel.login') }}">Acesso Reservado</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('panel.entries.list') }}">Entries</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('panel.stations.list') }}">Postos</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('panel.options.list') }}">Opções</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('panel.users.list') }}">Utilizadores</a></li>
                                    <li><a class="dropdown-item" href="{{ route('panel.externalauth.list') }}">External Auth</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('panel.logout') }}" class="px-3">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0">Terminar sessão</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        @if (session('status'))
            <div class="container mt-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            </div>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
