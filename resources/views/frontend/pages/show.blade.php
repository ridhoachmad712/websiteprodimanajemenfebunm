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
                <div class="row g-4 mt-1">
                    {{-- Info kontak --}}
                    <div class="col-lg-5">
                        <div class="d-flex mb-3">
                            <span class="feature-icon me-3"><i class="ti ti-map-pin"></i></span>
                            <div><div class="fw-bold">Alamat</div><div class="text-secondary">{{ \App\Models\Setting::get('kontak.alamat') }}</div></div>
                        </div>
                        <div class="d-flex mb-3">
                            <span class="feature-icon me-3"><i class="ti ti-phone"></i></span>
                            <div><div class="fw-bold">Telepon</div><div class="text-secondary">{{ \App\Models\Setting::get('kontak.telepon') }}</div></div>
                        </div>
                        <div class="d-flex mb-3">
                            <span class="feature-icon me-3"><i class="ti ti-mail"></i></span>
                            <div><div class="fw-bold">Email</div><div class="text-secondary">{{ \App\Models\Setting::get('kontak.email') }}</div></div>
                        </div>
                    </div>

                    {{-- Form kontak --}}
                    <div class="col-lg-7">
                        <div class="card card-hover">
                            <div class="card-body">
                                <h2 class="h3 mb-3">Kirim Pesan</h2>

                                @if (session('contact_success'))
                                    <div class="alert alert-success">{{ session('contact_success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                                @endif

                                <form method="POST" action="{{ route('contact.store') }}">
                                    @csrf
                                    {{-- Honeypot (disembunyikan dari manusia) --}}
                                    <div style="position:absolute;left:-9999px" aria-hidden="true">
                                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label required">Nama</label>
                                            <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label required">Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subjek</label>
                                        <input type="text" name="subjek" value="{{ old('subjek') }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Pesan</label>
                                        <textarea name="pesan" rows="5" class="form-control" required>{{ old('pesan') }}</textarea>
                                    </div>
                                    <button class="btn btn-primary"><i class="ti ti-send me-1"></i> Kirim Pesan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
