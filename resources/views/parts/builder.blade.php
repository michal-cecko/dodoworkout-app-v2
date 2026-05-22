@foreach($contents as $content)
    @switch($content['type'])
        @case("image")
            @include("parts.content.media", ['content' => $content, 'resource' => $resource])
            @break
        @case("content")
            @include("parts.content.content", ['content' => $content, 'resource' => $resource])
            @break
        @case("blockquote")
            @include("parts.content.quote", ['content' => $content, 'resource' => $resource])
            @break
        @case("blockquote")
            @include("parts.content.quote", ['content' => $content, 'resource' => $resource])
            @break
        @case("gallery")
            @include("parts.content.gallery", ['content' => $content, 'resource' => $resource])
            @break
    @endswitch
@endforeach
