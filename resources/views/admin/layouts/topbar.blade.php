<!-- Navbar -->
<nav class="sticky top-0 z-40 w-full bg-gray-900 border-b border-gray-800">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left Side -->
            <div class="flex items-center gap-4">
                <!-- Sidebar Toggle -->
                <button id="sidebar-toggle" onclick="toggleSidebar()" class="p-2 text-gray-400 hover:text-white transition rounded-lg">
                    <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Brand (visible when sidebar is hidden or on mobile) -->
                <div class="flex items-center gap-2 md:hidden">
                    <span class="text-xl">🎬</span>
                    <span class="text-lg font-bold text-white">MediaLAN</span>
                </div>

                <!-- Breadcrumb (hidden on mobile) -->
                <div class="hidden md:flex items-center gap-4">
                    <div class="flex items-center gap-2 text-sm">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition">Home</a>
                        <span class="text-gray-600">/</span>
                        <span class="text-white font-medium">@yield('page-title')</span>
                    </div>
                </div>
            </div>

            <!-- Middle: Search (hidden on mobile) -->
            <div class="hidden md:flex flex-1 max-w-xs px-4">
                <div class="relative w-full">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-800 border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-blue-500" />
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-4">
                <!-- User Menu -->
                <div class="flex items-center gap-3 pl-4 border-l border-gray-800">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg font-semibold text-sm">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>

                <!-- Logout -->
                <form method="post" action="{{ route('admin.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-gray-800 rounded-lg transition">
                        <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

