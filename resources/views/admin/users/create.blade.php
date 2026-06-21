@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('pretitle', 'Manajemen')
@section('page-title', 'Tambah Pengguna')

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                @include('admin.users._form')
            </div>
        </div>
    </form>
@endsection
