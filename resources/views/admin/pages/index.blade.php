@extends('layouts.admin')

@section('title', 'Halaman')
@section('pretitle', 'Manajemen')
@section('page-title', 'Halaman Statis')

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Slug</th>
                        <th class="w-1">Tipe</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td class="fw-bold">{{ $page->title }}</td>
                            <td><code>/{{ $page->slug }}</code></td>
                            <td>
                                @if ($page->sections)
                                    <span class="badge bg-purple-lt">Ber-section</span>
                                @else
                                    <span class="badge bg-blue-lt">Konten tunggal</span>
                                @endif
                            </td>
                            <td><a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
