@extends('layouts.guest')

@section('title', 'Konfirmasi Sandi')

@section('content')
    <form class="card card-md" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="card-body">
            <h2 class="h2 text-center mb-3">Konfirmasi kata sandi</h2>
            <p class="text-secondary mb-4">
                Ini area aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
            </p>

            <div class="mb-3">
                <label class="form-label">Kata sandi</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="current-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
            </div>
        </div>
    </form>
@endsection
