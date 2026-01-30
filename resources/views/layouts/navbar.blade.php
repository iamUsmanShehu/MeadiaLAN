<nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4 sm:h-16 sm:gap-0">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-bold text-blue-500 flex-shrink-0">
                MediaLAN
            </a>

            <!-- Search -->
            <form action="{{ route('search') }}" method="get" class="w-full sm:flex-1 sm:mx-8">
                <input 
                    type="search" 
                    name="q" 
                    placeholder="Search movies & series..." 
                    class="w-full px-3 sm:px-4 py-2 sm:py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                    required
                >
            </form>

            <!-- Admin Link -->
            <a href="{{ route('admin.login') }}" class="text-gray-300 hover:text-white font-semibold transition active:text-blue-500">
                Admin
            </a>
        </div>
    </div>
</nav>
