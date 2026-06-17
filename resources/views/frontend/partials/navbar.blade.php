{{-- Navbar publik — dirender dari tabel menus via View Composer ($mainMenu). --}}
<header class="navbar navbar-expand-md navbar-light d-print-none sticky-top bg-white border-bottom">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark pe-0 pe-md-3">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-reset text-decoration-none">
                <span class="avatar avatar-sm bg-primary text-white me-2">M</span>
                <span class="d-flex flex-column lh-1">
                    <span class="fw-bold">Manajemen</span>
                    <span class="text-secondary" style="font-size:.7rem">FEB UNM</span>
                </span>
            </a>
        </h1>

        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav ms-auto">
                @foreach ($mainMenu as $item)
                    @if ($item->activeChildren->isNotEmpty())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">{{ $item->title }}</a>
                            <div class="dropdown-menu">
                                @include('frontend.partials.menu-dropdown', ['items' => $item->activeChildren])
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $item->href }}" @if($item->target === '_blank') target="_blank" rel="noopener" @endif>{{ $item->title }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</header>
