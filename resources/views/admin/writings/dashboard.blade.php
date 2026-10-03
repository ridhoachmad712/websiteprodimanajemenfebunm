@extends('layouts.admin')

@section('title', 'Ruang Dosen')
@section('pretitle', 'Ruang Dosen')
@section('page-title', 'Selamat datang, '.auth()->user()->name)

@section('page-actions')
    <a href="{{ route('admin.writings.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1" aria-hidden="true"></i>Tulis Baru</a>
@endsection

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-4"><div class="card"><div class="card-body"><div class="h2 mb-1">{{ $drafts }}</div><div class="text-secondary">Draft</div></div></div></div>
        <div class="col-sm-4"><div class="card"><div class="card-body"><div class="h2 mb-1">{{ $submitted }}</div><div class="text-secondary">Menunggu tinjauan</div></div></div></div>
        <div class="col-sm-4"><div class="card"><div class="card-body"><div class="h2 mb-1">{{ $published }}</div><div class="text-secondary">Terbit</div></div></div></div>
    </div>
    <a href="{{ route('admin.writings.index') }}" class="btn btn-outline-primary">Lihat tulisan saya</a>
@endsection
