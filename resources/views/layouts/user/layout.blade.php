<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.user.includes.header')
<body>
    <!-- Topbar Navigation -->
    @include('layouts.user.includes.topbar')

    <!-- Main Content -->
    <div class="container py-5">
        @yield('content')
    </div>

    @include('layouts.user.includes.footer')
    @stack('scripts')
</body>
</html>
