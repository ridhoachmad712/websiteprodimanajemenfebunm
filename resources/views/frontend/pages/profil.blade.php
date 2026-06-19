@extends('layouts.frontend')

@section('title', $page->title)
@section('meta_description', 'Profil Program Studi Manajemen FEB UNM: sambutan, sejarah, visi, misi, tujuan, strategi, dan kompetensi lulusan.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'  => $page->title,
        'crumbs' => ['Beranda' => url('/'), $page->title => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-4">
                {{-- Anchor navigation --}}
                <aside class="col-lg-3 d-none d-lg-block">
                    <div class="card sticky-top" style="top:5rem">
                        <div class="list-group list-group-flush">
                            @foreach ($page->sections as $key => $section)
                                <a href="#{{ $key }}" class="list-group-item list-group-item-action">{{ $section['judul'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <div class="col-lg-9">
                    @foreach ($page->sections as $key => $section)
                        <section id="{{ $key }}" class="mb-5" style="scroll-margin-top:5rem">
                            <h2 class="h2 border-bottom pb-2 mb-3">{{ $section['judul'] }}</h2>
                            @if (!empty($section['isi']))
                                <div class="markdown">{!! $section['isi'] !!}</div>
                            @else
                                <p class="text-secondary fst-italic">Konten section ini belum diisi. Silakan lengkapi melalui panel admin.</p>
                            @endif
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
