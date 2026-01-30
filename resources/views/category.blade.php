@extends('layouts.app')

@section('title', $category->name . ' - MediaLAN')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2">{{ $category->name }}</h1>
    <p class="text-gray-400 mb-6 sm:mb-8 text-sm sm:text-base">{{ $category->description ?? 'Explore our collection' }}</p>

    <!-- Media Grid -->
    <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-4 mb-8">
        @forelse($media as $item)
            <a href="{{ route('media.show', $item) }}" class="group active:scale-95 transition">
                <div class="bg-gray-800 rounded-lg overflow-hidden hover:transform hover:scale-105 transition border border-gray-700">
                    @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-32 sm:h-48 md:h-64 object-cover">
                    @else
                        <div class="w-full h-32 sm:h-48 md:h-64 bg-gray-700 flex items-center justify-center">
                            <span class="text-xs sm:text-sm text-gray-500 px-2 text-center">No Image</span>
                        </div>
                    @endif
                    <div class="p-2 sm:p-4">
                        <h3 class="font-semibold text-xs sm:text-sm truncate group-hover:text-blue-500">{{ $item->title }}</h3>
                        <p class="text-xs text-gray-400">{{ ucfirst($item->type) }}</p>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-gray-400 col-span-3 sm:col-span-3 md:col-span-4 lg:col-span-6">No media in this category</p>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($media->hasPages())
        <div class="flex justify-center">
            {{ $media->links() }}
        </div>
    @endif
</div>
@endsection
