@extends('layouts.admin')

@section('title', 'Tambah Kegiatan')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Kegiatan')

@section('content')
    <form method="POST" action="{{ route('admin.kegiatan.store') }}">
        @csrf
        @include('admin.kegiatan._form')
    </form>
@endsection
