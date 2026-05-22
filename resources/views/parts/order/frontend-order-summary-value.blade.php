@if(is_array($value))
        @foreach($value as $key => $item)
            @if(is_array($item))
                @include('parts.order.frontend-order-summary-value', ['value' => $item])
            @elseif($item instanceof \Illuminate\Http\UploadedFile)
                <span class="item">{{ $item->getClientOriginalName() }}</span>
            @else
                <span class="item">{{ $item }}</span>
            @endif
        @endforeach
@elseif($value instanceof \Illuminate\Http\UploadedFile)
    <span class="item">{{ $value->getClientOriginalName() }}</span>
@else
    <span class="item">{{ $value }}</span>
@endif
