<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.2.5/swiper-bundle.min.css" />


        @include('parts.meta')

        @hasSection('meta-images')
            @yield('meta-images')
        @else
            <meta property="og:image" content="{{ asset('image/og-image.jpg') }}">
            <meta property="twitter:image" content="{{ asset('image/og-image.jpg') }}">
        @endif

        <title>
            @hasSection('title')
                @yield('title')
            @else
                Dodoworkout
            @endif
        </title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @hasSection('head')
            @yield('head')
        @endif
    </head>
    <body class="font-sans antialiased">
        @hasSection("header")
            @yield('header')
        @else
            @include('parts.header')
        @endif

        @yield('body')

        @hasSection("footer")
            @yield('footer')
        @else
            @include('parts.footer')
        @endif

        @hasSection("scripts")
            @yield('scripts')
        @endif

        @include('parts.scripts')
    </body>
</html>
