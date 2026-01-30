<!-- Sidebar -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden md:hidden" onclick="toggleSidebar()"></div>
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-800 z-50 transform -translate-x-full md:translate-x-0 sidebar-transition">
    <!-- Close button for mobile -->
    <button onclick="toggleSidebar()" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-white md:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Logo Section -->
    <div class="flex items-center gap-3 px-6 py-8">
        <div class="flex items-center justify-center h-10 w-10 min-w-[40px] rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 text-white font-bold">
            🎬
        </div>
        <span class="text-lg font-semibold text-white">MediaLAN</span>
    </div>

    <!-- Navigation -->
    <nav class="px-3 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l-7-4m0 0l-2-3m2 3v-10a1 1 0 011-1h12a1 1 0 011 1v10m-9 0l7 4" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7a2 2 0 012-2h14a2 2 0 012 2m0 0V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2m0 0h14" />
            </svg>
            <span>Categories</span>
        </a>

        <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.media.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4" />
            </svg>
            <span>Media</span>
        </a>

        <a href="{{ route('admin.pins.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.pins.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>PINs</span>
        </a>
    </nav>

    <!-- Divider -->
    <div class="my-6 mx-3 border-t border-gray-800"></div>

    <!-- User Section -->
    <div class="px-3 space-y-1">
        <a href="{{ route('admin.change-password') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-400 rounded-lg hover:bg-gray-800 hover:text-white">
            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>Change Password</span>
        </a>

        <form method="post" action="{{ route('admin.logout') }}" class="block">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-500 rounded-lg hover:bg-red-900/20">
                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <!-- Footer Info -->
    <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-gray-800 bg-gray-900/50">
        <p class="text-xs font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
        <p class="text-xs text-gray-500 mt-1">Administrator</p>
    </div>
</aside>

