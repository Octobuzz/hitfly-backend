<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if(\Auth::user() !== null)
        <meta authuser="{{\Auth::user()->id}}" >
        <meta roles="{{\Auth::user()->roles}}" >
    @else
        <meta authuser="null" >
    @endif
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection ('title')
            @yield('title')
        @else
           {{config('app.name', 'Laravel')}}
        @endif
    </title>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="body">

<!--TODO: remove after refactoring -->
<div class="non-spa-style">
    @yield('content')
</div>

@yield('spa')


<!-- Scripts -->
<script src="{{ mix('js/app.js') }}" defer></script>

</body>
</html>
