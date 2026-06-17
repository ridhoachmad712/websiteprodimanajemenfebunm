@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <form class="card card-md" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Buat akun admin</h2>

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Nama lengkap" required autofocus autocomplete="name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@unm.ac.id" required autocomplete="username">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kata sandi</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 8 karakter" required autocomplete="new-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi kata sandi</label>
                <input type="password" name="password_confirmation"
                       class="form-control" placeholder="Ulangi kata sandi" required autocomplete="new-password">
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </div>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </div>
@endsection
