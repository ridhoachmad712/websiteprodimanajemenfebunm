@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-3">Verifikasi email Anda</h2>
            <p class="text-secondary mb-4">
                Terima kasih telah mendaftar. Silakan verifikasi email Anda dengan mengklik
                tautan yang baru saja kami kirim. Jika belum menerima, kami dapat mengirim ulang.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    Tautan verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Kirim ulang verifikasi</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-secondary">Keluar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
