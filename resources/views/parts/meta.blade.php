<link rel="icon" type="image/png" href="favicon/favicon-96x96.png" sizes="96x96"/>
<link rel="icon" type="image/svg+xml" href="favicon/favicon.svg"/>
<link rel="shortcut icon" href="favicon/favicon.ico"/>
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png"/>
<meta name="apple-mobile-web-app-title" content="DODOWORKOUT"/>
<link rel="manifest" href="favicon/site.webmanifest"/>

<meta http-equiv="X-UA-Compatible" content="ie=edge">

@if(app()->currentLocale() === "sk")
    <meta name="description" content="Dominik Klimek, profesionálny atlét kalisteniky a street workoutu, majster sveta a certifikovaný trénera WSWCF Academy.">
@else
    <meta name="description" content="Dominik Klimek, a professional calisthenics and street workout athlete, world champion and certified coach of the WSWCF Academy.">
@endif

<!-- Open Graph Meta Tags for Social Media -->
@if(app()->currentLocale() === "sk")
    <meta property="og:title" content="Dodoworkout - Profesionálny tréner kalisteniky">
    <meta property="og:description" content="Dominik Klimek, profesionálny atlét kalisteniky a street workoutu, majster sveta a certifikovaný trénera WSWCF Academy.">
@else
    <meta property="og:title" content="Dodoworkout - Professional Calisthenics Coach">
    <meta property="og:description" content="Dominik Klimek, a professional calisthenics and street workout athlete, world champion and certified coach of the WSWCF Academy.">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="DODOWORKOUT">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
@if(app()->currentLocale() === "sk")
    <meta name="twitter:title" content="Dodoworkout - profesionálny atlét a tréner kalisteniky">
    <meta name="twitter:description" content="Dominik Klimek, profesionálny atlét kalisteniky a street workoutu, majster sveta a certifikovaný trénera WSWCF Academy.">
@else
    <meta name="twitter:title" content="Dodoworkout - professional calisthenics athelete & coach">
    <meta name="twitter:description" content="Dominik Klimek, a professional calisthenics and street workout athlete, world champion and certified coach of the WSWCF Academy.">
@endif

<!-- Additional SEO Meta Tags -->
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
