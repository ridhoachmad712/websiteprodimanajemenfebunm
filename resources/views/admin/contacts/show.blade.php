@extends('layouts.admin')

@section('title', 'Detail Pesan')
@section('pretitle', 'Komunikasi')
@section('page-title', 'Detail Pesan')

@section('page-actions')
    <a href="{{ route('admin.contacts.index') }}" class="btn"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title mb-0">{{ $message->subjek ?: 'Tanpa subjek' }}</h3>
                        <div class="text-secondary small">{{ $message->created_at->translatedFormat('l, d F Y H:i') }}</div>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row mb-3">
                        <dt class="col-3 text-secondary">Nama</dt>
                        <dd class="col-9">{{ $message->nama }}</dd>
                        <dt class="col-3 text-secondary">Email</dt>
                        <dd class="col-9"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
                    </dl>
                    <hr>
                    <p style="white-space:pre-line">{{ $message->pesan }}</p>
                </div>
                <div class="card-footer d-flex">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subjek ?: 'Pesan Anda') }}" class="btn btn-primary">
                        <i class="ti ti-mail-forward me-1"></i> Balas via Email
                    </a>
                    <form method="POST" action="{{ route('admin.contacts.destroy', $message) }}" class="ms-auto" onsubmit="return confirm('Hapus pesan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
