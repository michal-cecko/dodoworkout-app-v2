@if(!empty($image = ($content['data']['image'] ?? null)))
    <div class="image-container">
        <div class="image-container-image">
            @if(!empty($video = $content['data']['is_video']))
                <video src="/storage/{{$image}}" controls>
                    Your browser does not support the video tag.
                    <source src="/storage/{{$image}}" type="video/mp4">
                </video>
            @else
                <a data-fslightbox href="/storage/{{$image}}">
                    <img src="/storage/{{$image}}" alt="{{$content['data']['description'] ?? ""}}">
                </a>
            @endif
        </div>
        @if(!empty($desc = ($content['data']['description'] ?? null)))
            <p class="image-container-caption">
                {{$desc}}
            </p>
        @endif
    </div>
@endif
