@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('pretitle', 'Manajemen')
@section('page-title', 'Pengaturan Situs')

@php
    $labelGrup = ['umum' => 'Umum', 'kontak' => 'Kontak', 'sosmed' => 'Media Sosial', 'statistik' => 'Statistik Beranda', 'jadwal' => 'Jadwal Ujian'];
@endphp

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row row-cards">
            @foreach ($groups as $group => $items)
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header"><h3 class="card-title">{{ $labelGrup[$group] ?? ucfirst($group) }}</h3></div>
                        <div class="card-body">
                            @foreach ($items as $key => $meta)
                                @php($name = str_replace('.', '_', $key))
                                @php($val = old($name, $values[$key] ?? ''))
                                <div class="mb-3">
                                    <label class="form-label">{{ $meta['label'] }}</label>
                                    @if (($meta['type'] ?? 'text') === 'textarea')
                                        <textarea name="{{ $name }}" rows="2" class="form-control">{{ $val }}</textarea>
                                    @else
                                        <input type="text" name="{{ $name }}" value="{{ $val }}" class="form-control">
                                    @endif
                                    @if (!empty($meta['hint']))
                                        <small class="form-hint">{{ $meta['hint'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            <button class="btn btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
@endsection
