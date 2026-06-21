@extends('layouts.admin')

@section('title', 'Edit Kegiatan')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Kegiatan')

@section('content')
    <form method="POST" action="{{ route('admin.kegiatan.update', $kegiatan) }}">
        @csrf @method('PUT')
        @include('admin.kegiatan._form')
    </form>
@endsection
