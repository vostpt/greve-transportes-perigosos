<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no,maximum-scale=1,user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VOST') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/custom.css') }}">
    @yield('styles')
</head>

<body>
    <header class="iframe-remove">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary" role="navigation">
            <a class="navbar-brand" href="{{ route('map') }}">
                <img src="/img/VOSTPT_FuelCrisisPT_JNDPA_Logo_With_VOSTPT_Logo_800pxX800px.png" style="height: 50px; padding:0" alt="Já Não Dá Para Abastecer - VOST Portugal" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('map') }}">{{ __('Início') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stats') }}">{{ __('Estatísticas') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">{{ __('Sobre') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('media') }}">{{ __('Media') }}</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Acesso Reservado') }}</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('entries.list') }}">{{ __('Entries') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stations.list') }}">{{ __('Fuel Stations') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdownExternalAuth" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            v-pre>{{ __('External Auth') }}</a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdownExternalAuth">
                            <a class="dropdown-item" href="{{ route('externalauth.add') }}">{{ __('Add') }}</a>
                            <a class="dropdown-item" href="{{ route('externalauth.list') }}">{{ __('List') }}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdownUsers" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            v-pre>{{ __('Users') }}</a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdownUsers">
                            <a class="dropdown-item" href="{{ route('users.add') }}">{{ __('Add') }}</a>
                            <a class="dropdown-item" href="{{ route('users.list') }}">{{ __('List') }}</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('options.list') }}">{{ __('Options') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdownMyUse" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMyUse">
                            <a class="dropdown-item" href="{{ route('users.password') }}">{{ __('Change Password') }}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </nav>
    </header>
    <main role="main">
        @yield('content')
    </main>
    <script src="{{ mix('/js/app.js') }}" charset="utf-8"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128563766-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-128563766-2');
    </script>
    @yield('javascript')
</body>

</html>