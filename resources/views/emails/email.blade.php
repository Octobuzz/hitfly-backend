<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<body>
@include('emails.header')
@yield('content')
@include('emails.footer')
</body>
</html>