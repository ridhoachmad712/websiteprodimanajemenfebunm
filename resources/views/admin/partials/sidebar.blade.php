{{--
    Sidebar admin (navbar-vertical Tabler).
    Item menu masih statis; route diisi bertahap saat tiap modul dibangun.
    Helper `request()->routeIs()` dipakai untuk menandai menu aktif.
--}}
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ url('/admin') }}" class="d-flex align-items-center text-reset text-decoration-none">
                <span class="avatar avatar-sm bg-primary text-white me-2">M</span>
                <span class="fw-bold">Admin Manajemen</span>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin') }}">
                        <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.posts.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-news"></i></span>
                        <span class="nav-link-title">Berita</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dosen.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-users"></i></span>
                        <span class="nav-link-title">Dosen</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.pages.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-file-text"></i></span>
                        <span class="nav-link-title">Halaman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-link-icon"><i class="ti ti-photo"></i></span>
                        <span class="nav-link-title">Galeri</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-link-icon"><i class="ti ti-menu-2"></i></span>
                        <span class="nav-link-title">Menu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-link-icon"><i class="ti ti-settings"></i></span>
                        <span class="nav-link-title">Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
