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
    @php $isAdmin = auth()->user()->isAdmin(); @endphp

    {{-- Kartu statistik utama (bisa diklik) --}}
    <div class="row row-deck row-cards">
        @php
            $cards = [
                ['label' => 'Berita',     'value' => $stats['berita'],     'icon' => 'ti-news',         'url' => route('admin.posts.index')],
                ['label' => 'Pengumuman', 'value' => $stats['pengumuman'], 'icon' => 'ti-speakerphone', 'url' => route('admin.pengumuman.index')],
                ['label' => 'Dosen',      'value' => $stats['dosen'],      'icon' => 'ti-users',        'url' => route('admin.dosen.index')],
                ['label' => 'Prestasi',   'value' => $stats['prestasi'],   'icon' => 'ti-trophy',       'url' => route('admin.prestasi.index')],
            ];
        @endphp
        @foreach ($cards as $c)
            <div class="col-sm-6 col-lg-3">
                <a href="{{ $c['url'] }}" class="card card-link-pop text-reset text-decoration-none h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="bg-primary-lt p-2 me-3 d-inline-flex"><i class="ti {{ $c['icon'] }} fs-2"></i></span>
                            <div>
                                <div class="h1 mb-0 lh-1">{{ $c['value'] }}</div>
                                <div class="text-secondary small">{{ $c['label'] }}</div>
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
                    <div class="row g-2 admin-quick-actions">
                        @php
                            $actions = [
                                ['Tulis Berita',      'ti-pencil-plus',  route('admin.posts.create'),      false],
                                ['Tambah Pengumuman', 'ti-speakerphone', route('admin.pengumuman.create'), false],
                                ['Tambah Dosen',      'ti-user-plus',    route('admin.dosen.create'),      false],
                                ['Tambah Prestasi',   'ti-trophy',       route('admin.prestasi.create'),   false],
                                ['Susun Beranda',     'ti-layout-board', route('admin.home.edit'),         true],
                                ['Ubah Tampilan',     'ti-palette',      route('admin.appearance.edit'),   true],
                                ['Kelola Menu',       'ti-menu-2',       route('admin.menus.index'),       true],
                                ['Pengaturan',        'ti-settings',     route('admin.settings.edit'),     true],
                            ];
                        @endphp
                        @foreach ($actions as [$label, $icon, $url, $adminOnly])
                            @if (! $adminOnly || $isAdmin)
                                <div class="col-12 col-sm-6">
                                    <a href="{{ $url }}" class="btn btn-outline-primary w-100 d-flex align-items-center text-start">
                                        <i class="ti {{ $icon }} fs-2 me-2" aria-hidden="true"></i>
                                        <span>{{ $label }}</span>
                                    </a>
                                </div>
                            @endif
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

    <div class="row row-cards mt-1">
        {{-- Berita terpopuler --}}
        <div class="{{ $isAdmin ? 'col-lg-6' : 'col-lg-12' }}">
            <div class="card h-100">
                <div class="card-header"><h3 class="card-title"><i class="ti ti-flame me-2 text-orange"></i>Berita Terpopuler</h3></div>
                <div class="list-group list-group-flush">
                    @forelse ($popular as $p)
                        <a href="{{ $p->url() }}" target="_blank" rel="noopener" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="flex-fill text-truncate pe-2">{{ $p->judul }}</div>
                            <span class="badge bg-orange-lt flex-shrink-0"><i class="ti ti-eye me-1"></i>{{ number_format($p->dilihat, 0, ',', '.') }}</span>
                        </a>
                    @empty
                        <div class="list-group-item text-secondary text-center py-4">Belum ada data kunjungan berita.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Aktivitas terbaru (admin) --}}
        @if ($isAdmin)
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-history me-2"></i>Aktivitas Terbaru</h3>
                        <div class="card-actions"><a href="{{ route('admin.activity.index') }}" class="btn btn-sm btn-link">Semua</a></div>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse ($recentActivity as $log)
                            @php $meta = $log->actionMeta(); @endphp
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-{{ $meta['color'] }}-lt me-2 flex-shrink-0"><i class="ti {{ $meta['icon'] }}"></i></span>
                                    <div class="flex-fill text-truncate">
                                        <span class="text-secondary">{{ $log->user_name ?: 'Sistem' }}</span>
                                        {{ \Illuminate\Support\Str::lower($meta['label']) }}
                                        <span class="text-secondary">{{ $log->subjectLabelType() }}:</span>
                                        <span class="fw-bold">{{ $log->subject_label }}</span>
                                    </div>
                                    <span class="text-secondary small flex-shrink-0 ms-2">{{ $log->created_at?->diffForHumans(short: true) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-secondary text-center py-4">Belum ada aktivitas.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Ringkasan seluruh modul konten --}}
    <section class="admin-summary mt-4" aria-labelledby="admin-summary-title">
        <h3 class="admin-summary-title" id="admin-summary-title">Ringkasan Konten</h3>
            <div class="row g-2">
                @php
                    $ringkasan = [
                        ['Seminar', $stats['seminar'], 'ti-presentation', route('admin.seminar.index')],
                        ['Dokumen', $stats['dokumen'], 'ti-download', route('admin.downloads.index')],
                        ['Mitra', $stats['mitra'], 'ti-heart-handshake', route('admin.mitra.index')],
                        ['Kegiatan', $stats['kegiatan'], 'ti-calendar-event', route('admin.kegiatan.index')],
                        ['Galeri', $stats['galeri'], 'ti-photo', route('admin.gallery.index')],
                        ['Halaman', $stats['halaman'], 'ti-file-text', route('admin.pages.index')],
                    ];
                @endphp
                @foreach ($ringkasan as [$label, $value, $icon, $url])
                    <div class="col-4 col-md-2">
                        <a href="{{ $url }}" class="d-block text-reset text-decoration-none border p-2 text-center card-link-pop admin-summary-link">
                            <i class="ti {{ $icon }} fs-2 text-secondary"></i>
                            <div class="h3 mb-0">{{ $value }}</div>
                            <div class="text-secondary small">{{ $label }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
    </section>
@endsection
