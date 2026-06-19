@extends('layouts.frontend')

@section('title', 'Daftar Dosen')
@section('meta_description', 'Daftar dosen Program Studi Manajemen FEB UNM, dikelompokkan menurut Guru Besar, Dosen Tetap, MKDU, dan Dosen Luar Biasa.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Dosen & Tenaga Pengajar',
        'subtitle' => $total.' dosen di lingkungan Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Daftar Dosen' => null],
    ])

    <section class="section">
        <div class="container-xl">
            {{-- Filter kategori --}}
            @if ($grup->count() > 1)
                <div class="dosen-filter d-flex flex-wrap gap-2 mb-4" id="dosenFilter">
                    <button type="button" class="btn btn-sm btn-primary" data-filter="all">Semua</button>
                    @foreach ($grup as $key => $g)
                        <button type="button" class="btn btn-sm btn-outline-primary" data-filter="{{ $key }}">{{ $g['label'] }}</button>
                    @endforeach
                </div>
            @endif

            @forelse ($grup as $key => $g)
                <div class="dosen-group mb-5" data-group="{{ $key }}">
                    <div class="section-header mb-3">
                        <span class="eyebrow">{{ $g['items']->count() }} Orang</span>
                        <h2 class="section-title mb-0" style="font-size:1.5rem">{{ $g['label'] }}</h2>
                    </div>

                    <div class="row row-cards">
                        @foreach ($g['items'] as $d)
                            <div class="col-sm-6 col-lg-4 col-xl-3" data-reveal>
                                <div class="card card-hover dosen-card h-100">
                                    <div class="card-body text-center">
                                        @if ($d->foto)
                                            <span class="avatar avatar-xl mb-3" style="background-image: url('{{ Storage::url($d->foto) }}')"></span>
                                        @else
                                            <span class="avatar avatar-xl mb-3" style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy));color:#fff">{{ Str::of($d->nama)->substr(0, 1)->upper() }}</span>
                                        @endif
                                        <div class="fw-bold">
                                            <a href="{{ route('dosen.show', $d) }}" class="text-reset text-decoration-none stretched-link">{{ $d->nama }}</a>
                                        </div>
                                        @if ($d->konsentrasi)
                                            <div class="text-secondary small mt-1">{{ $d->konsentrasi }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty">
                    <p class="empty-title">Belum ada data dosen</p>
                    <p class="empty-subtitle text-secondary">Data dosen akan tampil di sini setelah ditambahkan melalui panel admin.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        var bar = document.getElementById('dosenFilter');
        if (!bar) return;
        var groups = Array.prototype.slice.call(document.querySelectorAll('.dosen-group'));
        bar.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-filter]');
            if (!btn) return;
            var f = btn.getAttribute('data-filter');
            // status tombol
            bar.querySelectorAll('[data-filter]').forEach(function (b) {
                b.classList.toggle('btn-primary', b === btn);
                b.classList.toggle('btn-outline-primary', b !== btn);
            });
            // tampil/sembunyi grup
            groups.forEach(function (g) {
                g.style.display = (f === 'all' || g.getAttribute('data-group') === f) ? '' : 'none';
            });
        });
    })();
</script>
@endpush
