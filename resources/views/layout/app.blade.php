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
<body>
    @yield('content')
    <footer class="text-center mt-auto">
        <p class="mt-5 mb-3 text-muted">&copy; 2022 - {{ env('APP_NAME') }}</p>
    </footer>
    <script src="{{ mix('js/jquery.min.js') }}"></script>
    <script src="{{ mix('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ mix('js/app.min.js') }}"></script>
</body>
</html>
