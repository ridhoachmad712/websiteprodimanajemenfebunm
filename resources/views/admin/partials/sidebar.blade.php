{{--
    Sidebar admin (navbar-vertical Tabler, gaya terang).
    Menu dikelompokkan: Konten · Tampilan Situs · Sistem.
--}}
@php($unread = \App\Models\ContactMessage::unread()->count())
<aside class="navbar navbar-vertical navbar-expand-lg bg-white border-end">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        @php($adminLogo = \App\Models\Setting::get('navbar.logo'))
        <div class="navbar-brand">
            <a href="{{ url('/admin') }}" class="d-flex align-items-center text-reset text-decoration-none">
                @if ($adminLogo)
                    <img src="{{ Storage::url($adminLogo) }}" alt="Admin" style="height:38px;width:auto">
                @else
                    <span class="avatar avatar-sm bg-primary text-white me-2">M</span>
                    <span class="fw-bold">Admin Manajemen</span>
                @endif
            </a>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-2">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/admin') }}">
                        <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>

                <li class="nav-section">Konten</li>
                <li class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.posts.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-news"></i></span>
                        <span class="nav-link-title">Berita</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.pengumuman.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-speakerphone"></i></span>
                        <span class="nav-link-title">Pengumuman</span>
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
                <li class="nav-item {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.gallery.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-photo"></i></span>
                        <span class="nav-link-title">Galeri</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.kegiatan.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.kegiatan.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-calendar-event"></i></span>
                        <span class="nav-link-title">Kegiatan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.prestasi.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.prestasi.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-trophy"></i></span>
                        <span class="nav-link-title">Prestasi</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.downloads.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-download"></i></span>
                        <span class="nav-link-title">Pusat Unduhan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.seminar.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.seminar.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-presentation"></i></span>
                        <span class="nav-link-title">Daftar Seminar</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.mitra.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.mitra.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-heart-handshake"></i></span>
                        <span class="nav-link-title">Mitra & Kerjasama</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.berita-eksternal.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.berita-eksternal.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-external-link"></i></span>
                        <span class="nav-link-title">Berita Eksternal</span>
                    </a>
                </li>

                @if (auth()->user()->isAdmin())
                    <li class="nav-section">Tampilan Situs</li>
                    <li class="nav-item {{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.home.edit') }}">
                            <span class="nav-link-icon"><i class="ti ti-layout-board"></i></span>
                            <span class="nav-link-title">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.appearance.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.appearance.edit') }}">
                            <span class="nav-link-icon"><i class="ti ti-palette"></i></span>
                            <span class="nav-link-title">Tampilan</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.menus.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-menu-2"></i></span>
                            <span class="nav-link-title">Menu</span>
                        </a>
                    </li>
                @endif

                <li class="nav-section">Sistem</li>
                <li class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                        <span class="nav-link-icon"><i class="ti ti-mail"></i></span>
                        <span class="nav-link-title">Pesan</span>
                        @if ($unread > 0)<span class="badge bg-red text-white ms-auto">{{ $unread }}</span>@endif
                    </a>
                </li>
                @if (auth()->user()->isAdmin())
                    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-user-shield"></i></span>
                            <span class="nav-link-title">Pengguna</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.media.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-photo"></i></span>
                            <span class="nav-link-title">Manajer Media</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.activity.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-history"></i></span>
                            <span class="nav-link-title">Log Aktivitas</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.settings.edit') }}">
                            <span class="nav-link-icon"><i class="ti ti-settings"></i></span>
                            <span class="nav-link-title">Pengaturan</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</aside>
