@extends('layouts.guest')

@section('title', 'Lupa Sandi')

@section('content')
    <form class="card card-md" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="card-body">
            <h2 class="h2 text-center mb-3">Lupa kata sandi</h2>
            <p class="text-secondary mb-4">
                Masukkan email Anda, kami akan mengirim tautan untuk mengatur ulang kata sandi.
            </p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@unm.ac.id" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Kirim tautan reset</button>
            </div>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
    </div>
@endsection
