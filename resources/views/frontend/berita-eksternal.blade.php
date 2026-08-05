@extends('layouts.frontend')

@section('title', 'Berita Eksternal')
@section('meta_description', 'Kumpulan pemberitaan tentang Program Studi Manajemen FEB UNM di media eksternal.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Berita Eksternal',
        'subtitle' => 'Pemberitaan dan liputan tentang Program Studi Manajemen FEB UNM di berbagai media.',
        'crumbs' => ['Beranda' => url('/'), 'Berita Eksternal' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="card">
                <div class="card-body border-bottom py-3">
                    <form method="GET" class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                <input type="search" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul atau nama media…">
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-primary">Cari</button>
                            @if (request('cari'))<a href="{{ route('berita-eksternal.index') }}" class="btn btn-link">Reset</a>@endif
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th class="w-1 text-nowrap">Tanggal</th>
                                <th>Judul Berita</th>
                                <th>Media</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($berita as $b)
                                <tr>
                                    <td class="text-secondary">{{ $berita->firstItem() + $loop->index }}</td>
                                    <td class="text-secondary text-nowrap">{{ $b->tanggal?->translatedFormat('d M Y') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ $b->url }}" target="_blank" rel="noopener" class="fw-semibold text-reset text-decoration-none">
                                            {{ $b->judul }}
                                        </a>
                                        @if ($b->ringkasan)
                                            <div class="text-secondary small excerpt-clamp">{{ $b->ringkasan }}</div>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-primary-lt">{{ $b->sumber }}</span></td>
                                    <td>
                                        <a href="{{ $b->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary text-nowrap">
                                            <i class="ti ti-external-link me-1"></i>Baca
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-5">
                                        <i class="ti ti-news-off fs-1 d-block mb-2"></i>
                                        Belum ada berita eksternal yang ditampilkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($berita->hasPages())
                    <div class="card-footer d-flex align-items-center">{{ $berita->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
