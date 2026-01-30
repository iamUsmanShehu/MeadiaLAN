<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - MediaLAN')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('extra-css')
</head>
<body class="bg-gray-900 text-white">
    <div class="flex h-screen bg-gray-900">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div id="main-content" class="flex-1 flex flex-col md:ml-64 transition-all duration-300 overflow-hidden">
            <!-- Topbar -->
            @include('admin.layouts.topbar')

            <!-- Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const mainContent = document.getElementById('main-content');
            
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                // On desktop, add margin back
                if (window.innerWidth >= 768) {
                    mainContent.classList.add('md:ml-64');
                }
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                // On desktop, remove margin
                if (window.innerWidth >= 768) {
                    mainContent.classList.remove('md:ml-64');
                }
            }
        }
    </script>
    @yield('extra-js')
</body>
</html>
