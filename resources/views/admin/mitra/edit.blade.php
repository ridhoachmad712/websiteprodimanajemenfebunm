@extends('layouts.admin')

@section('title', 'Edit Mitra')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Mitra')

@section('content')
    <form method="POST" action="{{ route('admin.mitra.update', $mitra) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.mitra._form')
    </form>
@endsection
