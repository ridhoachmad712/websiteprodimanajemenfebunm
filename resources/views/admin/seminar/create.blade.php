@extends('layouts.admin')

@section('title', 'Tambah Seminar')
@section('pretitle', 'Konten')
@section('page-title', 'Tambah Seminar')

@section('content')
    <form method="POST" action="{{ route('admin.seminar.store') }}">
        @csrf
        @include('admin.seminar._form')
    </form>
@endsection
