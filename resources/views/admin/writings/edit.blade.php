@extends('layouts.admin')

@section('title', 'Edit Tulisan')
@section('pretitle', 'Ruang Dosen')
@section('page-title', 'Edit Tulisan')

@section('content')
    <form method="POST" action="{{ route('admin.writings.update', $post) }}" enctype="multipart/form-data" class="writing-form">
        @csrf @method('PUT')
        @include('admin.writings._form')
    </form>
@endsection
