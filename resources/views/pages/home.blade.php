@php use App\Models\Post;use App\Services\LocaleService; @endphp
@extends('layouts.web')

@section('body')

    <section id="hp_hero" class="relative px-14 mb-14 -mt-[var(--header-height)] pt-[var(--header-height)] max-lg:px-0"
             style="min-height: calc(90dvh - var(--header-height));">
        <div class="container-wrapper max-lg:mt-16 max-lg:!max-w-full">
            <div id="hero-swiper" class="relative overflow-hidden max-lg:px-[var(--container-padding)]">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="flex items-center gap-12 relative max-lg:flex-col">
                            <div class="w-1/2 flex flex-col gap-20 max-lg:w-full">
                                <div>
                                    <h2 class="text-5xl uppercase max-w-[586px] max-lg:max-w-full mb-7 hfont max-lg:text-3xl">
                                        {!! __("home_certification_slide_title") !!}
                                    </h2>
                                    <p class="text-textSecondary text-xl mb-7 pr-20 max-lg:pr-8">
                                        {!! __("home_certification_slide_description") !!}
                                    </p>
                                    <div class="flex ml-2">
                                        <a class="btn" data-variant="primary"
                                           href="{{LocaleService::getLocalizedRoutePathByName(name: "event", parameters: ['event' => 'stan-sa-certifikovanym-trenerom-kalisteniky'])}}">{!! __("home_certification_slide_button_primary") !!}</a>
                                    </div>
                                </div>

                                <!-- Toto je image ktory sa zobrazuje v slideri na uzsich obrazovkach -->
                                <div class="hidden relative max-lg:block w-full flex items-center justify-center"
                                     style="translate: calc(-1 * var(--container-padding));">
                                    <img src="image/seminar.jpg"
                                         class="w-full h-full object-cover object-center rounded-r-3xl"
                                         alt="Dominik klimek performing one arm handstand.">

                                    <div
                                        class="hidden bg-primary absolute top-1/2 right-0 w-[343px] aspect-square -z-10 max-lg:block"
                                        style="translate: calc(var(--container-padding) * 2 + 60px) -50%;"></div>
                                </div>

                                <div>
                                    <div class="opacity-75 flex items-center gap-6">
                                        @include('parts.socials')
                                    </div>
                                </div>
                            </div>

                            <!-- Toto je image ktory sa zobrazuje v slideri na desktope -->
                            <div class="w-1/2 shrink-0 max-lg:hidden overflow-hidden rounded-3xl flex items-center justify-center"
                                 style="max-height: min(calc(100dvh - var(--header-height) * 2), 800px);">
                                <img src="image/seminar.jpg"
                                     class="w-full h-full object-cover object-center"
                                     alt="Dominik klimek performing one arm handstand.">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="flex items-center gap-12 relative max-lg:flex-col">
                            <div class="w-1/2 flex flex-col gap-20 max-lg:w-full">
                                <div>
                                    <h2 class="text-5xl uppercase max-w-[586px] max-lg:max-w-full mb-7 hfont max-lg:text-3xl">
                                        {!! __("home_hero_heading") !!}
                                    </h2>
                                    <p class="text-textSecondary text-xl mb-7 pr-20 max-lg:pr-8">
                                        {!! __("home_hero_text") !!}
                                    </p>
                                    <div class="flex ml-2">
                                        {{--<a class="btn" data-variant="primary"
                                           href="#">{!! __("home_hero_button_primary") !!}</a>
                                        <a class="btn" href="#">{!! __("home_hero_button_secondary") !!}</a>--}}
                                        <a class="btn" data-variant="primary"
                                           href="{{Post::find(12)->permalink}}">{!! __("home_hero_button_secondary") !!}</a>
                                    </div>
                                </div>

                                <!-- Toto je image ktory sa zobrazuje v slideri na uzsich obrazovkach -->
                                <div class="hidden relative max-lg:block w-full flex items-center justify-center"
                                     style="translate: calc(-1 * var(--container-padding));">
                                    <img src="image/hp-hero-placeholder.jpg"
                                         class="w-full h-full object-cover object-center rounded-r-3xl"
                                         alt="Dominik klimek performing one arm handstand.">

                                    <div
                                        class="hidden bg-primary absolute top-1/2 right-0 w-[343px] aspect-square -z-10 max-lg:block"
                                        style="translate: calc(var(--container-padding) * 2 + 60px) -50%;"></div>
                                </div>

                                <div>
                                    <div class="opacity-75 flex items-center gap-6">
                                        @include('parts.socials')
                                    </div>
                                </div>
                            </div>

                            <!-- Toto je image ktory sa zobrazuje v slideri na desktope -->
                            <div class="w-1/2 shrink-0 max-lg:hidden overflow-hidden rounded-3xl flex items-center justify-center"
                                 style="max-height: min(calc(100dvh - var(--header-height) * 2), 800px);">
                                <img src="image/hp-hero-placeholder.jpg"
                                     class="w-full h-full object-cover object-center"
                                     alt="Dominik klimek performing one arm handstand.">
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="max-md:hidden absolute bg-primary text-white left-[50%] bottom-[60px] translate-x-[-50%] z-10 flex rounded-xl max-lg:top-[68%] max-lg:translate-y-[-50%] max-lg:bottom-auto">
                    <button class="pl-4 py-3" id="hero-swiper-prev">{!! svgIcon("icon/icon-arrow.svg") !!}</button>
                    <div class="flex items-center gap-1 h-12 px-4 font-bold">
                        <span id="hero-swiper-current-page"></span>
                        <span>/</span>
                        <span id="hero-swiper-total-pages"></span>
                    </div>
                    <button class="pr-4 py-3"
                            id="hero-swiper-next">{!! svgIcon("icon/icon-arrow.svg", ['class' => ['rotate-180']]) !!}</button>
                </div>

                <!-- Toto je ten mesh svg ktory je v pozadi na desktope -->
                {!! svgIcon("svg/dots-mesh.svg", ['class' => ['text-[#D9D9D9] max-lg:hidden absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]']]) !!}
            </div>

        </div>
        <div class="bg-primary absolute top-0 right-0 w-[463px] aspect-square -z-10 max-lg:hidden"></div>
        {!! svgIcon("svg/striped-circle.svg", ['class' => ['hidden max-lg:block text-[#FFC1C1] absolute z-[-1] right-0 top-[10%]']]) !!}
    </section>

    @if($posts->isNotEmpty())
        <section class="card-holder gray py-20 relative overflow-hidden">
            <div class="container-wrapper mb-12">
                <h4 class="text-primary font-semibold mb-1">{{__("home_blog_subheading")}}</h4>
                <div class="flex gap-4 max-lg:flex-col">
                    <h2 class="text-3xl font-bold uppercase max-w-[500px] hfont max-lg:text-3xl">
                        {{__("home_blog_heading")}}
                    </h2>

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
                    {{--<a href="#" class="btn self-start whitespace-nowrap" data-variant="primary">
                        {{ __("view_all") }}
                    </a>--}}
                </div>

                <div id="workshops-swiper" class="overflow-hidden w-full">
                    <div class="swiper-wrapper">
                        @foreach($posts as $post)
                            <div class="swiper-slide px-3 max-md:px-6">
                                @include("parts.post-card", ["post" => $post])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="max-md:hidden absolute top-[40%] rotate-[-7deg] bg-progress-curve" style="background: url('/svg/progress.svg') repeat-x center;"></div>
            </div>
        </section>
    @endif
@endsection

@section("scripts")
    @vite(['resources/js/home.js'])
@endsection
