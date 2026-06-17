@extends('layouts.admin')

@section('title', 'Tambah Dosen')
@section('pretitle', 'Manajemen')
@section('page-title', 'Tambah Dosen')

@section('content')
    <form method="POST" action="{{ route('admin.dosen.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                @include('admin.dosen._form')
            </div>
        </div>
    </form>
@endsection
