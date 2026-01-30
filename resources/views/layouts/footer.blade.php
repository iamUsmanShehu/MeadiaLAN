<footer class="bg-gray-800 border-t border-gray-700 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-3 gap-8 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-blue-500 mb-4">MediaLAN</h3>
                <p class="text-gray-400">Local media streaming and download system</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Browse</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    @foreach(\App\Models\Category::all() as $category)
                        <li><a href="{{ route('category', $category) }}" class="hover:text-white">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Admin</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('admin.login') }}" class="hover:text-white">Admin Login</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} MediaLAN. All rights reserved.</p>
        </div>
    </div>
</footer>
