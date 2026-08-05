{{-- Baris menu rekursif (draggable). Variabel: $item, $level --}}
<div class="menu-item" data-id="{{ $item->id }}">
    <div class="list-group-item d-flex align-items-center gap-2">
        <span class="menu-drag text-secondary" style="cursor:grab" title="Seret untuk mengatur urutan"><i class="ti ti-grip-vertical"></i></span>
        <div class="flex-fill text-truncate">
            <span class="fw-bold">{{ $item->title }}</span>
            @if ($item->url)
                <code class="ms-2 small">{{ $item->url }}</code>
            @else
                <span class="badge bg-secondary-lt ms-2">induk</span>
            @endif
            @unless ($item->aktif) <span class="badge bg-red-lt ms-1">nonaktif</span> @endunless
            @if ($item->target === '_blank') <i class="ti ti-external-link text-secondary ms-1"></i> @endif
        </div>
        <div class="btn-list flex-nowrap">
            <a href="{{ route('admin.menus.edit', $item) }}" class="btn btn-sm">Edit</a>
            <form method="POST" action="{{ route('admin.menus.destroy', $item) }}" onsubmit="return confirm('Hapus menu ini beserta sub-menunya?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-ghost-danger">Hapus</button>
            </form>
        </div>
    </div>

    @if ($level < 2 && $item->children->isNotEmpty())
        {{-- Daftar sub-menu (dapat di-drag untuk mengatur urutan di levelnya) --}}
        <div class="menu-sortable ps-4 border-start ms-3">
            @foreach ($item->children as $child)
                @include('admin.menus._row', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
