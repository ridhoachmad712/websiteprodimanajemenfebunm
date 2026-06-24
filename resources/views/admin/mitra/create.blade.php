@extends('layouts.admin')

@section('title', 'Tambah Mitra')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Mitra')

@section('content')
    <form method="POST" action="{{ route('admin.mitra.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.mitra._form')
    </form>
@endsection
