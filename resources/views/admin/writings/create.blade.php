@extends('layouts.admin')

@section('title', 'Tulis Baru')
@section('pretitle', 'Ruang Dosen')
@section('page-title', 'Tulis Baru')

@section('content')
    <form method="POST" action="{{ route('admin.writings.store') }}" enctype="multipart/form-data" class="writing-form">
        @csrf
        @include('admin.writings._form')
    </form>
@endsection
