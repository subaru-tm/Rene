<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')

    @livewireStyles

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rese</title>

    <!-- Scripts 
    <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


</head>
<body>
    <div class="menu">
        @if ( !$errors->any() )
            @livewire('modal')
        @else
        <div class="header">
            <button class="menu-button" type="button">
                <img src="{{ asset('storage/menu_button.png') }}" alt="" />
            </button>
            <a class="app-name" href="/">
                Rese
            </a>
        </div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>
