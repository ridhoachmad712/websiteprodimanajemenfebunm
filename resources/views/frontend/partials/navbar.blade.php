{{--
    Navbar publik — struktur menu STATIS untuk fase scaffolding.
    Akan diganti menu dinamis (3 level) pada Tugas Menu Builder.
--}}
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-profil" data-bs-toggle="dropdown" role="button" aria-expanded="false">Profil</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Profil Program Studi</a>
                        <a class="dropdown-item" href="#">Daftar Dosen</a>
                        <a class="dropdown-item" href="#">Peta Proses Bisnis</a>
                        <a class="dropdown-item" href="#">Akreditasi</a>
                        <a class="dropdown-item" href="#">Fasilitas</a>
                        <a class="dropdown-item" href="#">Galeri</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-akademik" data-bs-toggle="dropdown" role="button" aria-expanded="false">Akademik</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Kurikulum</a>
                        <a class="dropdown-item" href="#">Kalender Akademik</a>
                        <a class="dropdown-item" href="#">Daftar Seminar</a>
                        <a class="dropdown-item" href="#">Prestasi</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-kemahasiswaan" data-bs-toggle="dropdown" role="button" aria-expanded="false">Kemahasiswaan</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">HIMA Manajemen</a>
                        <a class="dropdown-item" href="#">KMM Asy Asyaamil</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-alumni" data-bs-toggle="dropdown" role="button" aria-expanded="false">Alumni</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Direktori Alumni</a>
                        <a class="dropdown-item" href="#">Tracer Study</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-jurnal" data-bs-toggle="dropdown" role="button" aria-expanded="false">Jurnal</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">ICOMAN 2025</a>
                        <a class="dropdown-item" href="#">Jurnal (OJS)</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Download</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Hubungi Kami</a>
                </li>
            </ul>
        </div>
    </div>
</header>
