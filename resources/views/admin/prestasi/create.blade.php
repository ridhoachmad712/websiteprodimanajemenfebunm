@extends('layouts.admin')

@section('title', 'Tambah Prestasi')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Prestasi')

@section('content')
    <form method="POST" action="{{ route('admin.prestasi.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.prestasi._form')
    </form>
@endsection
