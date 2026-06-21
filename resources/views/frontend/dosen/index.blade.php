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

                    <div class="row row-cards row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                        @foreach ($g['items'] as $d)
                            <div class="col" data-reveal>
                                <a href="{{ route('dosen.show', $d) }}" class="card card-hover dosen-card h-100 text-reset text-decoration-none">
                                    <div class="dosen-photo">
                                        @if ($d->foto)
                                            <img src="{{ Storage::url($d->foto) }}" alt="Foto {{ $d->nama }}" loading="lazy">
                                        @else
                                            <span class="ph">{{ Str::of($d->nama)->substr(0, 1)->upper() }}</span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3 class="dosen-name fw-bold mb-1">{{ $d->nama }}</h3>
                                        @if ($d->nip)
                                            <div class="text-secondary small mb-1">NIP. {{ $d->nip }}</div>
                                        @endif
                                        @if ($d->konsentrasi)
                                            @php($km = \App\Models\Dosen::konsentrasiMeta($d->konsentrasi))
                                            <span class="badge bg-{{ $km['color'] }}-lt dosen-konsentrasi">
                                                <i class="ti {{ $km['icon'] }} me-1"></i>{{ $d->konsentrasi }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
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
