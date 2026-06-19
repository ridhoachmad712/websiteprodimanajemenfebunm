@extends('layouts.admin')

@section('title', 'Pesan Masuk')
@section('pretitle', 'Komunikasi')
@section('page-title', 'Pesan Masuk')

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1"></th>
                        <th>Pengirim</th>
                        <th>Subjek</th>
                        <th class="w-1">Tanggal</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $m)
                        <tr class="{{ $m->dibaca ? '' : 'fw-bold' }}">
                            <td>
                                @unless ($m->dibaca)<span class="badge bg-primary" title="Belum dibaca"></span>@endunless
                            </td>
                            <td>
                                <div>{{ $m->nama }}</div>
                                <div class="text-secondary small fw-normal">{{ $m->email }}</div>
                            </td>
                            <td class="fw-normal">{{ $m->subjek ?: Str::limit($m->pesan, 50) }}</td>
                            <td class="text-secondary fw-normal">{{ $m->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.contacts.show', $m) }}" class="btn btn-sm">Buka</a>
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $m) }}" onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada pesan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($messages->hasPages())
            <div class="card-footer d-flex">{{ $messages->links() }}</div>
        @endif
    </div>
@endsection
