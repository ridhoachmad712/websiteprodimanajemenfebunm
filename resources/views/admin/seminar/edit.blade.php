@extends('layouts.admin')

@section('title', 'Edit Seminar')
@section('pretitle', 'Konten')
@section('page-title', 'Edit Seminar')

@section('content')
    <form method="POST" action="{{ route('admin.seminar.update', $seminar) }}">
        @csrf
        @method('PUT')
        @include('admin.seminar._form')
    </form>
@endsection
