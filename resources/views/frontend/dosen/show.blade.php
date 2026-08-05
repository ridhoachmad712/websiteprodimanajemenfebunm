@extends('layouts.frontend')

@section('title', $dosen->nama)
@section('meta_description', $dosen->nama.' — '.$dosen->kategori_label.($dosen->konsentrasi ? ', '.$dosen->konsentrasi : '').'.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => $dosen->nama,
        'subtitle' => $dosen->kategori_label.($dosen->konsentrasi ? ' · '.$dosen->konsentrasi : ''),
        'crumbs'   => ['Beranda' => url('/'), 'Daftar Dosen' => route('dosen.index'), $dosen->nama => null],
    ])

    @php($km = $dosen->konsentrasi ? \App\Models\Dosen::konsentrasiMeta($dosen->konsentrasi) : null)
    @php($profil = \App\Models\Dosen::profilLinks())
    @php($adaProfil = collect($profil)->keys()->first(fn ($f) => filled($dosen->$f)) !== null)

    <section class="section">
        <div class="container-xl">
            <div class="row g-4">
                {{-- ===== Kartu profil (kiri, sticky) ===== --}}
                <div class="col-lg-4">
                    <div class="card sticky-lg-top dosen-profile-sticky">
                        <div class="dosen-photo" style="aspect-ratio:1/1;border-radius:var(--tblr-border-radius) var(--tblr-border-radius) 0 0;overflow:hidden">
                            @if ($dosen->foto)
                                <img src="{{ Storage::url($dosen->foto) }}" alt="Foto {{ $dosen->nama }}">
                            @else
                                <span class="ph">{{ Str::of($dosen->nama)->substr(0, 1)->upper() }}</span>
                            @endif
                        </div>
                        <div class="card-body text-center">
                            <h2 class="h3 mb-2">{{ $dosen->nama }}</h2>
                            @if ($dosen->jabatan)
                                <div class="mb-2"><span class="badge bg-primary"><i class="ti ti-briefcase me-1"></i>{{ $dosen->jabatan }}</span></div>
                            @endif
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                <span class="badge bg-primary-lt">{{ $dosen->kategori_label }}</span>
                                @if ($km)
                                    <span class="badge bg-{{ $km['color'] }}-lt"><i class="ti {{ $km['icon'] }} me-1"></i>{{ $dosen->konsentrasi }}</span>
                                @endif
                            </div>
                        </div>

                        @if ($adaProfil || $dosen->bio_link || !empty($dosen->tautan))
                            <div class="card-body border-top">
                                <div class="d-grid gap-2">
                                    @foreach ($profil as $field => $meta)
                                        @if ($dosen->$field)
                                            <a href="{{ $dosen->$field }}" target="_blank" rel="noopener" class="btn btn-{{ $meta['color'] }}">
                                                <i class="ti {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                    @foreach ($dosen->tautan ?? [] as $t)
                                        <a href="{{ $t['url'] }}" target="_blank" rel="noopener" class="btn btn-{{ $t['color'] ?? 'primary' }}">
                                            @if (!empty($t['icon']))<i class="ti {{ $t['icon'] }} me-1"></i>@endif{{ $t['label'] }}
                                        </a>
                                    @endforeach
                                    @if ($dosen->bio_link)
                                        <a href="{{ $dosen->bio_link }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
                                            <i class="ti ti-external-link me-1"></i> Profil Lengkap
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== Detail (kanan) ===== --}}
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header"><h2 class="card-title">Informasi</h2></div>
                        <div class="card-body">
                            <div class="datagrid">
                                @if ($dosen->jabatan)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Jabatan</div>
                                        <div class="datagrid-content">{{ $dosen->jabatan }}</div>
                                    </div>
                                @endif
                                <div class="datagrid-item">
                                    <div class="datagrid-title">NIP</div>
                                    <div class="datagrid-content">{{ $dosen->nip ?: '—' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Kategori</div>
                                    <div class="datagrid-content">{{ $dosen->kategori_label }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Konsentrasi</div>
                                    <div class="datagrid-content">{{ $dosen->konsentrasi ?: '—' }}</div>
                                </div>
                                @if ($dosen->kepakaran)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Kepakaran</div>
                                        <div class="datagrid-content">
                                            @foreach (array_filter(array_map('trim', explode(',', $dosen->kepakaran))) as $k)
                                                <span class="badge bg-blue-lt me-1 mb-1">{{ $k }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($dosen->biografi)
                        <div class="card">
                            <div class="card-header"><h2 class="card-title">Biografi</h2></div>
                            <div class="card-body">
                                <div class="markdown">{!! $dosen->biografi !!}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('dosen.index') }}" class="btn btn-link"><i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Dosen</a>
            </div>
        </div>
    </section>
@endsection
