<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', 'Dashboard') &mdash; Admin {{ config('app.name') }}</title>

    <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/brand.css') }}" rel="stylesheet">
    @include('partials.theme')
    <link href="{{ asset('tabler/css/admin-modern.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="admin-modern">
    <script src="{{ asset('tabler/js/tabler-theme.min.js') }}"></script>

    <div class="page">
        @include('admin.partials.sidebar')

        {{-- Topbar --}}
        <header class="navbar navbar-expand-md admin-topbar d-print-none">
            <div class="container-xl">
                <span class="admin-topbar-label d-none d-md-inline">Prodi Manajemen FEB UNM</span>
                <div class="navbar-nav flex-row order-md-last ms-auto">
                    @php($topUnread = \App\Models\ContactMessage::unread()->count())
                    <div class="nav-item me-2">
                        <a href="{{ route('admin.contacts.index') }}" class="nav-link px-0 position-relative" title="Pesan masuk" aria-label="Pesan masuk">
                            <i class="ti ti-mail fs-2"></i>
                            @if ($topUnread > 0)
                                <span class="badge bg-red text-white badge-notification">{{ $topUnread }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Menu pengguna">
                            <span class="avatar avatar-sm bg-primary text-white">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth()->user()->name ?? 'Admin' }}</div>
                                <div class="mt-1 small text-secondary">Administrator</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="{{ url('/') }}" class="dropdown-item" target="_blank">Lihat Situs</a>
                            <a href="{{ route('admin.users.edit', auth()->user()) }}" class="dropdown-item">Akun Saya</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            {{-- Page header --}}
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">@yield('pretitle', 'Panel Admin')</div>
                            <h2 class="page-title">@yield('page-title', 'Dashboard')</h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Page body --}}
            <div class="page-body">
                <div class="container-xl">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div>{{ session('status') }}</div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div>{{ session('error') }}</div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="text-center text-secondary">
                        &copy; {{ date('Y') }} Prodi Manajemen FEB UNM &mdash; Panel Admin
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('tabler/js/tabler.min.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
