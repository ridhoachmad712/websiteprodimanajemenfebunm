@extends('layouts.admin')

@section('title', 'Tulis Berita')
@section('pretitle', 'Manajemen')
@section('page-title', 'Tulis Berita')

@section('content')
    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.posts._form')
    </form>
@endsection
