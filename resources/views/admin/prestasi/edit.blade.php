@extends('layouts.admin')

@section('title', 'Edit Prestasi')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Prestasi')

@section('content')
    <form method="POST" action="{{ route('admin.prestasi.update', $prestasi) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.prestasi._form')
    </form>
@endsection
