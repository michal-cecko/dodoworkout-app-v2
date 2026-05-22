@if(!empty($images = ($content['data']['images'] ?? null)))
    <div class="gallery">
        @foreach($images as $image)
            <div class="gallery-item">
                <a data-fslightbox href="/storage/{{$image}}">
                    <img src="/storage/{{$image}}" alt="">
                </a>
            </div>
        @endforeach
    </div>
@endif
