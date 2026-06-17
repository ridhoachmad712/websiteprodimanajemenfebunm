@extends('layouts.guest')

@section('title', 'Atur Ulang Sandi')

@section('content')
    <form class="card card-md" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Atur ulang kata sandi</h2>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus autocomplete="username">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kata sandi baru</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="new-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi kata sandi</label>
                <input type="password" name="password_confirmation"
                       class="form-control" required autocomplete="new-password">
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Simpan kata sandi baru</button>
            </div>
        </div>
    </form>
@endsection
