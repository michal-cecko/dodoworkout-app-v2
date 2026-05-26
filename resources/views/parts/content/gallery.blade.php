@if(!empty($images = ($content['data']['images'] ?? null)))
    <div class="gallery">
        @foreach($images as $image)
            @php($src = \Storage::disk('public')->url($image))
            <div class="gallery-item">
                <a data-fslightbox href="{{ $src }}">
                    <img src="{{ $src }}" alt="">
                </a>
            </div>
        @endforeach
    </div>
@endif
