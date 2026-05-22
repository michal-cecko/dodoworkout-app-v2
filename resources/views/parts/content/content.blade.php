@if(!empty($content = $content['data']['content']))
    {!! tiptap_converter()->asHTML($content) !!}
@endif
