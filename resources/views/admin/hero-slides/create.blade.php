@extends('layouts.admin')

@section('title', 'Tambah Slide')
@section('pretitle', 'Tampilan Situs')
@section('page-title', 'Tambah Slide')

@section('content')
    <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.hero-slides._form')
    </form>
@endsection
