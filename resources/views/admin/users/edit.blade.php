@extends('layouts.admin')

@section('title', 'Ubah Pengguna')
@section('pretitle', 'Manajemen')
@section('page-title', 'Ubah Pengguna')

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-body">
                @include('admin.users._form')
            </div>
        </div>
    </form>
@endsection
