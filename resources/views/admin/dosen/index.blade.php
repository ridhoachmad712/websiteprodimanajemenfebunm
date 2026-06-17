@extends('layouts.admin')

@section('title', 'Dosen')
@section('pretitle', 'Manajemen')
@section('page-title', 'Daftar Dosen')

@section('page-actions')
    <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Dosen
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="kategori" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua kategori</option>
                        @foreach ($kategori as $key => $label)
                            <option value="{{ $key }}" @selected(request('kategori') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama dosen…">
                </div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('kategori') || request('cari'))
                        <a href="{{ route('admin.dosen.index') }}" class="btn btn-link">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1">Foto</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Konsentrasi</th>
                        <th class="w-1">NIP</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dosen as $d)
                        <tr>
                            <td>
                                <span class="avatar"
                                      @if ($d->foto) style="background-image: url('{{ Storage::url($d->foto) }}')" @endif>
                                    @unless ($d->foto) {{ Str::of($d->nama)->substr(0, 1)->upper() }} @endunless
                                </span>
                            </td>
                            <td><div class="fw-bold">{{ $d->nama }}</div></td>
                            <td><span class="badge bg-blue-lt">{{ $kategori[$d->kategori] ?? $d->kategori }}</span></td>
                            <td class="text-secondary">{{ $d->konsentrasi ?: '—' }}</td>
                            <td class="text-secondary">{{ $d->nip ?: '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.dosen.edit', $d) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.dosen.destroy', $d) }}"
                                          onsubmit="return confirm('Hapus data {{ $d->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Belum ada data dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dosen->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $dosen->links() }}
            </div>
        @endif
    </div>
@endsection
