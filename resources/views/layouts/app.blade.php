<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@500&family=Poppins:ital,wght@0,300;0,500;0,600;1,300&display=swap" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('layouts.shared.meta-title')
    <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" />
<style>
    body
    /* {
        font-family: 'Arimo', sans-serif;
font-family: 'Poppins', sans-serif;
    } */
    .nav-sidebar>.nav-item p , .nav-item  {
        font-size: 0.9rem !important;
    margin-bottom: 0;
}
</style>

</head>

<body class="hold-transition sidebar-mini layout-fixed ">
    <div class="wrapper">
        @include('layouts.shared.nav-bar')
        @auth
        @include('layouts.shared.side-bar')
        @endauth

        <div class="content-wrapper">



            @yield('content')
        </div>

        @include('layouts.shared.footer')
    </div>
</body>

</html>
