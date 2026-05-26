@if(!empty($image = ($content['data']['image'] ?? null)))
    @php($src = \Storage::disk('public')->url($image))
    <div class="image-container">
        <div class="image-container-image">
            @if(!empty($video = $content['data']['is_video']))
                <video src="{{ $src }}" controls>
                    Your browser does not support the video tag.
                    <source src="{{ $src }}" type="video/mp4">
                </video>
            @else
                <a data-fslightbox href="{{ $src }}">
                    <img src="{{ $src }}" alt="{{$content['data']['description'] ?? ""}}">
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
