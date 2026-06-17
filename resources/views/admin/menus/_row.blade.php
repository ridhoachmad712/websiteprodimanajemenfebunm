{{-- Baris menu rekursif. Variabel: $item, $level --}}
<div class="list-group-item">
    <div class="row align-items-center">
        <div class="col-auto" style="width:{{ $level * 1.5 + 0.5 }}rem"></div>
        <div class="col text-truncate">
            @if ($level > 0)<i class="ti ti-corner-down-right text-secondary me-1"></i>@endif
            <span class="fw-bold">{{ $item->title }}</span>
            @if ($item->url)
                <code class="ms-2 small">{{ $item->url }}</code>
            @else
                <span class="badge bg-secondary-lt ms-2">induk</span>
            @endif
            @unless ($item->aktif) <span class="badge bg-red-lt ms-1">nonaktif</span> @endunless
            @if ($item->target === '_blank') <i class="ti ti-external-link text-secondary ms-1"></i> @endif
        </div>
        <div class="col-auto text-secondary small">urutan: {{ $item->urutan }}</div>
        <div class="col-auto">
            <div class="btn-list flex-nowrap">
                <a href="{{ route('admin.menus.edit', $item) }}" class="btn btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.menus.destroy', $item) }}" onsubmit="return confirm('Hapus menu ini beserta sub-menunya?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach ($item->children as $child)
    @include('admin.menus._row', ['item' => $child, 'level' => $level + 1])
@endforeach
