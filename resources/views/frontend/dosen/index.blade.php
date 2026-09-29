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
        <div class="container-xl" data-local-collection>
            @include('frontend.partials.collection-filter', ['searchLabel' => 'Cari nama atau konsentrasi', 'filterCategories' => $grup->map(fn ($g) => $g['label'])])

            @forelse ($grup as $key => $g)
                <div class="dosen-group mb-5" data-group="{{ $key }}" data-collection-group>
                    <div class="section-header mb-3">
                        <span class="eyebrow">{{ $g['items']->count() }} Orang</span>
                        <h2 class="section-title mb-0" style="font-size:1.5rem">{{ $g['label'] }}</h2>
                    </div>

                    @if ($g['subgroups'])
                        {{-- Dikelompokkan lagi menurut konsentrasi --}}
                        @foreach ($g['subgroups'] as $konsentrasi => $items)
                            @php($km = \App\Models\Dosen::konsentrasiMeta($konsentrasi))
                            <div class="mb-4" data-collection-group>
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
