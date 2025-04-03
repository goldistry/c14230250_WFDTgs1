<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Meta tags --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="keywords" content="promosi, drama, drama China">
    <meta name="description" content="Website promosi drama China">
    <meta name="author" content="C14230250 | Jessica Chandra">

    {{-- tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- SWIPER JS --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    {{-- leni smooth scroll --}}
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.13/dist/lenis.css">

    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>{{ $title }}</title>


    @yield('head')
    <style>
        body {
            width: 100vw;
            overflow-x: hidden;
            top: 0;
            left: 0;
        }


        /* font style */
        .questrial-title {
            font-family: "Questrial", sans-serif;
            font-weight: 800;
            font-style: normal;
        }


        /* Chrome, Edge and Safari */
        *::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        *::-webkit-scrollbar-track {
            background-color: gray;
        }

        *::-webkit-scrollbar-track:hover {
            background-color: gray;
        }

        *::-webkit-scrollbar-track:active {
            background-color: gray;
        }

        *::-webkit-scrollbar-thumb {
            border-radius: 5px;
            background: linear-gradient(to bottom, transparent, #F4DEB5);
        }



        ::selection {
            color: white;
            background: #F4DEB5;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>

</head>

<body class="bg-neutral-950">
    <div>
        @if (!Request::is('/homepage'))
            @include('navbar')
        @endif

        @yield('content')
    </div>

    {{-- @include('loader.loader-logic') --}}

    {{-- AOS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- GSAP --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    {{-- SWIPER JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
   
    {{-- leni smooth scroll --}}
    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>

    <script>
        AOS.init();

        const lenis = new Lenis()


        lenis.on('scroll', ScrollTrigger.update)

        gsap.ticker.add((time) => {
            lenis.raf(time * 1000)
        })

        gsap.ticker.lagSmoothing(0)
    </script>

    @yield('scripts')
</body>

</html>
