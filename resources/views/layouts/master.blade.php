<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        @section('styles')
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @show
    </head>
    <body>
        <div id="app">
            @include('layouts.includes.header')

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <!-- Scripts -->
        @section('scripts')
            <script src="{{ asset('js/app.js') }}" defer></script>
        @show
    </body>
</html>
