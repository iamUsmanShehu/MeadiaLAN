@extends('layouts.app')

@section('title', 'MediaLAN - Home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Categories -->
    <section class="mb-12 sm:mb-16">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8">Browse by Category</h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @forelse($categories as $category)
                <a href="{{ route('category', $category) }}" class="group active:scale-95 transition">
                    <div class="bg-gray-800 rounded-lg p-4 sm:p-6 hover:bg-gray-700 transition min-h-[140px] flex flex-col justify-between border border-gray-700">
                        <div>
                            <h3 class="text-sm sm:text-xl font-semibold mb-2 group-hover:text-blue-500 line-clamp-2">{{ $category->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-400 line-clamp-2">{{ $category->description ?? 'No description' }}</p>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">{{ $category->media_count }} items</p>
                    </div>
                </a>
            @empty
                <p class="text-gray-400 col-span-2 sm:col-span-2 md:col-span-3 lg:col-span-4">No categories available</p>
            @endforelse
        </div>
    </section>

    <!-- Recent Media -->
    <section>
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8">Latest Additions</h2>
        <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-4">
            @forelse($recentMedia as $media)
                <a href="{{ route('media.show', $media) }}" class="group active:scale-95 transition">
                    <div class="bg-gray-800 rounded-lg overflow-hidden hover:transform hover:scale-105 transition border border-gray-700">
                        @if($media->poster_url)
                            <img src="{{ $media->poster_url }}" alt="{{ $media->title }}" class="w-full h-32 sm:h-48 md:h-64 object-cover">
                        @else
                            <div class="w-full h-32 sm:h-48 md:h-64 bg-gray-700 flex items-center justify-center">
                                <span class="text-xs sm:text-sm text-gray-500 px-2 text-center">No Image</span>
                            </div>
                        @endif
                        <div class="p-2 sm:p-4">
                            <h3 class="font-semibold text-xs sm:text-sm truncate group-hover:text-blue-500">{{ $media->title }}</h3>
                            <p class="text-xs text-gray-400">{{ ucfirst($media->type) }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-400 col-span-3 sm:col-span-3 md:col-span-4 lg:col-span-6">No media available</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
