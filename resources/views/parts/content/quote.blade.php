@if(!empty($text = $content['data']['text']))
    <div class="quote">
        <div class="quote-text">
            “{{$text}}”
        </div>

        @if($author = $content['data']['author'])
            <div class="quote-author">
                <span class="quote-author-name">{{$author}}</span>
                @if($position = $content['data']['position'])
                    <span class="quote-author-title">{{$position}}</span>
                @endif
            </div>
        @endif
    </div>
@endif
