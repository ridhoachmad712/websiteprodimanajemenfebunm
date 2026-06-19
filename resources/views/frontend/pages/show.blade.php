@extends('layouts.frontend')

@section('title', $page->title)
@section('meta_description', Str::limit(strip_tags($page->content), 155))

@section('content')
    @include('frontend.partials.page-hero', [
        'title'  => $page->title,
        'crumbs' => ['Beranda' => url('/'), $page->title => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-9">
                    <div class="markdown">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>

            @if ($page->slug === 'hubungi-kami')
                <div class="row mt-4">
                    <div class="col-lg-9">
                        <div class="row row-cards">
                            <div class="col-md-4">
                                <div class="card card-sm"><div class="card-body">
                                    <span class="avatar bg-primary-lt mb-2"><i class="ti ti-map-pin"></i></span>
                                    <div class="text-secondary">{{ \App\Models\Setting::get('kontak.alamat') }}</div>
                                </div></div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-sm"><div class="card-body">
                                    <span class="avatar bg-primary-lt mb-2"><i class="ti ti-phone"></i></span>
                                    <div class="text-secondary">{{ \App\Models\Setting::get('kontak.telepon') }}</div>
                                </div></div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-sm"><div class="card-body">
                                    <span class="avatar bg-primary-lt mb-2"><i class="ti ti-mail"></i></span>
                                    <div class="text-secondary">{{ \App\Models\Setting::get('kontak.email') }}</div>
                                </div></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
