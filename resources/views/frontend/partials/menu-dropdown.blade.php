{{-- Item dropdown rekursif (mendukung submenu hingga 3 level). Variabel: $items --}}
@foreach ($items as $item)
    @if ($item->activeChildren->isNotEmpty())
        <div class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="{{ $item->href }}">{{ $item->title }}</a>
            <div class="dropdown-menu">
                @include('frontend.partials.menu-dropdown', ['items' => $item->activeChildren])
            </div>
        </div>
    @else
        <a class="dropdown-item" href="{{ $item->href }}" @if($item->target === '_blank') target="_blank" rel="noopener" @endif>{{ $item->title }}</a>
    @endif
@endforeach
