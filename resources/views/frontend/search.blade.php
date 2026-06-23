@extends('layouts.frontend')

@section('title', $q !== '' ? 'Pencarian: '.$q : 'Pencarian')
@section('meta_description', 'Hasil pencarian di situs Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Pencarian',
        'subtitle' => $q !== '' ? 'Hasil untuk "'.$q.'"' : 'Cari berita, dosen, halaman, dan prestasi.',
        'crumbs' => ['Beranda' => url('/'), 'Pencarian' => null],
    ])

    <section class="section">
        <div class="container-xl">
            {{-- Kotak pencarian besar --}}
            <form action="{{ route('search.index') }}" method="GET" class="mb-4" style="max-width:640px">
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                    <input type="search" name="q" value="{{ $q }}" class="form-control form-control-lg" placeholder="Ketik kata kunci…" autofocus>
                </div>
            </form>

            @if ($q === '' || mb_strlen($q) < 2)
                <p class="text-secondary">Masukkan minimal 2 karakter untuk mencari.</p>
            @elseif ($total === 0)
                <div class="empty">
                    <div class="empty-icon"><i class="ti ti-search-off fs-1"></i></div>
                    <p class="empty-title">Tidak ada hasil untuk "{{ $q }}"</p>
                    <p class="empty-subtitle text-secondary">Coba kata kunci lain yang lebih umum.</p>
                </div>
            @else
                <p class="text-secondary mb-4">Ditemukan <strong>{{ $total }}</strong> hasil untuk "<strong>{{ $q }}</strong>".</p>
                @foreach ($groups as $label => $items)
                    <div class="mb-4">
                        <h2 class="h3 mb-3">{{ $label }} <span class="badge bg-primary-lt">{{ $items->count() }}</span></h2>
                        <div class="list-group">
                            @foreach ($items as $item)
                                <a href="{{ $item['url'] }}" class="list-group-item list-group-item-action">
                                    <div class="fw-bold">{{ $item['title'] }}</div>
                                    @if (! empty($item['snippet']))<div class="text-secondary small">{{ $item['snippet'] }}</div>@endif
                                    @if (! empty($item['meta']))<div class="text-muted small mt-1">{{ $item['meta'] }}</div>@endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
@endsection
