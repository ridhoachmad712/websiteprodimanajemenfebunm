@extends('layouts.admin')

@section('title', 'Edit Dokumen')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Dokumen')

@section('content')
    <form method="POST" action="{{ route('admin.downloads.update', $download) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.downloads._form')
    </form>
@endsection
