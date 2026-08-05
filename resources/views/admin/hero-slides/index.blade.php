@extends('layouts.admin')

@section('title', 'Hero Slider')
@section('pretitle', 'Tampilan Situs')
@section('page-title', 'Hero Slider Beranda')

@section('page-actions')
    <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Slide
    </a>
@endsection

@section('content')
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-1"></i>
        Slide aktif akan tampil sebagai <strong>carousel</strong> di bagian atas beranda. Bila belum ada slide, beranda memakai hero biasa (dari menu <strong>Tampilan</strong>).
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1">Gambar</th>
                        <th>Judul</th>
                        <th class="w-1">Urutan</th>
                        <th class="w-1">Status</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($slides as $s)
                        <tr>
                            <td>
                                <img src="{{ Storage::url($s->gambar) }}" alt="" style="width:120px;height:60px;object-fit:cover" class="rounded">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $s->judul ?: '(tanpa judul)' }}</div>
                                @if ($s->subjudul)<div class="text-secondary small text-truncate" style="max-width:360px">{{ $s->subjudul }}</div>@endif
                            </td>
                            <td class="text-secondary">{{ $s->urutan }}</td>
                            <td>
                                @if ($s->aktif)
                                    <span class="badge bg-green-lt">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-lt">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.hero-slides.edit', $s) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.hero-slides.destroy', $s) }}" onsubmit="return confirm('Hapus slide ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada slide.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
