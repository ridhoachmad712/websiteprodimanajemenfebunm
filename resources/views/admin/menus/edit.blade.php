@extends('layouts.admin')

@section('title', 'Edit Menu')
@section('pretitle', 'Manajemen')
@section('page-title', 'Edit Menu')

@section('content')
    <form method="POST" action="{{ route('admin.menus.update', $menu) }}">
        @csrf
        @method('PUT')
        @include('admin.menus._form')
    </form>
@endsection
