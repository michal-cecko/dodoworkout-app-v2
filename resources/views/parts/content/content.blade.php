@php($content = $content['data']['content'] ?? null)
@if(! empty($content))
    {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($content)->toHtml() !!}
@endif
