@extends('layouts.admin')

@section('title', 'Tambah Menu')
@section('pretitle', 'Manajemen')
@section('page-title', 'Tambah Menu')

@section('content')
    <form method="POST" action="{{ route('admin.menus.store') }}">
        @csrf
        @include('admin.menus._form')
    </form>
@endsection
