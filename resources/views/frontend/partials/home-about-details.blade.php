@if (!empty($d['body']))<p class="home-about-lead mb-4">{{ $d['body'] }}</p>@endif
@if (!empty($d['items']))
    <div class="home-about-points">
        @foreach ($d['items'] as $it)
            @if (!empty($it['text']))
                <div class="home-about-point"><i class="ti ti-circle-check" aria-hidden="true"></i><span>{{ $it['text'] }}</span></div>
            @endif
        @endforeach
    </div>
@endif
@if (!empty($d['button_label']) && !empty($d['button_url']))
    <a href="{{ $d['button_url'] }}" class="btn btn-primary mt-4">{{ $d['button_label'] }}</a>
@endif
