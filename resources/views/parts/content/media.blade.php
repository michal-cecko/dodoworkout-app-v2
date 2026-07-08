@if(!empty($image = ($content['data']['image'] ?? null)))
    @php($src = \Storage::disk('public')->url($image))
    @php($desc = $content['data']['description'] ?? null)
    @php($descRenderer = ! empty($desc) ? \Filament\Forms\Components\RichEditor\RichContentRenderer::make($desc) : null)
    <div class="image-container">
        <div class="image-container-image">
            @if(!empty($video = $content['data']['is_video']))
                <video src="{{ $src }}" controls>
                    Your browser does not support the video tag.
                    <source src="{{ $src }}" type="video/mp4">
                </video>
            @else
                <a data-fslightbox href="{{ $src }}">
                    <img src="{{ $src }}" alt="{{ $descRenderer?->toText() ?? '' }}">
                </a>
            @endif
        </div>
        @if($descRenderer)
            <p class="image-container-caption">
                {!! $descRenderer->toHtml() !!}
            </p>
        @endif
    </div>
@endif
