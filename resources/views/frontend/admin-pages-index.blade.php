@extends('layouts.admin')

@section('title', 'Halaman')
@section('pretitle', 'Manajemen')
@section('page-title', 'Halaman')

@section('page-actions')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i>Tambah Halaman
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>URL</th>
                        <th class="w-1">Tipe</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        @php($publicUrl = in_array($page->slug, ['profil'], true) ? route('page.profil') : (in_array($page->slug, ['akreditasi', 'fasilitas', 'sop-petaprosesbisnis', 'kalender-akademik', 'kurikulum', 'hima', 'alumni', 'icoman2025', 'hubungi-kami'], true) ? route('page.'.$page->slug) : route('page.custom', $page)))
                        <tr>
                            <td class="fw-bold">{{ $page->title }}</td>
                            <td><code>{{ parse_url($publicUrl, PHP_URL_PATH) }}</code></td>
                            <td>
                                @php($template = $page->sections['_template'] ?? ($page->sections ? 'sections' : 'content'))
                                @php($labels = ['content' => 'Konten tunggal', 'sections' => 'Ber-section', 'landing' => 'Landing Page', 'documents' => 'Dokumen', 'faq' => 'FAQ', 'timeline' => 'Timeline', 'embed' => 'Embed'])
                                <span class="badge {{ $template === 'content' ? 'bg-blue-lt' : 'bg-purple-lt' }}">{{ $labels[$template] ?? Str::headline($template) }}</span>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ $publicUrl }}" target="_blank" class="btn btn-sm btn-icon" title="Lihat"><i class="ti ti-external-link"></i></a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm">Edit</a>
                                    @unless (in_array($page->slug, ['profil', 'hubungi-kami'], true))
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Hapus halaman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
