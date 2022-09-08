<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Restaurante</title>
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ mix('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.min.css') }}">
    <link rel="icon" href="{{ url('favicon.ico') }}" sizes="90x90" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ url('favicon.ico') }}" sizes="90x90" type="image/x-icon">
    <meta name="theme-color" content="#0d6efd">
</head>
<body class="bg-light d-flex flex-column">
<header
    class="navbar navbar-dark sticky-top align-content-center align-items-center bg-dark flex-md-nowrap px-2 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 p-2" href="#">Restaurante</a>
    <div class="navbar-nav-scroll d-flex">
        <div class="nav-item text-nowrap text-light my-auto">
            @if(Auth::check())
                <a class="nav-link px-2 me-2" href="{{ route('logout') }}">Sair</a>
            @else
                <a class="nav-link px-3" href="{{ route('login') }}">Entrar</a>
            @endif
        </div>
        @if(Route::currentRouteName()!=='login')
            <div class="nav-item text-nowrap">
                <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        @endif
    </div>
</header>
@yield('content')
<footer class="text-center mt-auto">
    <p class="my-3 text-muted">&copy; 2022 - {{ env('APP_NAME') }}</p>
</footer>
<script src="{{ mix('js/jquery.min.js') }}"></script>
<script src="{{ mix('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ mix('js/axios.min.js') }}"></script>
<script src="{{ mix('js/app.min.js') }}"></script>
</body>
</html>
