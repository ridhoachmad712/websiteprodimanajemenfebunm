@extends('layouts.admin')

@section('title', 'Edit Berita')
@section('pretitle', 'Manajemen')
@section('page-title', 'Edit Berita')

@section('content')
    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.posts._form')
    </form>
@endsection
