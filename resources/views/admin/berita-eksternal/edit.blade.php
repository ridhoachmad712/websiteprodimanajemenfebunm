@extends('layouts.admin')

@section('title', 'Edit Berita Eksternal')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Berita Eksternal')

@section('content')
    <form method="POST" action="{{ route('admin.berita-eksternal.update', $berita) }}">
        @csrf
        @method('PUT')
        @include('admin.berita-eksternal._form')
    </form>
@endsection
