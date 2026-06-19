<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('code') &mdash; {{ config('app.name') }}</title>

    {{-- Mandiri: hanya CSS statis, tanpa ketergantungan data/DB --}}
    <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/brand.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column bg-light">
    <div class="page page-center">
        <div class="container-tight py-4 text-center">
            <div class="display-1 fw-bold mb-0" style="font-size:7rem;line-height:1;color:var(--tblr-primary)">@yield('code')</div>
            <h1 class="h2 mt-3 mb-2">@yield('title')</h1>
            <p class="text-secondary mb-4">@yield('message')</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ url('/') }}" class="btn btn-primary"><i class="ti ti-home me-1"></i> Kembali ke Beranda</a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i> Halaman sebelumnya</a>
            </div>
        </div>
    </div>
</body>
</html>
