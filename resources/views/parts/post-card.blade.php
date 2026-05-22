@php use Illuminate\Support\Str; @endphp
<a href="{{$post->permalink}}">
    <div class="card">
        <div class="image-container">
            <img class="w-full h-full object-cover" src="{{$post->getFirstMediaUrl("image")}}"
                 alt="{{$post->title}}">
        </div>

        @if($post->tags->isNotEmpty())
            <div class="tags">
                @foreach($post->tags as $tag)
                    <span class="tag">{{$tag->name}}</span>
                @endforeach
            </div>
        @endif

        <h3 class="title">{{$post->title}}</h3>

        @if(!empty($post->excerpt))
            <p class="description">{{Str::words($post->excerpt, 20)}} </p>
        @endif

        <div class="price-cta-container">
            <time>{{$post->published_at->translatedFormat("j. F Y - H:i")}}</time>
            <span class="cta-link text-primary">{{__("read_article")}}</span>
        </div>
    </div>
</a>
