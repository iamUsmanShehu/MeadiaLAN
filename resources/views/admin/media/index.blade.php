@extends('admin.layouts.app')

@section('title', 'Media')

@section('page-title', 'Media Management')

@section('content')
<div class="px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-2xl">
                🎬
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold">Media Library</h2>
        </div>
        <a href="{{ route('admin.media.create') }}" class="btn-primary text-center min-h-[48px] flex items-center justify-center font-semibold">
            ➕ Upload New Media
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-900 text-green-200 rounded-lg border border-green-700 flex items-center gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($pendingCount > 0)
        <div class="mb-6 p-4 bg-blue-900/50 text-blue-200 rounded-lg border border-blue-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl animate-pulse">🛰️</span>
                <div>
                    <p class="font-bold">New Media Detected!</p>
                    <p class="text-sm opacity-80">{{ $pendingCount }} files are waiting for your review and metadata update.</p>
                </div>
            </div>
            <div class="text-xs font-mono bg-blue-800 px-3 py-1 rounded">Run: php artisan media:scan</div>
        </div>
    @endif

    <!-- Table View for Desktop / Card View for Mobile -->
    <!-- Desktop Table -->
    <div class="hidden sm:block card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700 bg-gray-800">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-400">🎬 Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-400">📺 Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-400">📂 Category</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-400">📏 Size</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-blue-400">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($media as $item)
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($item->poster_url)
                                        <img src="{{ asset($item->poster_url) }}" alt="{{ $item->title }}" class="h-10 w-8 object-cover rounded">
                                    @else
                                        <div class="h-10 w-8 bg-gray-600 rounded flex items-center justify-center text-sm">📷</div>
                                    @endif
                                    <span class="text-white font-medium">{{ Str::limit($item->title, 40) }}</span>
                                    @if(!$item->is_processed)
                                        <span class="px-2 py-0.5 bg-yellow-900/50 text-yellow-400 text-[10px] font-bold uppercase rounded border border-yellow-700">Pending Review</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-900 text-blue-200 rounded-full text-xs font-semibold">
                                    {{ $item->type === 'movie' ? '🎥 Movie' : '📺 Series' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $item->category->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $item->getFormattedSizeAttribute() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.media.edit', $item) }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition">✏️ Edit</a>
                                    <form action="{{ route('admin.media.destroy', $item) }}" method="post" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium transition" onclick="return confirm('❌ Delete this media?')">🗑️ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-gray-400 text-center col-span-5" colspan="5">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">📭</span>
                                    <span>No media found. Start uploading!</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden grid grid-cols-1 gap-4">
        @forelse($media as $item)
            <div class="card">
                <div class="flex gap-4">
                    <!-- Poster -->
                    <div class="flex-shrink-0">
                        @if($item->poster_url)
                            <img src="{{ asset($item->poster_url) }}" alt="{{ $item->title }}" class="h-24 w-16 object-cover rounded">
                        @else
                            <div class="h-24 w-16 bg-gray-600 rounded flex items-center justify-center text-lg">📷</div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-white font-semibold line-clamp-2 mb-1">
                                {{ $item->title }}
                                @if(!$item->is_processed)
                                    <span class="inline-block px-1.5 py-0.5 bg-yellow-900/50 text-yellow-500 text-[10px] font-bold uppercase rounded border border-yellow-800 ml-1">New</span>
                                @endif
                            </h3>
                            <div class="flex gap-2 flex-wrap mb-2">
                                <span class="text-xs px-2 py-1 bg-blue-900 text-blue-200 rounded">{{ $item->type === 'movie' ? '🎥' : '📺' }}</span>
                                <span class="text-xs px-2 py-1 bg-gray-700 text-gray-300 rounded">{{ $item->category->name }}</span>
                                <span class="text-xs px-2 py-1 bg-gray-700 text-gray-300 rounded">{{ $item->getFormattedSizeAttribute() }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.media.edit', $item) }}" class="btn-secondary text-xs flex-1 py-2">✏️ Edit</a>
                            <form action="{{ route('admin.media.destroy', $item) }}" method="post" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger w-full text-xs py-2" onclick="return confirm('❌ Delete?')">🗑️ Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-12">
                <span class="text-5xl block mb-4">📭</span>
                <p class="text-gray-300 mb-4">No media found</p>
                <a href="{{ route('admin.media.create') }}" class="btn-primary inline-block">➕ Upload Your First Media</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($media->hasPages())
        <div class="mt-8 px-4">
            {{ $media->links() }}
        </div>
    @endif
</div>
@endsection
