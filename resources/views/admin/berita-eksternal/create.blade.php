@extends('layouts.admin')

@section('title', 'Tambah Berita Eksternal')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Berita Eksternal')

@section('content')
    <form method="POST" action="{{ route('admin.berita-eksternal.store') }}">
        @csrf
        @include('admin.berita-eksternal._form')
    </form>
@endsection
