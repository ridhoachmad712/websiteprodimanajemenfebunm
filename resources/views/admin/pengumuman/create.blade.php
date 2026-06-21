@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Pengumuman')

@section('content')
    <form method="POST" action="{{ route('admin.pengumuman.store') }}">
        @csrf
        @include('admin.pengumuman._form')
    </form>
@endsection
