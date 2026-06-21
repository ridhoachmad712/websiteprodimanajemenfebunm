@extends('layouts.admin')

@section('title', 'Dashboard')
@section('pretitle', 'Panel Admin')
@section('page-title', 'Selamat datang, '.\Illuminate\Support\Str::of(auth()->user()->name)->before(' '))

@section('page-actions')
    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary">
        <i class="ti ti-external-link me-1"></i> Lihat Situs
    </a>
@endsection

@section('content')
    {{-- Kartu statistik (bisa diklik) --}}
    <div class="row row-deck row-cards">
        @php
            $cards = [
                ['label' => 'Berita',  'value' => $stats['berita'],  'sub' => 'total post', 'icon' => 'ti-news',       'url' => route('admin.posts.index')],
                ['label' => 'Dosen',   'value' => $stats['dosen'],   'sub' => 'terdaftar',  'icon' => 'ti-users',      'url' => route('admin.dosen.index')],
                ['label' => 'Halaman', 'value' => $stats['halaman'], 'sub' => 'statis',     'icon' => 'ti-file-text',  'url' => route('admin.pages.index')],
                ['label' => 'Galeri',  'value' => $stats['galeri'],  'sub' => 'media',      'icon' => 'ti-photo',      'url' => route('admin.gallery.index')],
            ];
        @endphp
        @foreach ($cards as $c)
            <div class="col-sm-6 col-lg-3">
                <a href="{{ $c['url'] }}" class="card card-link-pop text-reset text-decoration-none h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="bg-primary-lt rounded p-2 me-3 d-inline-flex"><i class="ti {{ $c['icon'] }} fs-2"></i></span>
                            <div>
                                <div class="h1 mb-0 lh-1">{{ $c['value'] }}</div>
                                <div class="text-secondary small">{{ $c['label'] }} · {{ $c['sub'] }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row row-cards mt-1">
        {{-- Aksi cepat --}}
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header"><h3 class="card-title">Aksi Cepat</h3></div>
                <div class="card-body">
                    <div class="row g-2">
                        @php
                            $actions = [
                                ['Tulis Berita',   'ti-pencil-plus', route('admin.posts.create')],
                                ['Tambah Dosen',   'ti-user-plus',   route('admin.dosen.create')],
                                ['Susun Beranda',  'ti-layout-board', route('admin.home.edit')],
                                ['Ubah Tampilan',  'ti-palette',     route('admin.appearance.edit')],
                                ['Kelola Menu',    'ti-menu-2',      route('admin.menus.index')],
                                ['Pengaturan',     'ti-settings',    route('admin.settings.edit')],
                            ];
                        @endphp
                        @foreach ($actions as [$label, $icon, $url])
                            <div class="col-6 col-md-4">
                                <a href="{{ $url }}" class="btn btn-outline-primary w-100 d-flex flex-column py-3">
                                    <i class="ti {{ $icon }} fs-2 mb-1"></i>
                                    <span class="small">{{ $label }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Pesan masuk terbaru --}}
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Pesan Masuk</h3>
                    <div class="card-actions">
                        @if ($unread > 0)<span class="badge bg-red text-white me-2">{{ $unread }} baru</span>@endif
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-link">Semua</a>
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($recentMessages as $m)
                        <a href="{{ route('admin.contacts.show', $m) }}" class="list-group-item list-group-item-action {{ $m->dibaca ? '' : 'fw-bold' }}">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-primary-lt me-2">{{ \Illuminate\Support\Str::of($m->nama)->substr(0, 1)->upper() }}</span>
                                <div class="flex-fill text-truncate">
                                    <div class="text-truncate">{{ $m->subjek ?: 'Tanpa subjek' }}</div>
                                    <div class="text-secondary small text-truncate">{{ $m->nama }} · {{ $m->created_at?->diffForHumans() }}</div>
                                </div>
                                @unless ($m->dibaca)<span class="badge bg-red"></span>@endunless
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-secondary text-center py-4">Belum ada pesan masuk.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
