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

                    @if ($g['subgroups'])
                        {{-- Dikelompokkan lagi menurut konsentrasi --}}
                        @foreach ($g['subgroups'] as $konsentrasi => $items)
                            @php($km = \App\Models\Dosen::konsentrasiMeta($konsentrasi))
                            <div class="mb-4">
                                <h3 class="h4 d-flex align-items-center gap-2 mb-3 text-{{ $km['color'] }}">
                                    <i class="ti {{ $km['icon'] }}"></i>{{ $konsentrasi }}
                                    <span class="badge bg-{{ $km['color'] }}-lt ms-1">{{ $items->count() }}</span>
                                </h3>
                                @include('frontend.partials.dosen-grid', ['items' => $items])
                            </div>
                        @endforeach
                    @else
                        @include('frontend.partials.dosen-grid', ['items' => $g['items']])
                    @endif
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
