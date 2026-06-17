@extends('layouts.admin')

@section('title', 'Edit Dosen')
@section('pretitle', 'Manajemen')
@section('page-title', 'Edit Dosen')

@section('content')
    <form method="POST" action="{{ route('admin.dosen.update', $dosen) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                @include('admin.dosen._form')
            </div>
        </div>
    </form>
@endsection
