@extends('layouts.admin')

@section('title', 'Menu')
@section('pretitle', 'Manajemen')
@section('page-title', 'Menu Builder')

@section('page-actions')
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Menu
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p class="text-secondary">Struktur menu navigasi publik (hingga 3 level). Atur posisi lewat kolom urutan tiap item.</p>

            <div class="list-group list-group-flush">
                @forelse ($menus as $menu)
                    @include('admin.menus._row', ['item' => $menu, 'level' => 0])
                @empty
                    <div class="text-secondary py-3">Belum ada menu.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
