@extends('layouts.admin')

@section('title', 'Tambah Halaman')
@section('pretitle', 'Manajemen')
@section('page-title', 'Tambah Halaman')

@section('content')
    <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Judul Halaman</label>
                            <input type="text" name="title" id="pageTitle" value="{{ old('title') }}" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label required">Slug</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ url('/halaman') }}/</span>
                                <input type="text" name="slug" id="pageSlug" value="{{ old('slug') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published" @selected(old('status', 'published') === 'published')>Published</option>
                                    <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OG Image</label>
                                <input type="file" name="og_image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meta Description</label>
                                <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="form-control">
                            </div>
                        </div>
                        <label class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="add_to_menu" value="1" @checked(old('add_to_menu'))>
                            <span class="form-check-label">Tambahkan otomatis ke menu utama</span>
                        </label>
                        <input type="text" name="menu_title" value="{{ old('menu_title') }}" class="form-control mt-2" placeholder="Label menu (opsional)">
                    </div>
                </div>

                <div class="card mb-3" data-template-panel="content">
                    <div class="card-header"><h3 class="card-title">Konten Bebas</h3></div>
                    <div class="card-body"><textarea id="editor-konten" name="content" rows="16" class="form-control">{{ old('content') }}</textarea></div>
                </div>

                <div class="card mb-3 d-none" data-template-panel="sections">
                    <div class="card-header">
                        <h3 class="card-title">Section Halaman</h3>
                        <div class="card-actions"><button type="button" class="btn btn-sm btn-primary" data-add-section><i class="ti ti-plus me-1"></i>Tambah Section</button></div>
                    </div>
                    <div class="card-body" id="sectionWrap">
                        @php($oldSections = old('sections', [['key' => 'section-1', 'judul' => '', 'isi' => '']]))
                        @foreach ($oldSections as $i => $section)
                            <div class="section-row border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">ID Section</label><input type="text" name="sections[{{ $i }}][key]" value="{{ $section['key'] ?? 'section-'.($i + 1) }}" class="form-control"></div>
                                    <div class="col-md-7 mb-3"><label class="form-label">Judul Section</label><input type="text" name="sections[{{ $i }}][judul]" value="{{ $section['judul'] ?? '' }}" class="form-control"></div>
                                    <div class="col-md-1 mb-3 d-flex align-items-end"><button type="button" class="btn btn-icon btn-ghost-danger" data-del-section><i class="ti ti-trash"></i></button></div>
                                </div>
                                <label class="form-label">Isi Section</label>
                                <textarea name="sections[{{ $i }}][isi]" rows="8" class="form-control editor-section">{{ $section['isi'] ?? '' }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-3 d-none" data-template-panel="landing">
                    <div class="card-header"><h3 class="card-title">Landing Page</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label class="form-label">Eyebrow</label><input class="form-control" name="template_data[eyebrow]" value="{{ old('template_data.eyebrow') }}"></div>
                            <div class="col-md-8 mb-3"><label class="form-label">Judul Hero</label><input class="form-control" name="template_data[hero_title]" value="{{ old('template_data.hero_title') }}"></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Subjudul Hero</label><textarea class="form-control" name="template_data[hero_subtitle]" rows="2">{{ old('template_data.hero_subtitle') }}</textarea></div>
                        <div class="row">
                            <div class="col-md-3 mb-3"><label class="form-label">Tombol Utama</label><input class="form-control" name="template_data[primary_label]" value="{{ old('template_data.primary_label') }}"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">URL Utama</label><input class="form-control" name="template_data[primary_url]" value="{{ old('template_data.primary_url') }}"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Tombol Kedua</label><input class="form-control" name="template_data[secondary_label]" value="{{ old('template_data.secondary_label') }}"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">URL Kedua</label><input class="form-control" name="template_data[secondary_url]" value="{{ old('template_data.secondary_url') }}"></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Konten Pendukung</label><textarea class="form-control editor-template" name="template_data[body]" rows="8">{{ old('template_data.body') }}</textarea></div>
                        <div class="hr-text">Highlight</div>
                        @for ($i = 0; $i < 4; $i++)
                            <div class="row">
                                <div class="col-md-4 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][title]" value="{{ old('template_data.items.'.$i.'.title') }}" placeholder="Judul highlight"></div>
                                <div class="col-md-8 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][body]" value="{{ old('template_data.items.'.$i.'.body') }}" placeholder="Deskripsi singkat"></div>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="card mb-3 d-none" data-template-panel="documents">
                    <div class="card-header"><h3 class="card-title">Dokumen / Download</h3></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Intro</label><textarea class="form-control editor-template" name="template_data[intro]" rows="6">{{ old('template_data.intro') }}</textarea></div>
                        @for ($i = 0; $i < 8; $i++)
                            <div class="border rounded p-3 mb-2">
                                <div class="row">
                                    <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][category]" value="{{ old('template_data.items.'.$i.'.category') }}" placeholder="Kategori"></div>
                                    <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][label]" value="{{ old('template_data.items.'.$i.'.label') }}" placeholder="Nama dokumen"></div>
                                    <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][url]" value="{{ old('template_data.items.'.$i.'.url') }}" placeholder="URL"></div>
                                    <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][description]" value="{{ old('template_data.items.'.$i.'.description') }}" placeholder="Deskripsi"></div>
                                </div>
                                <input type="file" class="form-control" name="template_data[items][{{ $i }}][file]">
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="card mb-3 d-none" data-template-panel="faq">
                    <div class="card-header"><h3 class="card-title">FAQ</h3></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Intro</label><textarea class="form-control editor-template" name="template_data[intro]" rows="6">{{ old('template_data.intro') }}</textarea></div>
                        @for ($i = 0; $i < 10; $i++)
                            <div class="border rounded p-3 mb-2">
                                <label class="form-label">Pertanyaan</label>
                                <input class="form-control mb-2" name="template_data[items][{{ $i }}][question]" value="{{ old('template_data.items.'.$i.'.question') }}">
                                <label class="form-label">Jawaban</label>
                                <textarea class="form-control editor-template" name="template_data[items][{{ $i }}][answer]" rows="4">{{ old('template_data.items.'.$i.'.answer') }}</textarea>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="card mb-3 d-none" data-template-panel="timeline">
                    <div class="card-header"><h3 class="card-title">Timeline</h3></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Intro</label><textarea class="form-control editor-template" name="template_data[intro]" rows="6">{{ old('template_data.intro') }}</textarea></div>
                        @for ($i = 0; $i < 8; $i++)
                            <div class="border rounded p-3 mb-2">
                                <div class="row">
                                    <div class="col-md-3 mb-2"><label class="form-label">Tanggal/Label</label><input class="form-control" name="template_data[items][{{ $i }}][date]" value="{{ old('template_data.items.'.$i.'.date') }}"></div>
                                    <div class="col-md-9 mb-2"><label class="form-label">Judul</label><input class="form-control" name="template_data[items][{{ $i }}][title]" value="{{ old('template_data.items.'.$i.'.title') }}"></div>
                                </div>
                                <label class="form-label">Isi</label>
                                <textarea class="form-control editor-template" name="template_data[items][{{ $i }}][body]" rows="4">{{ old('template_data.items.'.$i.'.body') }}</textarea>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="card mb-3 d-none" data-template-panel="embed">
                    <div class="card-header"><h3 class="card-title">Embed</h3></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Intro</label><textarea class="form-control editor-template" name="template_data[intro]" rows="6">{{ old('template_data.intro') }}</textarea></div>
                        <div class="row">
                            <div class="col-md-8 mb-3"><label class="form-label">URL Embed</label><input class="form-control" name="template_data[embed_url]" value="{{ old('template_data.embed_url') }}" placeholder="https://..."></div>
                            <div class="col-md-4 mb-3"><label class="form-label">Tinggi iframe</label><input type="number" min="300" max="1200" class="form-control" name="template_data[height]" value="{{ old('template_data.height', 640) }}"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card position-sticky" style="top:5rem">
                    <div class="card-header"><h3 class="card-title">Bentuk Halaman</h3></div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach ($templates as $key => $template)
                                <div class="col-12">
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="template" value="{{ $key }}" class="form-selectgroup-input" @checked(old('template', 'content') === $key)>
                                        <span class="form-selectgroup-label d-flex align-items-start p-3">
                                            <span class="me-3"><i class="ti {{ $template['icon'] }} fs-2 text-primary"></i></span>
                                            <span><span class="fw-bold d-block">{{ $template['label'] }}</span><span class="text-secondary small">{{ $template['description'] }}</span></span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-link">Batal</a>
                        <button class="btn btn-primary ms-auto"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="sectionTpl">
        <div class="section-row border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-4 mb-3"><label class="form-label">ID Section</label><input type="text" name="sections[__I__][key]" class="form-control"></div>
                <div class="col-md-7 mb-3"><label class="form-label">Judul Section</label><input type="text" name="sections[__I__][judul]" class="form-control"></div>
                <div class="col-md-1 mb-3 d-flex align-items-end"><button type="button" class="btn btn-icon btn-ghost-danger" data-del-section><i class="ti ti-trash"></i></button></div>
            </div>
            <label class="form-label">Isi Section</label>
            <textarea name="sections[__I__][isi]" rows="8" class="form-control editor-section"></textarea>
        </div>
    </template>
@endsection

@include('admin.partials.tinymce', ['selector' => '#editor-konten, .editor-section, .editor-template', 'height' => 280])

@push('scripts')
<script>
    (function () {
        var title = document.getElementById('pageTitle');
        var slug = document.getElementById('pageSlug');
        var slugTouched = !!(slug && slug.value);
        var sectionIndex = {{ count(old('sections', [['key' => 'section-1']])) + 1 }};
        var sectionWrap = document.getElementById('sectionWrap');
        var sectionTpl = document.getElementById('sectionTpl');
        var toSlug = function (text) { return (text || '').toLowerCase().normalize('NFKD').replace(/[^\w\s-]/g, '').trim().replace(/\s+/g, '-').replace(/-+/g, '-'); };
        if (slug) slug.addEventListener('input', function () { slugTouched = true; slug.value = toSlug(slug.value); });
        if (title && slug) title.addEventListener('input', function () { if (!slugTouched) slug.value = toSlug(title.value); });
        var applyTemplate = function () {
            var selected = document.querySelector('input[name="template"]:checked');
            document.querySelectorAll('[data-template-panel]').forEach(function (panel) { panel.classList.toggle('d-none', !selected || panel.getAttribute('data-template-panel') !== selected.value); });
            document.querySelectorAll('[data-template-panel] input, [data-template-panel] textarea, [data-template-panel] select').forEach(function (field) {
                var panel = field.closest('[data-template-panel]');
                field.disabled = !!selected && panel.getAttribute('data-template-panel') !== selected.value;
            });
        };
        document.querySelectorAll('input[name="template"]').forEach(function (radio) { radio.addEventListener('change', applyTemplate); });
        applyTemplate();
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-add-section]') && sectionWrap && sectionTpl) sectionWrap.insertAdjacentHTML('beforeend', sectionTpl.innerHTML.replaceAll('__I__', sectionIndex++));
            if (e.target.closest('[data-del-section]')) e.target.closest('.section-row').remove();
        });
    })();
</script>
@endpush
