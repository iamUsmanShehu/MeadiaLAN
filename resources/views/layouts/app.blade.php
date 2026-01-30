<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediaLAN')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('extra-css')
</head>
<body class="bg-gray-900 text-white">
    <!-- Navigation -->
    @include('layouts.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('extra-js')
</body>
</html>
