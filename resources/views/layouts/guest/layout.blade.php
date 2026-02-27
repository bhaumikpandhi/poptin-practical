<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.guest.includes.header')
<body>
    @yield('content')
    @include('layouts.guest.includes.footer')
</body>
</html>