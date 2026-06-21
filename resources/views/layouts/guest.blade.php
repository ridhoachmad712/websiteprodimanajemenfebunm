<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Masuk') &mdash; Admin {{ config('app.name') }}</title>

    <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/brand.css') }}" rel="stylesheet">
    @include('partials.theme')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="d-flex flex-column bg-light">
    <script src="{{ asset('tabler/js/tabler-theme.min.js') }}"></script>

    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark d-inline-flex align-items-center text-decoration-none">
                    @php($loginLogo = \App\Models\Setting::get('navbar.logo'))
                    @if ($loginLogo)
                        <img src="{{ Storage::url($loginLogo) }}" alt="{{ config('app.name') }}" style="height:52px;width:auto">
                    @else
                        <span class="avatar bg-primary text-white me-2">M</span>
                        <span class="fs-3 fw-bold">Admin Manajemen FEB UNM</span>
                    @endif
                </a>
            </div>

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('tabler/js/tabler.min.js') }}" defer></script>
</body>
</html>
