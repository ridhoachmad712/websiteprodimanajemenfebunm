<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', 'Beranda') &mdash; {{ config('app.name') }}</title>

    @include('frontend.partials.seo')

    {{-- Tabler core + icons + brand override --}}
    <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/brand.css') }}" rel="stylesheet">

    {{-- Font: Inter (konsisten dengan Tabler) --}}
    <style>@import url('https://rsms.me/inter/inter.css');</style>

    @stack('styles')
</head>
<body class="layout-fluid">
    {{-- Theme bootstrap (light/dark, sebelum render body) --}}
    <script src="{{ asset('tabler/js/tabler-theme.min.js') }}"></script>

    <div class="page">
        @include('frontend.partials.navbar')

        <main id="content">
            @yield('content')
        </main>

        @include('frontend.partials.footer')
    </div>

    <script src="{{ asset('tabler/js/tabler.min.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
