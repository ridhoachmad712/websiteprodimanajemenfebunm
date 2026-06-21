@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Pengumuman')

@section('content')
    <form method="POST" action="{{ route('admin.pengumuman.update', $post) }}">
        @csrf @method('PUT')
        @include('admin.pengumuman._form')
    </form>
@endsection
