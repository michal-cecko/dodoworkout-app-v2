<a href="{{ $event->permalink }}">
    <div class="card">
        <div class="image-container">
            <img class="w-full h-full object-cover"
                 src="{{$event->getFirstMediaUrl("image")}}" alt="{{$event->title}}">
            <div class="date-badge">
                <span class="month">{{ $event->start_at->translatedFormat('M') }}</span>
                <span>{{ $event->start_at->format('d') }}</span>
            </div>
        </div>

        <h3 class="title">{{ $event->title }}</h3>

        <div class="info-container">
            @if(!empty($event->participants_count))
                <div class="info-item">
                    {!! svgIcon("icon/icon-user_group.svg", ['class' => ['text-primary mx-auto']]) !!}
                    <span class="{{ $event->last_few_left ? "text-primary font-bold" : "" }}">{{ trans_choice("places_left", $event->participants_available, ["count" => $event->participants_available]) }}</span>
                </div>
            @endif

            @if(!empty($event->address))
                <div class="info-item">
                    {!! svgIcon("icon/icon-map_marker.svg", ['class' => ['text-primary mx-auto']]) !!}
                    <span>{{ $event->address }}</span>
                </div>
            @endif

            @if($event->days > 0)
                <div class="info-item">
                    {!! svgIcon("icon/icon-hourglass.svg", ['class' => ['text-primary mx-auto']]) !!}
                    <span> {{ __("days_count", ["count" => $event->days]) }}</span>
                </div>
            @endif
        </div>

        @if(!empty($event->excerpt))
            <p class="description">
                {{ $event->excerpt }}
            </p>
        @endif

        <div class="price-cta-container">
            @if(!empty($event->last_price))
                <span class="sale-price">{{ (float) $event->price }} €</span>
                <span class="price">{{ (float) $event->last_price }} €</span>
            @else
                <span class="price">{{ (float) $event->price }} €</span>
            @endif
            <span class="cta-link text-primary">{{ __("learn_more") }}</span>
        </div>
    </div>
</a>
