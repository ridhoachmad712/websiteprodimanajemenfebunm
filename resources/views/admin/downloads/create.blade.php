@extends('layouts.admin')

@section('title', 'Tambah Dokumen')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Dokumen')

@section('content')
    <form method="POST" action="{{ route('admin.downloads.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.downloads._form')
    </form>
@endsection
