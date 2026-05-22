@php use Illuminate\Support\Str; @endphp
@extends('layouts.web')

@php
    $image = $post->getFirstMedia("image");
    $excerpt = Str::limit($post->excerpt, 155, '...');
@endphp

@section('title')
    {{ $post->title }} | Dodoworkout
@endsection

@if($image)
    @section('meta-images')
        <meta property="og:image" content="{{ $image->getFullUrl() }}"/>
        <meta name="twitter:image" content="{{ $image->getFullUrl() }}"/>
    @endsection
@endif

@section('head')
    <meta property="og:title" content="{{ $post->title }}"/>
    <meta property="og:description" content="{{ $excerpt }}"/>
    <meta property="og:url" content="{{ $post->permalink }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:site_name" content="DODOWORKOUT"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $post->title }}"/>
    <meta name="twitter:description" content="{{ $excerpt }}"/>
@endsection

@section('body')
    <section id="blog" class="pt-10 pb-12 pb-sm-24 relative flex flex-col max-lg:pt-0">
        <div
            class="container-wrapper relative max-lg:order-2 max-lg:pt-6 max-lg:-mt-6 z-[20] max-lg:rounded-3xl bg-white max-lg:px-6 max-lg:!max-w-full">
            <span
                class="block text-primary font-semibold mx-auto w-fit mb-3 uppercase max-lg:mx-0 max-lg:text-sm max-lg:mt-6">BLOG</span>
            <h1 class="hfont text-4xl font-bold mb-7 text-center max-lg:text-2xl max-lg:text-left">
                {{$post->title}}
            </h1>
            <div class="flex gap-2 items-center mb-11 justify-center max-lg:justify-start max-lg:mb-0">
                <time
                    class="mr-6 text-textSecondary max-lg:text-xs">{{$post->created_at->translatedFormat("j. F Y - H:i")}}</time>

                <button
                    class="flex items-center border border-[#EDEDED] p-[6px] bg-[#F8F8F8] text-sm gap-1 rounded-lg h-6">
                    {!! svgIcon("icon/icon-like.svg", ['class' => ['']]) !!}
                    @if(!empty($post->likes))
                        <span>{{$post->likes}}</span>
                    @endif
                </button>

                <button
                    class="flex items-center border border-[#EDEDED] p-[6px] bg-[#F8F8F8] text-sm gap-1 rounded-lg h-6">
                    {!! svgIcon("icon/icon-like.svg", ['class' => ['rotate-180 scale-x-[-1]']]) !!}
                </button>
            </div>

            {!! svgIcon("svg/dots-mesh.svg", ['class' => ['max-lg:hidden absolute left-10 top-[10%] w-[calc(100vw * 2)] text-[#D9D9D9]']]) !!}

        </div>

        @if($image)
            <div class="relative isolate max-lg:order-1">
                <div
                    class="absolute max-h-[360px] top-1/2 -translate-y-1/2 left-0 w-full h-full bg-primary z-[-1] max-lg:hidden"></div>
                <div class="h-[512px] max-w-[1098px] mx-auto rounded-3xl overflow-hidden max-lg:rounded-none">
                    <a data-fslightbox href="{{$image->getFullUrl()}}">
                        <img class="w-full h-full object-cover" src="{{$image->getFullUrl()}}" alt="{{$post->title}}">
                    </a>
                </div>
            </div>
        @endif

        <div class="container-wrapper mt-12 order-3">
            <article class="w-full max-w-[900px] mx-auto max-lg:!max-w-full content-builder">

                @include("parts.builder", ['contents' => $post->content, 'resource' => $post])

                @if($post->tags->isNotEmpty())
                    <div class="tags">
                        @foreach($post->tags as $tag)
                            <span class="tag">{{$tag->name}}</span>
                        @endforeach
                    </div>
                @endif

            </article>
        </div>
    </section>

    @if($relatedPosts->isNotEmpty())
        <div class="bg-white py-20 card-holder gray relative overflow-hidden">
            <div class="container-wrapper mb-12">
                <h4 class="text-primary font-semibold mb-7 max-lg:mb-6">{{__("article_related_subheading")}}</h4>
                <div class="flex gap-4 max-lg:flex-col">
                    <h2 class="text-3xl font-bold uppercase max-w-[500px] hfont max-lg:text-3xl">{{__("article_related_heading")}}</h2>

                    <div class="flex gap-4 ml-auto items-center text-[#B2B2B2] justify-between max-lg:ml-0">
                        <button id="workshop-swiper-prev" class="swiper-button">
                            {!! svgIcon("icon/icon-arrow.svg") !!}
                        </button>
                        <button id="workshop-swiper-next" class="swiper-button">
                            {!! svgIcon("icon/icon-arrow.svg", ['class' => ['rotate-180']]) !!}
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex gap-[141px] max-lg:flex-col-reverse max-lg:gap-12">
                <div class="max-lg:mx-auto"
                     style="padding-left: max(calc((100vw - var(--max-container-width)) / 2), var(--container-padding));">
                    {{--<a href="{{LocaleService::getLocalizedRoutePathByName("blog")}}"
                       class="btn self-start whitespace-nowrap" data-variant="primary">
                        {{__("view_all")}}
                        {!! svgIcon("icon/icon-arrow.svg", ['class' => ['rotate-180']]) !!}
                    </a>--}}
                </div>

                <div id="workshops-swiper" class="overflow-hidden w-full">
                    <div class="swiper-wrapper">
                        @foreach($relatedPosts as $post)
                            <div class="swiper-slide px-3 max-md:px-6">
                                @include('parts.post-card', ['post' => $post])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="max-md:hidden absolute top-[40%] rotate-[-7deg] bg-progress-curve" style="background: url('/svg/progress.svg') repeat-x center;"></div>
        </div>
        </div>
    @endif
@endsection

@section("scripts")
    @vite(['resources/js/post.js'])
@endsection
