@extends('layouts.admin')

@section('title', 'Pengguna')
@section('pretitle', 'Manajemen')
@section('page-title', 'Daftar Pengguna')

@section('page-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Pengguna
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col">
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama atau email…">
                </div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('cari'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-link">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1"></th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="w-1">Dibuat</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td>
                                <span class="avatar avatar-sm">{{ \Illuminate\Support\Str::of($u->name)->substr(0, 1)->upper() }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    {{ $u->name }}
                                    @if ($u->id === auth()->id())
                                        <span class="badge bg-blue-lt ms-1">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-secondary">{{ $u->email }}</td>
                            <td class="text-secondary">{{ $u->created_at?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm">Edit</a>
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                              onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
